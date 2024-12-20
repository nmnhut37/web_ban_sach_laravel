<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng của người dùng
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->get(); // Lấy đơn hàng của người dùng
        return view('shop.checkout', compact('orders')); // Chuyển đến view danh sách đơn hàng
    }

    // Tạo đơn hàng mới
    public function create(Request $request)
    {
        $cartItems = session('cart'); // Lấy giỏ hàng từ session

        // Kiểm tra nếu giỏ hàng trống
        if (empty($cartItems)) {
            return redirect()->route('shop.index')->with('error', 'Giỏ hàng của bạn hiện tại trống!');
        }

        $totalPrice = 0;

        // Kiểm tra số lượng trong kho và tính tổng tiền cho đơn hàng
        foreach ($cartItems as $item) {
            $product = Product::find($item['id']);

            // Kiểm tra sản phẩm có tồn tại không
            if (!$product) {
                return redirect()->route('shop.index')->with('error', 'Sản phẩm không tồn tại.');
            }

            // Kiểm tra số lượng tồn kho
            if ($product->stock_quantity < $item['quantity']) {
                return redirect()->route('shop.index')->with('error', 'Số lượng sản phẩm ' . $product->product_name . ' không đủ trong kho.');
            }

            $totalPrice += $item['price'] * $item['quantity']; // Tính tổng tiền đơn hàng
        }

        // Tạo đơn hàng
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $totalPrice,
            'status' => 'pending',
            'address' => $request->address, // Lưu địa chỉ giao hàng
        ]);

        // Lưu các mục trong đơn hàng và giảm số lượng trong kho
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Giảm số lượng trong kho sản phẩm
            Product::where('id', $item['id'])->decrement('stock_quantity', $item['quantity']);
        }

        // Xóa giỏ hàng sau khi đặt đơn
        session()->forget('cart');

        // Quay lại danh sách đơn hàng với thông báo thành công
        return redirect()->route('cart.index')->with('success', 'Đơn hàng đã được tạo thành công!');
    }

    // Xem chi tiết đơn hàng
    public function show($orderId)
    {
        $order = Order::with('orderItems.product')->findOrFail($orderId); // Lấy đơn hàng với chi tiết sản phẩm

        // Kiểm tra xem người dùng có quyền xem đơn hàng này không
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('cart.index')->with('error', 'Bạn không có quyền xem đơn hàng này.');
        }

        return view('shop.orders.show', compact('order')); // Hiển thị chi tiết đơn hàng
    }
}
