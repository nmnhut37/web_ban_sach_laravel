<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderSuccessMail;


class PaymentService
{
    public function processPayment($method, $request, $cart, $finalTotal)
    {
        $cartTotal = session('cart_total');
        $discountAmount = session('discount_amount', 0);

        switch ($method) {
            case 'vnpay':
                return $this->processVnPayPayment($request ,$finalTotal);
            case 'cod':
                return $this->createOrder(
                    $request,
                    $cart,
                    $cartTotal,
                    $discountAmount,
                    $finalTotal
                );
            default:
                throw new \Exception("Phương thức thanh toán không hợp lệ");
        }
    }

    private function createOrder($request, $cart, $cartTotal, $discountAmount, $finalTotal)
    {
        $order = Order::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'order_number' => 'ORD-' . Str::random(10),
            'name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'note' => $request->note,
            'total_amount' => $cartTotal,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalTotal,
            'payment_method' => $request->payment_method,
            'order_status' => 'pending',
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        Mail::to($order->email)->send(new OrderSuccessMail($order));

        $request->session()->put('order_id', $order->id);
        session()->forget(['cart', 'cart_total', 'discount_amount', 'final_total', 'coupon']);

        return redirect()
            ->route('order.success')
            ->with('success', 'Đặt hàng thành công! Chúng tôi sẽ liên hệ với bạn sớm.');
    }

    public function handleCallback($method, $request)
    {
        switch ($method) {
            case 'vnpay':
                return $this->processVnPayCallback($request, session('checkout_info'));
            default:
                throw new \Exception("Phương thức xử lý callback không hợp lệ");
        }
    }
    
    public function processVnPayPayment($request ,$finalTotal)
    {
        session([
            'checkout_info' => [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'note' => $request->note
            ]
        ]);
        $vnp_HashSecret = config('payment.vnpay.hash_secret');
        $vnp_TmnCode = config('payment.vnpay.tmn_code');
        $vnp_Url = config('payment.vnpay.url');
        $vnp_ReturnUrl = config('payment.vnpay.return_url');

        // Format theo đúng yêu cầu của VNPay
        $vnp_TxnRef = date('YmdHis'); // Mã giao dịch, format theo thời gian
        $vnp_OrderInfo = "Thanh toan don hang"; // Không dùng ký tự có dấu
        $finalTotal = (int)$finalTotal; // Đảm bảo là số nguyên

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $finalTotal * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(), // Thêm IP người dùng
            "vnp_Locale" => "vn",           // Thêm ngôn ngữ
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "ncb", // Thêm loại thanh toán
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect()->away($vnp_Url);
    }

    private function processVnPayCallback($request, $checkoutInfo)
    {
        
        $vnp_HashSecret = config('payment.vnpay.hash_secret');
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = [];
    
        // Lấy tất cả dữ liệu từ request
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
    
        // Loại bỏ hash
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
    
        // Tạo chuỗi hash mới
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        
    
        // Kiểm tra hash
        if ($secureHash === $vnp_SecureHash) {
            // Kiểm tra response code
            switch ($request->vnp_ResponseCode) {
                case '00': // Thành công
                    try {
                        $request->merge([
                            ...$checkoutInfo,
                            'payment_method' => 'vnpay'
                        ]);
                        // Lấy dữ liệu cần thiết từ session
                        $cart = session('cart', []);
                        $cartTotal = session('cart_total', 0);
                        $discountAmount = session('discount_amount', 0);
                        $finalTotal = $inputData['vnp_Amount'] / 100;
    
                        // Tạo đơn hàng
                        $this->createOrder(
                            $request,
                            $cart,
                            $cartTotal,
                            $discountAmount,
                            $finalTotal
                        );

                        Mail::to($order->email)->send(new OrderSuccessMail($order));

                        session()->forget('checkout_info');
                        return redirect()
                            ->route('order.success')
                            ->with('success', 'Thanh toán thành công! Đơn hàng của bạn đã được tạo.');
                    } catch (\Exception $e) {
                        Log::error('VNPay Callback Error: ' . $e->getMessage());
                        return redirect()
                            ->route('checkout.index')
                            ->with('error', 'Có lỗi xảy ra trong quá trình xử lý đơn hàng.');
                    }
    
                case '24': // Người dùng hủy thanh toán
                    return redirect()
                        ->route('checkout.index')
                        ->with('warning', 'Bạn đã hủy thanh toán. Đơn hàng chưa được tạo.');
    
                default: // Các trường hợp lỗi khác
                    return redirect()
                        ->route('checkout.index')
                        ->with('error', 'Thanh toán không thành công. Mã lỗi: ' . $request->vnp_ResponseCode);
            }
        }
    
        // Hash không hợp lệ
        Log::error('VNPay Invalid Hash: ' . $request->fullUrl());
        Log::info('VNPay Response:', [
            'ResponseCode' => $request->vnp_ResponseCode,
            'SecureHash' => $vnp_SecureHash,
            'CalculatedHash' => $secureHash
        ]);
        return redirect()
            ->route('checkout.index')
            ->with('error', 'Dữ liệu thanh toán không hợp lệ.');
    }
}
