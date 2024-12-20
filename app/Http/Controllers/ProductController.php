<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $products = Product::with('category')->get();
        return view('Admin.product_manage.Product.product_list', compact('products'));
    }

    // Hiển thị form thêm sản phẩm
    public function create()
    {
        $categories = Category::whereNotNull('parent_id')->get();
        return view('Admin.product_manage.product.product_create', compact('categories'));
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
            }
        }
        if (!$imageName) {
            return redirect()->back()->with('error', 'Không thể tải lên hình ảnh. Vui lòng thử lại.');
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
        return view('Admin.product_manage.product.product_edit', compact('product', 'categories'));
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
        if ($request->hasFile('img')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($product->img && file_exists(public_path('storage/images/product/' . $product->img))) {
                unlink(public_path('storage/images/product/' . $product->img));
            }
            // Lưu ảnh mới
            $imageFile = $request->file('img');
            $imageName = time() . '-' . $imageFile->getClientOriginalName();
            $imageFile->move(public_path('storage/images/product'), $imageName);
            $product->img = $imageName;
        }
        $product->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
        ]);
    
        return redirect()->route('product_list')->with('success', 'Sản phẩm đã được cập nhật thành công');
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
}
