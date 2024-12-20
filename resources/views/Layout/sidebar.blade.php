        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('dashboard')}}">
                <div class="sidebar-brand-text mx-3">Readbook shop</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{route('dashboard')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tổng quan</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseone"
                    aria-expanded="true" aria-controls="collapseone">
                    <i class="bi bi-book fs-6"></i>
                    <span>Quản lý sản phẩm</span>
                </a>
                <div id="collapseone" class="collapse" aria-labelledby="headingone" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{route('product_list')}}">Sản phẩm</a>
                        <a class="collapse-item" href="{{route('categories.index')}}">Danh mục</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsetwo"
                    aria-expanded="true" aria-controls="collapsetwo">
                    <i class="bi bi-person fs-6"></i>
                    <span>Quản lý thành viên</span>
                </a>
                <div id="collapsetwo" class="collapse" aria-labelledby="headingtwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{route('user.index')}}">Thành viên</a>
                        <a class="collapse-item" href="quanlybinhluan.php">Đánh giá sản phẩm</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsetree"
                    aria-expanded="true" aria-controls="collapsetree">
                    <i class="bi bi-shop fs-6"></i>
                    <span>Quản lý cửa hàng</span>
                </a>
                <div id="collapsetree" class="collapse" aria-labelledby="headingtree" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{route('banners.index')}}">Banner</a>
                        <a class="collapse-item" href="quanlybinhluan.php">Mã giảm giá</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="quanlydonhang.php">
                    <i class="bi bi-credit-card fs-6"></i>
                    <span>Quản lý đơn hàng</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->