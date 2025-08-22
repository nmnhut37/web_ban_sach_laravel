<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use App\Models\Banner;


class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $products = Product::with('category')->get();
        return view('admin.product_manage.product.product_list', compact('products'));
    }
    // Hiển thị form thêm sản phẩm
    public function create()
    {
        $categories = Category::whereNotNull('parent_id')->get();
        return view('admin.product_manage.product.product_create', compact('categories'));
    }
    // Lưu sản phẩm mới
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock_quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Xử lý upload hình ảnh
        $imageName = null;
        if ($request->hasFile('img')) {
            $imageFile = $request->file('img');
            if ($imageFile->isValid()) {
                $imageName = time() . '-' . $imageFile->getClientOriginalName();
                $imageFile->move(public_path('storage/images/product'), $imageName);
            } else {
                return redirect()->back()->with('error', 'Không thể tải lên hình ảnh. Vui lòng thử lại.');
            }
        }

        Product::create([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'img' => $imageName,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('product_list')->with('success', 'Sản phẩm đã được thêm thành công');
    }
    // Hiển thị form sửa sản phẩm
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::whereNotNull('parent_id')->get();
        return view('admin.product_manage.product.product_edit', compact('product', 'categories'));
    }
    // Cập nhật thông tin sản phẩm
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock_quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::findOrFail($id);

        // Xử lý upload ảnh mới
        $imageUpdated = false;
        if ($request->hasFile('img')) {
            $imageFile = $request->file('img');
            if ($imageFile->isValid()) {
                // Xóa ảnh cũ nếu tồn tại
                if ($product->img && file_exists(public_path('storage/images/product/' . $product->img))) {
                    unlink(public_path('storage/images/product/' . $product->img));
                }

                // Lưu ảnh mới
                $imageName = time() . '-' . $imageFile->getClientOriginalName();
                $imageFile->move(public_path('storage/images/product'), $imageName);
                $product->img = $imageName;
                $imageUpdated = true;
            } else {
                return redirect()->back()->with('error', 'Không thể tải lên hình ảnh. Vui lòng thử lại.');
            }
        }

        // Cập nhật thông tin sản phẩm
        $product->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
        ]);

        if ($imageUpdated) {
            return redirect()->route('product_list')->with('success', 'Sản phẩm và ảnh đã được cập nhật thành công');
        }

        return redirect()->route('product_list')->with('success', 'Thông tin sản phẩm đã được cập nhật thành công');
    }
    // Xóa sản phẩm
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->img) {
            $imagePath = public_path('storage/images/product/' . $product->img);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $product->delete();
        return redirect()->route('product_list')->with('success', 'Sản phẩm đã được xóa thành công');
    }
    // Tìm kiếm sản phẩm admin
    public function search(Request $request)
    {
        try {
            $query = Product::query();

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where('product_name', 'LIKE', "%{$search}%");
            }

            if ($request->has('category_id') && !empty($request->category_id)) {
                $query->where('category_id', $request->category_id);
            }

            $products = $query->get();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'products' => $products]);
            }

            return view('products.search', compact('products'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Có lỗi xảy ra khi tìm kiếm sản phẩm'], 500);
            }
            return back()->with('error', 'Có lỗi xảy ra khi tìm kiếm sản phẩm');
        }
    }
    public function searchshop(Request $request)
    {
        $searchTerm = $request->input('tensp');
        $products = Product::where('product_name', 'like', '%' . $searchTerm . '%')->get();
        $banners = Banner::orderBy('order')->get();
        // Trả về kết quả tìm kiếm (có thể trả về view hoặc JSON tùy nhu cầu)
        return view('shop.search', compact('products', 'banners'));
    }
    // Phương thức để lấy gợi ý sản phẩm
    public function searchSuggestions(Request $request)
    {
        try {
            $query = $request->get('query');

            $products = Product::where('product_name', 'like', "%{$query}%")
                ->select('id', 'product_name', 'img', 'price')
                ->take(5)
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'product_name' => $product->product_name,
                        'img' => $product->img ? asset("storage/images/product/{$product->img}") : asset("storage/images/product/no-image.jpeg"),
                        'price' => $product->price,
                        'price_formatted' => number_format($product->price, 0, ',', '.') . ' đ'
                    ];
                })->toArray();

            return response()->json($products, 200, [
                'Content-Type' => 'application/json',
            ]);

        } catch (\Exception $e) {
            Log::error('Search suggestion error: ' . $e->getMessage());
            return response()->json([], 200);
        }
    }

}
