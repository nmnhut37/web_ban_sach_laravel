@extends('layout.login_layout')

@section('content')
<!-- Outer Row -->
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-password-image">
                        <a href="{{ route('index') }}">
                            <img src="{{ asset('/storage/images/logo/Logo.png') }}" alt="Logo" class="img-fluid" style="max-width: 80%; margin: 50px auto; display: block;">
                        </a>
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-2">Đặt lại mật khẩu</h1>
                                <p class="mb-4">Vui lòng nhập mật khẩu mới của bạn và xác nhận lại!</p>
                            </div>
                            <form method="POST" action="{{ route('password.update') }}" class="user">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" name="password" placeholder="Nhập mật khẩu mới" required>
                                    @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" name="password_confirmation" placeholder="Xác nhận mật khẩu mới" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Đặt lại mật khẩu
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="{{ route('login') }}">Đăng nhập ngay!</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="{{ route('register') }}">Tạo tài khoản mới!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection