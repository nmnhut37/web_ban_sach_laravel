@extends('layout.shop_layout')

@section('content')
<div class="container-xl px-4 mt-4">
    <!-- Account page navigation-->
    <ul class="nav nav-tabs" id="accountTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                Thông tin tài khoản
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab" aria-controls="orders" aria-selected="false">
                Đơn hàng
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">
                Bảo mật
            </button>
        </li>
    </ul>
    <div class="tab-content" id="accountTabContent">
        <!-- Thông tin tài khoản -->
        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="card mb-4 mt-3">
                <div class="card-header">Thông tin tài khoản</div>
                <div class="card-body">
                    <!-- Profile picture card-->
                    <div class="container-xl px-4 mt-4">
                        <h4 class="text-center mb-4">Ảnh đại diện</h4>
                        <div class="text-center">
                            <!-- Hiển thị ảnh đại diện -->
                            <img id="avatar_preview" class="img-account-profile rounded-circle mb-2" 
                                src="{{ asset('/storage/images/user/' . (Auth::user()->avatar ?? 'test.svg')) }}" 
                                alt="Avatar" width="200" height="200">
                            <br>
                            <!-- Trạng thái hình ảnh -->
                            <span id="image_status" class="mb-2 d-block">
                                {{ Auth::user()->avatar ? 'Ảnh hiện tại' : 'Chưa có hình' }}
                            </span>
                            <div class="small font-italic text-muted mb-4">JPG, PNG, hoặc GIF không quá 5 MB</div>
                            
                            <!-- Form upload ảnh -->
                            <form id="avatar_form" action="{{ route('profile.updateAvatar') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="avatar" id="avatar_input" class="form-control-file" 
                                    style="display:none;" accept="image/jpeg, image/png, image/gif">
                                
                                <!-- Nút chọn ảnh mới -->
                                <button id="select_button" type="button" class="btn btn-primary">
                                    Chọn ảnh mới
                                </button>
                                
                                <!-- Nút lưu và hủy (ẩn ban đầu) -->
                                <div id="action_buttons" style="display:none;">
                                    <button type="submit" class="btn btn-success">Lưu ảnh</button>
                                    <button type="button" class="btn btn-secondary ms-2" id="cancel_button">Hủy</button>
                                </div>
                            </form>
                    
                            <!-- Thông báo lỗi -->
                            <div id="file_error" class="text-danger mt-2" style="display: none;">
                                Chỉ chấp nhận file ảnh JPG, PNG, hoặc GIF và không quá 5 MB.
                            </div>
                        </div>
                    </div>                                  
                    <form action="{{ route('profile.updateProfile') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="small mb-1" for="inputName">Họ và tên</label>
                            <input class="form-control" id="inputName" type="text" name="name" value="{{ Auth::user()->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="inputPhone">Số điện thoại</label>
                            <input class="form-control" id="inputPhone" type="tel" name="phone" value="{{ Auth::user()->phone }}">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="inputAddress">Địa chỉ</label>
                            <input class="form-control" id="inputAddress" type="text" name="address" value="{{ Auth::user()->address }}">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="inputDateOfBirth">Ngày sinh</label>
                            <input class="form-control" id="inputDateOfBirth" type="date" name="date_of_birth" value="{{ Auth::user()->date_of_birth }}">
                        </div>
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary" type="submit">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Đơn hàng -->
        <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
            <div class="card mb-4 mt-3">
                <div class="card-header">Danh sách đơn hàng</div>
                <div class="card-body">
                    @include('shop.profile.order_history')
                </div>
            </div>
        </div>

        <!-- Bảo mật -->
        <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
            @include('shop.profile.security_form')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Kích hoạt tab Bootstrap
    const triggerTabList = document.querySelectorAll('#accountTab button');
    triggerTabList.forEach(function (triggerEl) {
        const tabTrigger = new bootstrap.Tab(triggerEl);
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });

    // Xử lý avatar
    document.addEventListener('DOMContentLoaded', function() {
        const avatarInput = document.getElementById('avatar_input');
        const selectButton = document.getElementById('select_button');
        const actionButtons = document.getElementById('action_buttons');
        const cancelButton = document.getElementById('cancel_button');
        const avatarPreview = document.getElementById('avatar_preview');
        const imageStatus = document.getElementById('image_status');
        const fileError = document.getElementById('file_error');
        const avatarForm = document.getElementById('avatar_form');
        
        // Lưu URL ảnh hiện tại
        const currentAvatarUrl = avatarPreview.src;

        // Sự kiện click nút chọn ảnh
        selectButton.addEventListener('click', function() {
            avatarInput.click();
        });

        // Xử lý khi chọn file
        avatarInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const validTypes = ['image/jpeg', 'image/png', 'image/gif'];

                // Kiểm tra định dạng file
                if (!validTypes.includes(file.type)) {
                    fileError.style.display = 'block';
                    return;
                }

                // Kiểm tra kích thước file (tối đa 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    fileError.style.display = 'block';
                    return;
                }

                fileError.style.display = 'none';

                // Hiển thị ảnh preview
                const reader = new FileReader();
                reader.onload = function() {
                    avatarPreview.src = reader.result;
                    imageStatus.textContent = 'Ảnh đã được chọn';
                };
                reader.readAsDataURL(file);

                // Hiển thị nút lưu và hủy, ẩn nút chọn ảnh
                selectButton.style.display = 'none';
                actionButtons.style.display = 'block';
            }
        });

        // Xử lý khi nhấn nút hủy
        cancelButton.addEventListener('click', function() {
            // Khôi phục ảnh ban đầu
            avatarPreview.src = currentAvatarUrl;
            imageStatus.textContent = 'Ảnh hiện tại';
            
            // Reset form
            avatarForm.reset();
            fileError.style.display = 'none';
            
            // Ẩn nút lưu và hủy, hiện nút chọn ảnh
            actionButtons.style.display = 'none';
            selectButton.style.display = 'inline-block';
        });
    });
</script>
@endpush