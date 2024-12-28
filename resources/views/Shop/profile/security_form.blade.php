<div class="card mb-4 mt-3">
    <div class="card-header">Bảo mật</div>
    <div class="card-body">
        <form action="{{ route('profile.updatePassword') }}" method="POST">
            @csrf
            <!-- Form Group (current password)-->
            <div class="mb-3">
                <label class="small mb-1" for="currentPassword">Mật khẩu hiện tại</label>
                <input class="form-control" id="currentPassword" type="password" name="current_password" placeholder="Nhập mật khẩu hiện tại" required>
            </div>
            <!-- Form Group (new password)-->
            <div class="mb-3">
                <label class="small mb-1" for="newPassword">Mật khẩu mới</label>
                <input class="form-control" id="newPassword" type="password" name="new_password" placeholder="Nhập mật khẩu mới" required>
            </div>
            <!-- Form Group (confirm new password)-->
            <div class="mb-3">
                <label class="small mb-1" for="confirmPassword">Xác nhận mật khẩu mới</label>
                <input class="form-control" id="confirmPassword" type="password" name="new_password_confirmation" placeholder="Xác nhận mật khẩu mới" required>
            </div>
            <!-- Save changes button-->
            <button class="btn btn-primary" type="submit">Đổi mật khẩu</button>
        </form>
    </div>
</div>