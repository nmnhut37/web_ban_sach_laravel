<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\PaymentService;

class OrderController extends Controller
{
    protected $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function store(Request $request, PaymentService $paymentService)
    {
        $cart = session('cart');
        $cartTotal = session('cart_total');
        $discountAmount = session('discount_amount', 0);
        $finalTotal = session('final_total');

        if (empty($cart) || !$cartTotal || !$finalTotal) {
            return back()->with('error', 'Giỏ hàng không hợp lệ.');
        }

        try {
            $request->validate([
                'full_name' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'address' => 'required',
                'payment_method' => 'required|in:momo,vnpay,cod'
            ]);

            return $paymentService->processPayment(
                $request->payment_method,
                $request,
                $cart,
                $finalTotal
            );
        } catch (\Exception $e) {
            Log::error('Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Đặt hàng không thành công. Vui lòng thử lại.');
        }
    }


    public function handlePaymentCallback($method, Request $request)
    {
        try {
            return $this->paymentService->handleCallback($method, $request);
        } catch (\Exception $e) {
            Log::error('Payment Callback Error: ' . $e->getMessage());
            return redirect()
                ->route('checkout.index')
                ->with('error', 'Có lỗi xảy ra trong quá trình xử lý callback.');
        }
    }

    public function success(Request $request)
    {
        if ($request->session()->has('order_id')) {
            session()->forget(['cart', 'cart_total', 'discount_amount', 'final_total', 'coupon']);
            $orderId = $request->session()->get('order_id');
            $order = Order::with('items.product')->findOrFail($orderId);

            return view('shop.order.success', compact('order'));
        }

        return redirect()->route('index')->with('error', 'Không tìm thấy thông tin đơn hàng.');
    }

    public function failed(Request $request)
    {
        return view('shop.order.failed');
    }
}
