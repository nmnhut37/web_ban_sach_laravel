<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Log;

class BannerController extends Controller
{
    /**
     * Hiển thị danh sách banner.
     */
    public function index()
    {
        $banners = Banner::get();
        return view('Admin.banner_manage.banner', compact('banners'));
    }

    /**
     * Hiển thị form tạo mới banner.
     */
    public function create()
    {
        return view('Admin.banner_manage.banner_create');
    }

    /**
     * Lưu banner mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5048',
            'description' => 'nullable|string|max:255',
            'order' => 'required|integer',
            'url' => 'nullable|url|max:255',
        ]);

        // Kiểm tra nếu thứ tự đã tồn tại trong cơ sở dữ liệu
        $existingBanner = Banner::where('order', $request->order)->first();
        if ($existingBanner) {
            return redirect()->back()->with('error', 'Thứ tự banner đã tồn tại. Vui lòng chọn thứ tự khác.');
        }

        try {
            // Xử lý upload hình ảnh
            $imageName = null;
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                if ($imageFile->isValid()) {
                    $imageName = time() . '-' . $imageFile->getClientOriginalName();
                    $imageFile->move(public_path('storage/images/Banner'), $imageName);
                }
            }

            if (!$imageName) {
                return redirect()->back()->with('error', 'Không thể tải lên hình ảnh. Vui lòng thử lại.');
            }

            // Lưu thông tin banner vào cơ sở dữ liệu
            Banner::create([
                'image' => $imageName,
                'description' => $request->description,
                'order' => $request->order,
                'url' => $request->url,
            ]);

            return redirect()->route('banners.index')->with('success', 'Thêm banner thành công!');
        } catch (\Exception $e) {
            // Log lỗi để kiểm tra (nếu cần)
            Log::error('Lỗi thêm banner: ' . $e->getMessage());

            // Trả về thông báo lỗi cho người dùng
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi thêm banner. Vui lòng thử lại sau.');
        }
    }


    /**
     * Hiển thị form chỉnh sửa banner.
     */
    public function edit(Banner $banner)
    {
        return view('Admin.banner_manage.banner_edit', compact('banner'));
    }

    /**
     * Cập nhật banner trong cơ sở dữ liệu.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:255',
            'order' => 'required|integer',
            'url' => 'nullable|url|max:255',
        ]);

        // Kiểm tra nếu thứ tự mới đã tồn tại trong cơ sở dữ liệu (trừ bản ghi hiện tại)
        $existingBanner = Banner::where('order', $request->order)
                                ->where('id', '!=', $banner->id)  // loại trừ banner hiện tại
                                ->first();

        if ($existingBanner) {
            return redirect()->back()->with('error', 'Thứ tự banner đã tồn tại. Vui lòng chọn thứ tự khác.');
        }

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($banner->image && file_exists(public_path('storage/images/Banner/' . $banner->image))) {
                unlink(public_path('storage/images/Banner/' . $banner->image));
            }

            // Lưu ảnh mới vào thư mục storage/images/Banner
            $imageFile = $request->file('image');
            $imageName = time() . '-' . $imageFile->getClientOriginalName();
            $imageFile->move(public_path('storage/images/Banner'), $imageName);
            $banner->image = $imageName;
        }

        $banner->description = $request->description;
        $banner->order = $request->order;
        $banner->url = $request->url;
        $banner->save();

        return redirect()->route('banners.index')->with('success', 'Cập nhật banner thành công!');
    }

    /**
     * Xóa banner khỏi cơ sở dữ liệu.
     */
    public function destroy(Banner $banner)
    {
        // Xóa ảnh khỏi thư mục storage/images/Banner
        if ($banner->image && file_exists(public_path('storage/images/Banner/' . $banner->image))) {
            unlink(public_path('storage/images/Banner/' . $banner->image));
        }

        $banner->delete();

        return redirect()->route('banners.index')->with('success', 'Xóa banner thành công!');
    }
}
