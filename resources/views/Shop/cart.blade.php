@extends('Layout.shop_layout')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="row mx-3">
            <!-- Phần 1: Giỏ hàng -->
            <div class="col-lg-8">
                @if(count($cartItems) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="bg-light">
                                    <th class="py-3">Sản phẩm</th>
                                    <th class="py-3">Giá</th>
                                    <th class="py-3">Số lượng</th>
                                    <th class="py-3">Tổng cộng</th>
                                    <th class="py-3" style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $productId => $item)
                                <tr class="align-middle cart-item" data-product-id="{{ $item['id'] }}">
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('storage/images/product/' . $item['img']) }}" 
                                                    alt="{{ $item['product_name'] }}" 
                                                    class="rounded-3 shadow-sm"
                                                    style="width: 80px; height: 80px; object-fit: cover;">
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h5 class="mb-1">
                                                    <a href="{{ route('product.show', $item['id']) }}" 
                                                        class="text-dark text-decoration-none">
                                                        {{ $item['product_name'] }}
                                                    </a>
                                                </h5>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="fw-medium">{{ number_format($item['price'], 0, ',', '.') }} đ</span>
                                    </td>
                                    <td class="py-3">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button class="btn btn-outline-secondary" type="button" 
                                                onclick="updateQuantity(this, -1, {{ $item['id'] }})">-</button>
                                            <input type="text" 
                                                min="1" 
                                                value="{{ $item['quantity'] }}" 
                                                class="form-control text-center border-secondary" 
                                                onchange="updateCart({{ $item['id'] }}, this.value)">
                                            <button class="btn btn-outline-secondary" type="button" 
                                                onclick="updateQuantity(this, 1, {{ $item['id'] }})">+</button>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="fw-bold text-primary item-total" data-product-id="{{ $item['id'] }}">
                                            {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} đ
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <button onclick="removeFromCart({{ $item['id'] }})" 
                                            class="btn btn-link text-dark p-0 border-0">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </td>
                                </tr>                                
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Nút hành động -->
                    <div class="row mt-4">
                        <div class="col-sm-6">
                            <a href="{{ url()->previous() ?: route('index') }}" 
                                class="btn btn-outline-secondary">
                                <i class="mdi mdi-arrow-left me-1"></i> Tiếp tục mua sắm
                            </a>
                            <button class="btn btn-danger" onclick="clearCart()">
                                <i class="mdi mdi-delete-sweep me-1"></i> Xóa hết sản phẩm
                            </button>
                        </div>
                        <div class="col-sm-6 text-end">
                            <button class="btn btn-primary" onclick="placeOrder()">
                                <i class="mdi mdi-cart-plus me-1"></i> Đặt hàng
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Thông báo giỏ hàng trống -->
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="mdi mdi-cart-outline text-muted" style="font-size: 48px;"></i>
                        </div>
                        <h4 class="text-muted mb-3">Giỏ hàng trống</h4>
                        <a href="{{ route('index') }}" class="btn btn-primary">
                            <i class="mdi mdi-shopping me-1"></i> Tiếp tục mua sắm
                        </a>
                    </div>
                @endif

                <!-- Ghi chú -->
                <div class="mt-4">
                    <label for="example-textarea" class="form-label">Thêm ghi chú:</label>
                    <textarea class="form-control" id="example-textarea" rows="3" 
                        placeholder="Viết ghi chú của bạn tại đây..."></textarea>
                </div>
            </div>

            <!-- Phần 2: Tóm tắt đơn hàng -->
            <div class="col-lg-4">
                <div class="card border shadow-sm mt-4 mt-lg-0">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Tóm tắt đơn hàng</h5>
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="text-muted">Tổng cộng:</td>
                                        <td class="text-end fw-bold fs-5" id="total-price">
                                            {{ count($cartItems) > 0 ? number_format($totalPrice, 0, ',', '.') : '0' }} đ
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mã giảm giá -->
                        <div class="alert alert-warning mt-3 mb-3" role="alert">
                            <i class="mdi mdi-tag-outline me-1"></i>
                            Sử dụng mã <strong>HYPBM</strong> và nhận giảm giá 10%!
                        </div>

                        <div class="input-group">
                            <input type="text" class="form-control" 
                                placeholder="Nhập mã giảm giá" aria-label="Coupon code">
                            <button class="btn btn-secondary" type="button">Áp dụng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="{{asset('js/cart.js')}}"></script>
@endpush
