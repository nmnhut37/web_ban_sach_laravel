<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


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
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // Thêm điều kiện validate cho mật khẩu nếu có
            'password' => 'nullable|string|min:8|regex:/[A-Za-z]/|regex:/[\W_]/|confirmed',
        ]);

        // Kiểm tra nếu người dùng muốn thay đổi mật khẩu
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);  // Mã hóa mật khẩu mới
        }

        // Cập nhật thông tin người dùng
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($user->avatar && file_exists(public_path('storage/images/user/' . $user->avatar))) {
                unlink(public_path('storage/images/user/' . $user->avatar));
            }

            // Lưu ảnh mới vào thư mục 'storage/images/user'
            $avatarFile = $request->file('avatar');
            $avatarName = time() . '-' . $avatarFile->getClientOriginalName();
            $avatarFile->move(public_path('storage/images/user'), $avatarName);
            $user->avatar = $avatarName;
        }

        // Cập nhật các trường còn lại
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

        // Xóa ảnh nếu tồn tại
        if ($user->avatar && file_exists(public_path('storage/images/user/' . $user->avatar))) {
            unlink(public_path('storage/images/user/' . $user->avatar));
        }

        // Xóa người dùng
        $user->delete();

        return redirect()->route('user.index')->with('success', 'Xóa người dùng thành công.');
    }

    
    public function store(Request $request)
    {
        // Xác thực dữ liệu (bao gồm mật khẩu)
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => [
                'required',
                'string',
                'min:8', // Mật khẩu phải có ít nhất 8 ký tự
                'regex:/[A-Za-z]/', // Phải có ít nhất một chữ cái
                'regex:/[\W_]/', // Phải có ít nhất một ký tự đặc biệt
                'confirmed', // Kiểm tra xác nhận mật khẩu
            ],
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'date_of_birth' => 'nullable|date',
            'status' => 'required|in:verified,unverified',
            'avatar' => 'nullable|image|mimes:jpeg,png,gif|max:5120',  // 5MB limit for image files
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

        // Kiểm tra nếu validate không thành công
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Xử lý upload avatar
        $avatarName = null;
        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            if ($avatarFile->isValid()) {
                $avatarName = time() . '-' . $avatarFile->getClientOriginalName();
                $avatarFile->move(public_path('storage/images/user'), $avatarName);
            }
        }
        if (!$avatarName) {
            return redirect()->back()->with('error', 'Không thể tải lên ảnh đại diện. Vui lòng thử lại.');
        }
        // Create a new user and store in database
        try {
            $user = new User();
            $user->name = $request->name;
            $user->address = $request->address;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->date_of_birth = $request->date_of_birth;
            $user->status = $request->status;
            $user->avatar = $avatarName;  // Store the image path in the database
            $user->password = bcrypt($request->password);  // Mã hóa mật khẩu trước khi lưu
            $user->save();

            // Redirect with success message
            return redirect()->route('user.index')->with('success', 'Thêm người dùng mới thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Không thể thêm người dùng. Vui lòng thử lại.');
        }
    }

}
