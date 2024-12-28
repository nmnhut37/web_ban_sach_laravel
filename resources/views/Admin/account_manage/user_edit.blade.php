@extends('Layout.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Chỉnh sửa thông tin người dùng</h1>
        <a href="{{ route('user.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fa fa-arrow-left"></i>
            </span>
            <span class="text">Quay lại danh sách tài khoản</span>
        </a>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card shadow mb-4 mb-xl-0">
                    <div class="card-header">Ảnh đại diện</div>
                    <div class="card-body text-center">
                        <!-- Hiển thị ảnh nếu có -->
                        <img id="avatar_preview" class="img-account-profile rounded-circle mb-2" 
                             src="{{ $user->avatar ? asset('storage/images/user/' . $user->avatar) : asset('storage/images/user/profile.png') }}" 
                             alt="demo" width="200" height="200"><br>
                        <span id="image_status">{{ $user->avatar ? 'Ảnh đã được chọn' : 'Chưa có hình' }}</span>
                        <div class="small font-italic text-muted mb-4">JPG, PNG, hoặc GIF không quá 5 MB</div>
                        <!-- Input file để chọn ảnh với giới hạn file ảnh và kích thước -->
                        <input type="file" name="avatar" id="avatar_input" class="form-control-file" style="display:none;" accept="image/jpeg, image/png, image/gif" onchange="previewImage(event)">
                        <!-- Nút tải ảnh lên -->
                        <button class="btn btn-primary" type="button" onclick="document.getElementById('avatar_input').click();">Chọn ảnh mới</button>
                        <div id="file_error" class="text-danger mt-2" style="display: none;">Chỉ chấp nhận file ảnh JPG, PNG, hoặc GIF và không quá 5 MB.</div>
                    </div>
                </div>
            </div>
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">Chỉnh sửa thông tin người dùng</div>
                <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1">Tên người dùng</label>
                            <input type="text" name="name" class="form-control" placeholder="Nhập họ và tên" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">Địa chỉ</label>
                            <input type="text" name="address" class="form-control" placeholder="Nhập địa chỉ" value="{{ old('address', $user->address) }}">
                            @error('address')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">Số điện thoại</label>
                            <input type="tel" name="phone" class="form-control" placeholder="Nhập số điện thoại" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Nhập địa chỉ email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row gx-3 mb-3">
                            <div class="col-md-4">
                                <label class="small mb-1">Ngày sinh</label>
                                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $user->date_of_birth) }}">
                                @error('date_of_birth')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="small mb-1">Trạng thái</label>
                                <select name="status" class="form-control" required>
                                    <option value="verified" {{ old('status', $user->status) == 'verified' ? 'selected' : '' }}>Đã xác thực</option>
                                    <option value="unverified" {{ old('status', $user->status) == 'unverified' ? 'selected' : '' }}>Chưa xác thực</option>
                                </select>
                                @error('status')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="small mb-1">Role</label>
                                <select name="role" class="form-control" required>
                                    <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super admin</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                                @error('role')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>                        
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1">Mật khẩu</label>
                                <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu">
                                @error('password')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1">Xác nhận mật khẩu</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Xác nhận mật khẩu">
                                @error('password_confirmation')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Cập nhật thông tin người dùng</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')


<script>
    // Hàm preview hình ảnh khi người dùng chọn ảnh
    function previewImage(event) {
        const file = event.target.files[0];
        const fileError = document.getElementById('file_error');
        const reader = new FileReader();
        const imageStatus = document.getElementById('image_status');

        // Kiểm tra loại file
        if (file) {
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                fileError.style.display = 'block';
                document.getElementById('avatar_preview').src = '{{ asset('storage/images/user/profile.png') }}';
                imageStatus.textContent = 'Chưa có hình';
                return;
            }

            // Kiểm tra kích thước file (tối đa 5MB)
            if (file.size > 5 * 1024 * 1024) {
                fileError.style.display = 'block';
                document.getElementById('avatar_preview').src = '{{ asset('storage/images/user/profile.png') }}';
                imageStatus.textContent = 'Chưa có hình';
                return;
            }

            fileError.style.display = 'none';
            reader.onload = function() {
                const preview = document.getElementById('avatar_preview');
                preview.src = reader.result;
                imageStatus.textContent = 'Ảnh đã được chọn';
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
