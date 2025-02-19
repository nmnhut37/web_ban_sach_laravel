<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    // Hiển thị trang profile
    public function showProfile()
    {
        $orders = Order::where('user_id', auth::id())->get();
        return view('shop.profile.profile', compact('orders'));
    }
    // Phương thức cập nhật thông tin người dùng
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
        ]);

        $user = User::findOrFail(Auth::id());
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->date_of_birth = $request->date_of_birth;

        $user->save();

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công.');
    }

    // Phương thức cập nhật avatar người dùng
    public function updateAvatar(Request $request)
    {
        try {
            // Validate dữ liệu input
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Giới hạn 5MB
            ]);

            // Lấy thông tin người dùng hiện tại
            $user = User::findOrFail(Auth::id());

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');

                // Kiểm tra tính hợp lệ của file
                if ($file->isValid()) {
                    // Xóa ảnh cũ nếu tồn tại
                    if ($user->avatar) {
                        $oldAvatarPath = public_path('storage/images/user/' . $user->avatar);
                        if (file_exists($oldAvatarPath)) {
                            unlink($oldAvatarPath);
                        }
                    }

                    // Tạo tên file mới sử dụng mã hóa của Laravel
                    $fileName = md5(time() . uniqid()) . '.' . $file->getClientOriginalExtension();

                    // Lưu file vào thư mục public
                    $destinationPath = public_path('storage/images/user');
                    $file->move($destinationPath, $fileName);

                    // Cập nhật tên file trong database
                    $user->avatar = $fileName;
                    $user->save();

                    return redirect()->back()->with('success', 'Cập nhật ảnh đại diện thành công');
                }
            }

            return redirect()->back()->with('error', 'Không tìm thấy file upload hợp lệ');

        } catch (\Exception $e) {
            // Ghi log lỗi
            Log::error('Update avatar error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật ảnh đại diện');
        }
    }


    // Phương thức cập nhật mật khẩu người dùng
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Za-z]/',
                'regex:/[\W_]/',
                'confirmed',
                'different:current_password'
            ],
        ]);
        $user = User::findOrFail(Auth::id());
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Mật khẩu hiện tại không đúng.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Cập nhật mật khẩu thành công.');
    }

    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return view('shop.profile.order_detail', compact('order'));
        
    }
    
}