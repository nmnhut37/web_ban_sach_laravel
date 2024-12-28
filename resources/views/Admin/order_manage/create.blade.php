@extends('Layout.master')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800">Thêm đơn hàng mới</h1>
    <a href="{{route('orders.index')}}" class="btn btn-secondary btn-icon-split">
        <span class="icon text-white-50">
            <i class="fa fa-arrow-left"></i>
        </span>
        <span class="text">Quay lại danh sách</span>
    </a>
</div>

<form id="orderForm" action="{{ route('orders.store') }}" method="POST">
    @csrf
    <div class="content-page">
        <div class="content">
            <div class="row mt-2">
                <!-- Shipping Information -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-3">Thông tin giao hàng</h4>
                            <div class="form-group">
                                <label for="name">Họ và tên khách hàng</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Địa chỉ giao hàng</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Status and Payment -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-3">Thông tin thanh toán</h4>
                            <div class="form-group">
                                <label for="payment_method">Phương thức thanh toán</label>
                                <select class="form-control" id="payment_method" name="payment_method" required>
                                    <option value="cod">COD</option>
                                    <option value="vnpay">VNPay</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="order_status">Trạng thái đơn hàng</label>
                                <select class="form-control" id="order_status" name="order_status" required>
                                    <option value="pending">Chờ xử lý</option>
                                    <option value="processing">Đang xử lý</option>
                                    <option value="shipping">Đang giao hàng</option>
                                    <option value="completed">Hoàn thành</option>
                                    <option value="cancelled">Đã hủy</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="note">Ghi chú</label>
                                <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        <button type="submit" form="orderForm" class="btn btn-success btn-icon-split">
            <span class="icon text-white-50">
                <i class="bi bi-floppy-fill"></i>
            </span>
            <span class="text">Lưu</span>
        </button>
    </div>
</form>

@endsection
