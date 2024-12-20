<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        $cartItems = session('cart', []); // Giỏ hàng từ session
        $totalPrice = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
        $discount = session('discount', 0);

        return view('Shop.cart', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'discount' => $discount,
            'finalTotal' => $totalPrice - $discount,
        ]);
    }

    // Thêm sản phẩm vào giỏ hàng
    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);  // Lấy sản phẩm từ cơ sở dữ liệu

        // Kiểm tra số lượng sản phẩm
        $quantity = $request->input('quantity', 1);
        if ($quantity < 1) {
            return response()->json(['message' => 'Số lượng không hợp lệ.'], 400);
        }

        $cart = session('cart', []);  // Lấy giỏ hàng từ session

        // Kiểm tra xem sản phẩm đã có trong giỏ hay chưa
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;  // Cộng dồn số lượng nếu sản phẩm đã có trong giỏ
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'price' => $product->price,
                'quantity' => $quantity,
                'img' => $product->img,
            ];
        }

        session(['cart' => $cart]);  // Lưu giỏ hàng vào session

        return response()->json([
            'success' => true,  // Trả về thông báo thành công
            'message' => 'Sản phẩm đã được thêm vào giỏ hàng.',
            'cartCount' => count($cart),  // Trả về số lượng sản phẩm trong giỏ
        ]);
    }

    // Áp dụng mã giảm giá
    public function applyDiscount(Request $request)
    {
        $discountCode = $request->input('discountCode');
        $discount = Discount::where('code', $discountCode)->first();

        if (!$discount || !$discount->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.',
            ]);
        }

        session(['discount' => $discount->value]);

        return response()->json([
            'success' => true,
            'discount' => $discount->value,
        ]);
    }

    public function updateCart(Request $request)
    {
        $productId = $request->input('productId');
        $quantity = $request->input('quantity');
    
        // Kiểm tra xem sản phẩm có trong giỏ hàng không
        $cart = session()->get('cart', []);
    
        if (isset($cart[$productId])) {
            // Cập nhật số lượng sản phẩm trong giỏ hàng
            $cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $cart);
    
            // Tính lại tổng giá trị giỏ hàng
            $totalPrice = 0;
            foreach ($cart as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
            }
    
            $itemTotal = $cart[$productId]['price'] * $quantity;
    
            return response()->json([
                'success' => true,
                'totalPrice' => number_format($totalPrice, 0, ',', '.'),
                'itemTotal' => number_format($itemTotal, 0, ',', '.')
            ]);
        }
    
        return response()->json(['success' => false]);
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function remove(Request $request, $productId)
    {
        // Kiểm tra giỏ hàng có tồn tại không
        if (session()->has('cart')) {
            $cart = session()->get('cart');
            
            // Kiểm tra sản phẩm có trong giỏ không
            if (isset($cart[$productId])) {
                unset($cart[$productId]); // Xóa sản phẩm khỏi giỏ hàng

                session()->put('cart', $cart); // Cập nhật lại giỏ hàng trong session

                // Tính lại tổng tiền giỏ hàng
                $totalPrice = 0;
                foreach ($cart as $item) {
                    $totalPrice += $item['price'] * $item['quantity'];
                }

                // Kiểm tra xem giỏ hàng có trống không
                $cartEmpty = count($cart) == 0;

                return response()->json([
                    'success' => true,
                    'totalPrice' => number_format($totalPrice, 0, ',', '.'), // Trả về tổng tiền
                    'cartEmpty' => $cartEmpty, // Trả về trạng thái giỏ hàng trống
                ]);
            }
        }

        return response()->json(['success' => false]);
    }

    // Xóa toàn bộ giỏ hàng
    public function clear(Request $request)
    {
        // Xóa hết tất cả sản phẩm trong giỏ hàng
        session()->forget('cart');
        
        // Trả về phản hồi dưới dạng JSON
        return response()->json([
            'success' => true
        ]);
    }
}
