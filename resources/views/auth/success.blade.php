@extends('Layout.login_layout')

@section('content')
<!-- Outer Row -->
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-password-image">
                        <a href="{{ route('index') }}">
                            <img src="{{ asset('/storage/images/Logo/Logo.png') }}" alt="Logo" class="img-fluid" style="max-width: 80%; margin: 50px auto; display: block;">
                        </a>
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-2">Đã gửi email kích hoạt tài khoản</h1>
                                <p class="mb-4">Chúng tôi đã gửi một email xác nhận đến địa chỉ của bạn. Vui lòng kiểm tra email và kích hoạt tài khoản để bắt đầu sử dụng.</p>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('login') }}" class="small">Quay lại trang đăng nhập</a>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('register') }}" class="small">Tạo tài khoản mới!</a>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('index') }}" class="small">Về trang chủ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
