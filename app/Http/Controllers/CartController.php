<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    // Lấy giỏ hàng
    public function index()
    {
        $cart = session('cart', []);
        $cart_total = $this->formatCurrency($this->calculateCartTotal($cart));

        return view('shop.cart', compact('cart', 'cart_total'));
    }
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $cart = session('cart', []);
        $cart_total = $this->calculateCartTotal($cart);

        // Kiểm tra thông tin mã giảm giá từ request
        $coupon = $request->input('coupon', null);
        $discount_amount = 0;
        $final_total = $cart_total;

        if ($coupon) {
            $discount = Discount::where('code', $coupon)->first();
            if ($discount && $discount->isValid()) {
                $discount_amount = $cart_total * ($discount->discount_percentage / 100);
                $final_total = $cart_total - $discount_amount;
            }
        }

        // Đảm bảo final_total là số nguyên
        $final_total = (int) round($final_total); // Làm tròn và ép kiểu thành số nguyên

        // Lưu thông tin vào session
        session([
            'cart' => $cart,
            'cart_total' => $cart_total,
            'discount_amount' => $discount_amount,
            'final_total' => $final_total,
            'coupon' => $coupon,
        ]);

        // Chuyển đến view checkout
        return view('shop.order.checkout', compact('user', 'cart', 'cart_total', 'discount_amount', 'final_total', 'coupon'));
    }

    public function addToCart(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại.']);
        }

        if ($product->stock_quantity <= 0) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm đã hết hàng.']);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $newQuantity = $cart[$id]['quantity'] + $request->quantity;
            if ($newQuantity > $product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng yêu cầu vượt quá số lượng tồn kho.'
                ]);
            }
            $cart[$id]['quantity'] = $newQuantity;
        } else {
            if ($request->quantity > $product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng yêu cầu vượt quá số lượng tồn kho.'
                ]);
            }
            $cart[$id] = [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'quantity' => (int)$request->quantity,
                'price' => number_format((float)$product->price, 2, '.', ''),
                'img' => $product->img,
                'stock_quantity' => $product->stock_quantity,
            ];
        }

        session()->put('cart', $cart);
        $cart_total = $this->calculateCartTotal($cart);
        session(['cart_total' => $cart_total]);

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
            'cart_total' => $this->formatCurrency($this->calculateCartTotal($cart)),
        ]);
    }

    public function delete(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            $cart_total = $this->calculateCartTotal($cart);
            session(['cart_total' => $cart_total]);

            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng',
                'cart_total' => $this->formatCurrency($this->calculateCartTotal($cart))
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy sản phẩm trong giỏ hàng'
        ]);
    }

    public function updateQuantity(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            if ($request->quantity > $cart[$request->id]['stock_quantity'] || $request->quantity < 1) {
                return response()->json([
                    'success' => false,
                    'message' => $request->quantity < 1 
                        ? 'Số lượng không được nhỏ hơn 1.' 
                        : 'Số lượng yêu cầu vượt quá số lượng tồn kho.'
                ]);
            }
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);

            $item_total = $cart[$id]['price'] * $cart[$id]['quantity'];
            $cart_total = $this->calculateCartTotal($cart);
            session(['cart_total' => $cart_total]);

            return response()->json([
                'success' => true,
                'item_total' => $this->formatCurrency($item_total),
                'cart_total' => $this->formatCurrency($cart_total),
            ]);
        }
        return response()->json(['success' => false]);
    }

    public function applyCoupon(Request $request)
    {
        $coupon = $request->coupon;
        $discount = Discount::where('code', $coupon)->first();

        if ($discount && $discount->isValid()) {
            $cart_total = $this->calculateCartTotal(session('cart', []));
            $discount_amount = $cart_total * ($discount->discount_percentage / 100);
            $final_total = $cart_total - $discount_amount;

            // Đảm bảo final_total là số nguyên
            $final_total = (int) round($final_total); // Làm tròn và ép kiểu thành số nguyên

            session(['discount_amount' => $discount_amount, 'final_total' => $final_total]);

            return response()->json([
                'success' => true,
                'discount' => $this->formatCurrency($discount_amount),
                'final_total' => $this->formatCurrency($final_total)
            ]);
        }
        return response()->json(['success' => false, 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.']);
    }


    // Hàm tính tổng giỏ hàng
    private function calculateCartTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += (float)$item['price'] * (int)$item['quantity'];
        }
        return (int) round($total);  // Làm tròn và ép kiểu thành số nguyên
    }


    private function formatCurrency($value)
    {
        return number_format($value, 0, '.', ',') . ' đ';
    }

    public function getCartCount()
    {
        // Lấy giỏ hàng từ session
        $cart = session()->get('cart', []);

        // Tính tổng số lượng sản phẩm trong giỏ hàng
        $cartCount = 0;
        foreach ($cart as $item) {
            $cartCount += $item['quantity'];
        }

        return response()->json([
            'cart_count' => $cartCount
        ]);
    }
}
