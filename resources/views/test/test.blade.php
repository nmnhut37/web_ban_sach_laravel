<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Trang sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs4@1.13.1/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <header>
        <div class="container-fluid">
            <div class="row align-items-center bg-white py-1">
                <nav class="navbar navbar-expand-lg navbar-light bg-white">
                    <div class="container-fluid">
                        <!-- Navbar Toggler for Mobile -->
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <!-- Collapsible Navbar Content -->
                        <div class="collapse navbar-collapse mx-5" id="navbarHeader">
                            <div class="w-100 d-flex flex-column flex-lg-row justify-content-center align-items-center gap-3">
                                <!-- Logo -->
                                <a href="{{ url('/') }}" class="navbar-brand">
                                    <img src="{{ asset('storage/images/Logo/Logo.png') }}" alt="Logo" style="width: 100px;">
                                </a>
                                <!-- Search Bar -->
                                <form action="{{ url('/') }}" method="POST" class="d-flex w-100 w-lg-40 justify-content-center">
                                    @csrf
                                    <div class="input-group" style="max-width: 500px;">
                                        <input type="text" name="tensp" class="form-control" placeholder="Tìm kiếm" aria-label="Search">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="bi bi-search"> Tìm kiếm</i>
                                        </button>
                                    </div>
                                </form>
                                <!-- User Actions -->
                                <div class="d-flex justify-content-center align-items-center">
                                    @auth
                                        <div class="dropdown">
                                            <a class="btn btn-outline-secondary dropdown-toggle d-inline-flex align-items-center" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                <img src="{{ asset('/storage/images/user/' . (Auth::user()->avatar ?? 'test.svg')) }}" alt="Avatar" class="rounded-circle me-2" style="width: 30px; height: 30px;">
                                                <span>{{ Auth::user()->name }}</span>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                                                <li>
                                                    <a class="dropdown-item" href="{{route('profile.showProfile')}}">
                                                        <i class="bi bi-person-circle me-2"></i> Hồ sơ cá nhân
                                                    </a>
                                                </li>
                                                @if(auth::user()->hasRole('admin') || auth::user()->hasRole('super_admin'))
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                                                            <i class="bi bi-house-door me-2"></i> Trang Admin
                                                        </a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <form action="{{ route('logout') }}" method="POST" class="dropdown-item m-0 p-0">
                                                        @csrf
                                                        <button class="btn btn-link text-decoration-none w-100 text-start" type="submit">
                                                            <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-primary d-inline-flex align-items-center me-3" style="white-space: nowrap;">
                                            <i class="bi bi-box-arrow-in-right me-1"></i>Đăng nhập
                                        </a>
                                        <a href="{{ route('register') }}" class="btn btn-outline-success d-inline-flex align-items-center" style="white-space: nowrap;">
                                            <i class="bi bi-box-arrow-in-right me-1"></i>Đăng ký
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #044785;">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav w-100 d-flex justify-content-evenly">
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ url('/') }}">
                                        <i class="bi bi-house fs-6 me-2"></i>Trang chủ
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-list fs-6 me-2"></i>Danh mục sản phẩm
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        @foreach ($parentCategories as $parentCategory)
                                            <li class="dropdown-submenu">
                                                <a class="dropdown-item" href="{{ route('category.show', $parentCategory->id) }}">{{ $parentCategory->name }}</a>
                                                @if ($parentCategory->children->count() > 0)
                                                    <ul class="dropdown-menu">
                                                        @foreach ($parentCategory->children as $child)
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('category.show', $child->id) }}">{{ $child->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('cart.index') }}">
                                        <span style="position: relative; display: inline-block;">
                                            <i class="bi bi-cart fs-6 me-2"></i>
                                            <span id="cart-count" class="badge bg-danger" style="position: absolute; top: -10px; right: -10px; font-size: 0.8rem;"></span>
                                        </span>
                                        Giỏ Hàng
                                    </a>
                                </li>                                                                
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
    <footer class="footer mt-5" style="background-color: #044785; color: #ffffff;">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <h5>Về chúng tôi</h5>
                    <p>Readbook Shop là cửa hàng trực tuyến chuyên cung cấp các loại sách đa dạng: sách học thuật, sách văn học, sách kỹ năng và nhiều hơn nữa. Sứ mệnh của chúng tôi là mang tri thức đến gần hơn với mọi người.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <h5>Liên hệ</h5>
                    <p>
                        Địa chỉ: 123 Đường Sách, TP. Tri Thức<br>
                        Điện thoại: (0123) 456-7890<br>
                        Email: support@readbookshop.com
                    </p>
                </div>
            </div>
            <div class="text-center mt-3">
                <p class="mb-0">© 2024 Công ty TNHH Readbook Shop. Bảo lưu mọi quyền.</p>
            </div>
        </div>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    @stack('scripts')
</body>
</html>
@if(session('warning'))
<script>
    Swal.fire({
        icon: 'warning',
        title: 'Cảnh báo',
        text: '{{ session('warning') }}',
        confirmButtonText: 'OK'
    });
</script>
@endif
@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Đã xảy ra lỗi',
            text: '{{ session('error') }}',
        });
    </script>
@endif
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{{ session('success') }}',
        });
    </script>
@endif

