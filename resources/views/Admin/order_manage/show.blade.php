@extends('layout.master')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800">Chi tiết đơn hàng</h1>
    <div>
        <a href="{{route('orders.index')}}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
            <i class="fa fa-arrow-left"></i>
        </span>
        <span class="text">Quay lại danh sách</span>
    </a>
    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-success btn-icon-split">
        <span class="icon text-white-50">
            <i class="bi bi-pencil-square"></i>
        </span>
        <span class="text">Sửa đơn hàng</span>
    </a>
    </div>
</div>

<div class="content-page">
    <div class="content">
        <div class="row mt-2">
            <!-- Shipping Information -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Thông tin giao hàng</h4>
                        <ul class="list-unstyled mb-0">
                            <li>
                                <p class="mb-2"><span class="font-weight-bold mr-2">Họ và tên khách hàng:</span> {{ $order->name }}</p>
                                <p class="mb-2"><span class="font-weight-bold mr-2">Địa chỉ giao hàng:</span> {{ $order->address }}</p>
                                <p class="mb-2"><span class="font-weight-bold mr-2">Số điện thoại:</span> {{ $order->phone }}</p>
                                <p class="mb-2"><span class="font-weight-bold mr-2">Email:</span> {{ $order->email }}</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div> <!-- end col -->

            <!-- Billing Information -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Thông tin thanh toán</h4>
                        <ul class="list-unstyled mb-0">
                            <li>
                                <p class="mb-2"><span class="font-weight-bold mr-2">Phương thức thanh toán:</span> {{ $order->payment_method }}</p>
                                <p class="mb-2"><span class="font-weight-bold mr-2">Tổng tiền:</span> {{$order->final_amount}} đ</p>
                                <p class="mb-2"><span class="font-weight-bold mr-2">Trạng thái đơn hàng:</span> {{$order->order_status}} </p>
                                <p class="mb-2"><span class="font-weight-bold mr-2">Ghi chú:</span> {{$order->note}} </p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
        <!-- end row -->

        <!-- Order Items -->
        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Sản phẩm trong đơn hàng #{{ $order->id }}</h4>

                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                <tr>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Tổng cộng</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->orderItems as $orderItem)
                                <tr>
                                    <td>{{ $orderItem->product->product_name }}</td>
                                    <td>{{ $orderItem->quantity }}</td>
                                    <td>{{ $orderItem->price }} đ</td>
                                    <td>{{ $orderItem->quantity * $orderItem->price }} đ</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                    </div>
                </div>
            </div> <!-- end col -->

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Tóm tắt đơn hàng</h4>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                <tr>
                                    <th>Mô tả</th>
                                    <th>Giá</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Tổng cộng:</td>
                                    <td>{{ $order->total_amount }} đ</td>
                                </tr>
                                <tr>
                                    <td>Giảm giá:</td>
                                    <td>{{ $order->discount_amount }} đ</td>
                                </tr>
                                <tr>
                                    <th>Thành tiền:</th>
                                    <th>{{ $order->final_amount }} đ</th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div> <!-- End Content -->
</div>

@endsection
