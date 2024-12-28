<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // Hiển thị danh sách người dùng
    public function index()
    {
        $users = User::all(); // Lấy danh sách người dùng
        return view('admin.account_manage.user', compact('users'));
    }

    // Hiển thị form tạo người dùng mới
    public function create()
    {
        return view('admin.account_manage.user_create');
    }

    // Hiển thị form chỉnh sửa thông tin người dùng
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.account_manage.user_edit', compact('user'));
    }

    // Cập nhật thông tin người dùng
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate dữ liệu
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:11',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'password' => 'nullable|string|min:8|regex:/[A-Za-z]/|regex:/[\W_]/|confirmed',
        ]);

        // Kiểm tra nếu người dùng muốn thay đổi mật khẩu
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password); // Mã hóa mật khẩu mới
        }

        // Xử lý avatar
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            if ($file->isValid()) {
                // Xóa avatar cũ nếu tồn tại
                if ($user->avatar && file_exists(public_path('storage/images/user/' . $user->avatar))) {
                    unlink(public_path('storage/images/user/' . $user->avatar));
                }

                // Lưu avatar mới
                $fileName = md5(time() . uniqid()) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/images/user'), $fileName);
                $user->avatar = $fileName;
            }
        }

        // Cập nhật các trường khác
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->date_of_birth = $request->date_of_birth;
        $user->status = $request->status;

        $user->save();

        return redirect()->route('user.index')->with('success', 'Cập nhật thông tin người dùng thành công.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Xóa avatar nếu tồn tại
        if ($user->avatar && file_exists(public_path('storage/images/user/' . $user->avatar))) {
            unlink(public_path('storage/images/user/' . $user->avatar));
        }

        // Xóa người dùng
        $user->delete();

        return redirect()->route('user.index')->with('success', 'Xóa người dùng thành công.');
    }

    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Za-z]/',
                'regex:/[\W_]/',
                'confirmed',
            ],
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'date_of_birth' => 'nullable|date',
            'status' => 'required|in:verified,unverified',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            // Thông báo lỗi tùy chỉnh
            'name.required' => 'Họ và tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.regex' => 'Mật khẩu phải có ít nhất một chữ cái và một ký tự đặc biệt.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái phải là đã xác thực hoặc chưa xác thực.',
            'avatar.image' => 'Ảnh đại diện phải là file ảnh.',
            'avatar.mimes' => 'Ảnh đại diện phải có định dạng JPG, PNG, hoặc GIF.',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 5MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Xử lý upload avatar
        $fileName = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($file->isValid()) {
                $fileName = md5(time() . uniqid()) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/images/user'), $fileName);
            }
        }

        // Tạo người dùng mới
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->date_of_birth = $request->date_of_birth;
            $user->status = $request->status;
            $user->avatar = $fileName;
            $user->password = bcrypt($request->password);
            $user->save();

            return redirect()->route('user.index')->with('success', 'Thêm người dùng mới thành công!');
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Không thể thêm người dùng. Vui lòng thử lại.');
        }
    }
}
