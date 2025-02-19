@extends('Layout.shop_layout')

@section('content')
<div class="card mx-5 mt-2">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table table-borderless table-centered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                                <th style="width: 50px;"></th>
                            </tr>
                        </thead>
                        <tbody id="cart-items">
                            @forelse($cart as $id => $item)
                            <tr data-id="{{ $id }}">
                                <td>
                                    <img src="{{ asset('storage/images/product/' . ($item['img'] ?? 'no-image.jpg')) }}" alt="{{ $item['product_name'] }}" class="rounded mr-3" height="72">
                                    <p class="m-0 d-inline-block align-middle font-16">
                                        <a href="{{ route('product.show', ['id' => $id]) }}" class="text-body">{{ $item['product_name'] }}</a>
                                    </p>
                                </td>
                                <td>
                                    {{ number_format($item['price'], 0, '.', ',') }} đ
                                </td>
                                <td>
                                    <input type="number" min="1" max="{{ $item['stock_quantity'] }}" value="{{ $item['quantity'] }}" class="form-control quantity-input update-quantity" data-id="{{ $id }}" placeholder="Số lượng" style="width: 90px;">
                                </td>
                                <td>
                                    <span class="item-total">{{ number_format($item['price'] * $item['quantity'], 0, '.', ',') }} đ</span>
                                </td>
                                <td>
                                    <a href="javascript:void(0);" class="action-icon remove-from-cart" data-id="{{ $id }}">
                                        <i class="bi bi-trash3-fill"></i>
                                    </a>
                                </td>                                
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Giỏ hàng trống!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- action buttons-->
                <div class="row mt-4">
                    <div class="col-sm-6">
                        <a href="{{route('index')}}" class="btn text-muted d-none d-sm-inline-block btn-link font-weight-semibold">
                            <i class="mdi mdi-arrow-left"></i> Tiếp tục mua sắm </a>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-right">
                            <a href="/checkout" class="btn btn-danger">
                                <i class="mdi mdi-cart-plus mr-1"></i> Thanh toán </a>
                        </div>
                    </div>                    
                </div>
            </div>
            <!-- Tóm tắt đơn hàng và mã giảm giá -->
            <div class="col-lg-4">
                <div class="border p-3 mt-4 mt-lg-0 rounded">
                    <h4 class="header-title mb-3">Tóm tắt đơn hàng</h4>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td>Tổng giá trị:</td>
                                    <td id="grand-total">{{$cart_total}}</td>
                                </tr>
                                <tr id="discount-row" class="d-none">
                                    <td>Giảm giá:</td>
                                    <td id="discount-amount">$0</td>
                                </tr>
                                <tr>
                                    <th>Tổng cộng:</th>
                                    <th id="final-total">{{$cart_total}}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Phần nhập mã giảm giá -->
                    <div class="mb-3 mt-4">
                        <label for="coupon-code" class="form-label">Nhập mã giảm giá:</label>
                        <div class="input-group">
                            <input type="text" id="coupon-code" class="form-control" placeholder="Nhập mã">
                            <button id="apply-coupon" class="btn btn-primary">Áp dụng</button>
                        </div>
                        <small class="text-danger d-none" id="coupon-error"></small>
                        <small class="text-success d-none" id="coupon-success"></small>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
</div>
@endsection
<script>
    console.log('jQuery version:', typeof jQuery !== 'undefined' ? jQuery.fn.jquery : 'not loaded');
    </script>
@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
@endpush
