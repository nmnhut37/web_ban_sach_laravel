<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate đầu vào
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'rating' => 'required|integer|between:1,5',
                'comment' => 'required|string|min:10|max:1000'
            ], [
                'rating.required' => 'Vui lòng chọn số sao đánh giá',
                'rating.between' => 'Số sao đánh giá phải từ 1 đến 5',
                'comment.required' => 'Vui lòng nhập nội dung đánh giá',
                'comment.min' => 'Nội dung đánh giá phải có ít nhất 10 ký tự',
                'comment.max' => 'Nội dung đánh giá không được vượt quá 1000 ký tự'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Kiểm tra xem người dùng đã mua sản phẩm chưa
            $hasPurchased = Order::where('user_id', Auth::id())
                ->whereHas('orderItems', function($query) use ($request) {
                    $query->where('product_id', $request->product_id);
                })
                ->where('order_status', 'completed')
                ->exists();

            if (!$hasPurchased) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn cần mua sản phẩm trước khi đánh giá'
                ], 403);
            }

            // Kiểm tra xem đã đánh giá chưa
            $existingReview = Review::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã đánh giá sản phẩm này rồi'
                ], 403);
            }

            // Tạo đánh giá mới
            Review::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đánh giá của bạn đã được ghi nhận'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Review Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau'
            ], 500);
        }
    }
    // Hiển thị danh sách đánh giá
    public function index()
    {
        $reviews = Review::with('product')->get();  // Lấy tất cả đánh giá với thông tin sản phẩm
        return view('admin.review_manage.index', compact('reviews'));
    }

    // Hiển thị form thêm mới đánh giá
    public function create()
    {
        $users = User::all();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $products = \App\Models\Product::all();  // Lấy tất cả sản phẩm
        return view('admin.review_manage.create', compact('products', 'categories', 'users'));
    }

    // Xử lý lưu đánh giá mới
    public function add(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',   // Đảm bảo người dùng tồn tại
            'product_id' => 'required|exists:products,id', // Đảm bảo sản phẩm tồn tại
            'rating' => 'required|integer|min:1|max:5',  // Kiểm tra rating hợp lệ
            'comment' => 'nullable|string|max:255',  // Kiểm tra comment nếu có
        ]);

        // Kiểm tra nếu người dùng đã đánh giá sản phẩm này
        $existingReview = Review::where('user_id', $request->input('user_id'))
                                ->where('product_id', $request->input('product_id'))
                                ->first();

        if ($existingReview) {
            return redirect()->back()->withErrors(['error' => 'Người dùng đã đánh giá sản phẩm này!']);
        }

        // Nếu chưa có đánh giá, lưu mới
        Review::create([
            'user_id' => $request->input('user_id'),
            'product_id' => $request->input('product_id'),
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
        ]);

        return redirect()->route('reviews.index')->with('success', 'Đánh giá đã được thêm thành công!');
    }

    // Hiển thị form sửa đánh giá
    public function edit($id)
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $review = Review::findOrFail($id);
        $users = User::all();
        $products = \App\Models\Product::all();
        return view('admin.review_manage.edit', compact('review', 'users', 'categories', 'products'));
    }

    // Xử lý sửa đánh giá
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ]);

        $review = Review::findOrFail($id);
        $review->update($request->all());

        return redirect()->route('reviews.index')->with('success', 'Đánh giá đã được cập nhật thành công!');
    }

    // Xử lý xóa đánh giá
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        return redirect()->route('reviews.index')->with('success', 'Đánh giá đã được xóa!');
    }
    public function getProductsByCategory(Request $request)
    {
        $categoryId = $request->input('category_id');
        
        // Lấy tất cả sản phẩm thuộc danh mục này
        $products = Product::where('category_id', $categoryId)->get();

        return response()->json(['products' => $products]);
    }
}