<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    // Hiển thị danh sách mã giảm giá
    public function index()
    {
        $discounts = Discount::all();
        return view('admin.discount_manage.discount_index', compact('discounts'));
    }

    // Hiển thị trang thêm mã giảm giá
    public function create()
    {
        return view('admin.discount_manage.discount_create');
    }

    // Thêm mã giảm giá mới
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:discounts',
            'discount_percentage' => 'required|numeric',
            'expires_at' => 'required|date',
        ]);

        $discount = Discount::create($request->all());
        return redirect()->route('discounts.index')->with('success', 'Thêm mã giảm giá thành công.');
    }

    // Hiển thị trang sửa mã giảm giá
    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        return view('admin.discount_manage.discount_edit', compact('discount'));
    }

    // Sửa mã giảm giá
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:discounts,code,' . $id,
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'expires_at' => 'required|date',
        ]);

        $discount = Discount::findOrFail($id);
        $discount->update($request->all());

        return redirect()->route('discounts.index')->with('success', 'Cập nhật mã giảm giá thành công.');
    }

    // Xóa mã giảm giá
    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();

        return redirect()->route('discounts.index')->with('success', 'Xóa mã giảm giá thành công.');
    }
}
