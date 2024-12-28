<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class OrderManageController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->get();
        return view('admin.order_manage.order', compact('orders'));
    }
    public function create()
    {
        return view('admin.order_manage.create');
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('admin.order_manage.edit', compact('order', 'categories'));
    }
    public function show($orderId)
    {
        $order = Order::with('orderItems.product')->findOrFail($orderId);
        
        return view('admin.order_manage.show', compact('order'));
    }

    public function addItem(Request $request, $orderId)
    {
        try {
            // Validate đầu vào
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            // Tìm đơn hàng và sản phẩm
            $order = Order::findOrFail($orderId);
            $product = Product::findOrFail($request->product_id);

            // Kiểm tra sản phẩm đã tồn tại trong đơn hàng chưa
            $orderItem = OrderItem::where('order_id', $orderId)
                ->where('product_id', $request->product_id)
                ->first();

            if ($orderItem) {
                // Nếu sản phẩm đã tồn tại, cập nhật số lượng
                $orderItem->quantity += $request->quantity;
                $orderItem->save();
            } else {
                // Nếu sản phẩm chưa tồn tại, tạo mới
                $orderItem = new OrderItem([
                    'order_id' => $orderId,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $product->price
                ]);
                $orderItem->save();
            }

            // Cập nhật tổng tiền đơn hàng
            $order->updateTotalAmount();

            // Trả về response với thông tin sản phẩm và tổng tiền mới
            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $orderItem->id,
                    'name' => $product->product_name,
                    'price' => $orderItem->price,
                    'quantity' => $orderItem->quantity,
                    'total' => $orderItem->getSubtotalAttribute()
                ],
                'order' => [
                    'total_amount' => $order->total_amount,
                    'final_amount' => $order->final_amount
                ],
                'message' => 'Đã thêm sản phẩm vào đơn hàng'
            ]);

        } catch (\Exception $e) {
            Log::error('Error adding item to order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroyOrderItem($orderId, $itemId)
    {
        try {
            // Tìm order item
            $orderItem = OrderItem::where('order_id', $orderId)
                ->where('id', $itemId)
                ->firstOrFail();

            // Lưu order_id trước khi xóa item
            $order = Order::findOrFail($orderId);

            // Xóa item
            $orderItem->delete();

            // Cập nhật lại tổng tiền đơn hàng
            $order->updateTotalAmount();

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi đơn hàng',
                'order' => [
                    'total_amount' => $order->total_amount,
                    'final_amount' => $order->final_amount
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error removing item from order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa sản phẩm'
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            // Validate dữ liệu đầu vào
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'payment_method' => 'required|in:cod,vnpay',
                'order_status' => 'required|in:pending,processing,shipping,completed,cancelled',
                'items' => 'required|array',
                'items.*.id' => 'required|exists:order_items,id',
                'items.*.quantity' => 'required|integer|min:1',
                'discount_amount' => 'nullable|numeric|min:0',
                'note' => 'nullable|string|max:1000' // Thêm validate cho note
            ]);

            // Tìm đơn hàng
            $order = Order::findOrFail($id);

            // Cập nhật thông tin đơn hàng
            $order->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'payment_method' => $request->payment_method,
                'order_status' => $request->order_status,
                'discount_amount' => $request->discount_amount ?? 0,
                'note' => $request->note // Thêm cập nhật note
            ]);

            // Cập nhật số lượng các sản phẩm
            foreach ($request->items as $item) {
                OrderItem::where('id', $item['id'])
                    ->where('order_id', $order->id)
                    ->update(['quantity' => $item['quantity']]);
            }

            // Cập nhật tổng tiền
            $order->updateTotalAmount();

            return redirect()
                ->route('orders.index')
                ->with('success', 'Đơn hàng đã được cập nhật thành công');

        } catch (\Exception $e) {
            Log::error('Error updating order: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật đơn hàng: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            
            // Xóa tất cả các items của đơn hàng
            $order->orderItems()->delete();
            
            // Xóa đơn hàng
            $order->delete();

            return redirect()
                ->route('orders.index')
                ->with('success', 'Đơn hàng đã được xóa thành công');

        } catch (\Exception $e) {
            Log::error('Error deleting order: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra khi xóa đơn hàng: ' . $e->getMessage());
        }
    }
    public function store(Request $request)
    {
        $orderNumber = 'ORD-' . strtoupper(uniqid()); // Tạo mã đơn hàng duy nhất

        $orderData = $request->all();
        $orderData['order_number'] = $orderNumber;
        
        // Thêm giá trị mặc định cho total_amount nếu chưa có
        $orderData['total_amount'] = $orderData['total_amount'] ?? 0;
        $orderData['final_amount'] = $orderData['final_amount'] ?? 0;

        $order = Order::create($orderData);
        return redirect()->route('orders.edit', $order->id);
    }
}
