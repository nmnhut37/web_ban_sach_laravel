@extends('Layout.shop_layout')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Phần 1: Giỏ hàng -->
        <div class="col-lg-8">
            <div class="table-responsive">
                <table class="table table-borderless table-centered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tổng cộng</th>
                            <th style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/images/product/' . $item->product->img) }}" alt="{{ $item->product->product_name }}" class="rounded mr-3" height="64">
                                <p class="m-0 d-inline-block align-middle font-16">
                                    <a href="{{ route('product.detail', $item->product->id) }}" class="text-body">{{ $item->product->product_name }}</a>
                                </p>
                            </td>
                            <td>
                                {{ number_format($item->product->price, 0, ',', '.') }} đ
                            </td>
                            <td>
                                <input type="number" min="1" value="{{ $item->quantity }}" class="form-control" placeholder="Qty" style="width: 90px;">
                            </td>
                            <td>
                                {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }} đ
                            </td>
                            <td>
                                <a href="{{ route('cart.remove', $item->id) }}" class="action-icon">
                                    <i class="mdi mdi-delete"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Thêm ghi chú -->
            <div class="mt-3">
                <label for="example-textarea">Thêm ghi chú:</label>
                <textarea class="form-control" id="example-textarea" rows="3" placeholder="Viết ghi chú cho đơn hàng..."></textarea>
            </div>

            <!-- Nút hành động -->
            <div class="row mt-4">
                <div class="col-sm-6">
                    <a href="{{ route('shop.index') }}" class="btn text-muted d-none d-sm-inline-block btn-link font-weight-semibold">
                        <i class="mdi mdi-arrow-left"></i> Tiếp tục mua sắm
                    </a>
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-right">
                        <a href="{{ route('checkout.index') }}" class="btn btn-danger">
                            <i class="mdi mdi-cart-plus mr-1"></i> Thanh toán
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phần 2: Tóm tắt đơn hàng -->
        <div class="col-lg-4">
            <div class="border p-3 mt-4 mt-lg-0 rounded">
                <h4 class="header-title mb-3">Tóm tắt đơn hàng</h4>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <td>Tổng cộng:</td>
                                <td>{{ number_format($totalPrice, 0, ',', '.') }} đ</td>
                            </tr>
                            <tr>
                                <td>Giảm giá:</td>
                                <td>-{{ number_format($discount, 0, ',', '.') }} đ</td>
                            </tr>
                            <tr>
                                <td>Phí vận chuyển:</td>
                                <td>{{ number_format($shippingCharge, 0, ',', '.') }} đ</td>
                            </tr>
                            <tr>
                                <td>Thuế ước tính:</td>
                                <td>{{ number_format($estimatedTax, 0, ',', '.') }} đ</td>
                            </tr>
                            <tr>
                                <th>Tổng cộng cuối:</th>
                                <th>{{ number_format($finalTotal, 0, ',', '.') }} đ</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="alert alert-warning mt-3" role="alert">
                Sử dụng mã giảm giá <strong>HYPBM</strong> để được giảm 10%!
            </div>

            <div class="input-group mt-3">
                <input type="text" class="form-control form-control-light" placeholder="Mã giảm giá" aria-label="Recipient's username">
                <div class="input-group-append">
                    <button class="btn btn-light" type="button">Áp dụng</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
