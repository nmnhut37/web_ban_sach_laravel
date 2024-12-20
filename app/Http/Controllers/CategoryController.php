<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Hiển thị danh mục cha với danh mục con
    public function showCategories()
    {
        $parentCategories = Category::whereNull('parent_id')->with('children')->get();
        return view('layout.shop_layout', compact('parentCategories'));
    }

    // Hiển thị sản phẩm theo danh mục (cha hoặc con)
    public function showCategory($id)
    {
        $category = Category::with('children')->findOrFail($id);

        // Nếu là danh mục cha
        if (is_null($category->parent_id)) {
            $products = $category->products()->get();
            return view('shop.parent_category', compact('category', 'products'));
        }

        // Nếu là danh mục con
        $products = $category->products()->get();
        return view('shop.child_category', compact('category', 'products'));
    }

    // Hiển thị danh sách danh mục cha
    public function index()
    {
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.product_manage.category.categories', compact('categories'));
    }

    // Hiển thị danh sách danh mục con của một danh mục cha
    public function showChildren($id)
    {
        $parentCategory = Category::findOrFail($id);
        $childCategories = $parentCategory->children()->get();
        return view('admin.product_manage.category.categories_children', compact('parentCategory', 'childCategories'));
    }

    // Tạo danh mục cha
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'parent_id' => null,
        ]);

        return redirect()->route('categories.index')->with('success', 'Danh mục cha đã được thêm thành công.');
    }

    // Tạo danh mục con
    public function storeChildren(Request $request, $parentCategoryId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'parent_id' => $parentCategoryId,
        ]);

        return redirect()->route('categories.children', $parentCategoryId)->with('success', 'Danh mục con đã được thêm thành công.');
    }

    // Hiển thị form tạo danh mục cha
    public function create()
    {
        return view('admin.product_manage.category.categories_create');
    }

    // Hiển thị form tạo danh mục con
    public function createChildren($parentCategoryId)
    {
        $parentCategory = Category::findOrFail($parentCategoryId);
        return view('admin.product_manage.category.categories_create_children', compact('parentCategory'));
    }

    // Hiển thị form chỉnh sửa danh mục
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.product_manage.category.categories_edit', compact('category', 'parentCategories'));
    }

    // Cập nhật danh mục
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('categories.index')->with('success', 'Danh mục đã được cập nhật.');
    }

    // Xóa danh mục
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Kiểm tra xem danh mục có sản phẩm hoặc danh mục con không
        if ($category->children()->exists() || $category->products()->exists()) {
            return redirect()->route('categories.index')->with('error', 'Không thể xóa danh mục có sản phẩm hoặc danh mục con.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Danh mục đã được xóa.');
    }
}
