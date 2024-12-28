@extends('layout.shop_layout')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Chi tiết đơn hàng #{{ $order->order_number }}</h2>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5>Thông tin khách hàng</h5>
        </div>
        <div class="card-body">
            <p><strong>Họ tên:</strong> {{ $order->name }}</p>
            <p><strong>Email:</strong> {{ $order->email }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
            <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
            <p><strong>Tổng tiền:</strong> {{ number_format($order->final_amount, 0, '.', ',') }} đ</p>
            <p>
                <strong>Trạng thái đơn hàng:</strong>
                <span class="badge 
                    @if($order->order_status == 'pending') text-bg-secondary
                    @elseif($order->order_status == 'processing') text-bg-primary
                    @elseif($order->order_status == 'completed') text-bg-success
                    @elseif($order->order_status == 'cancelled') text-bg-danger
                    @endif">
                    {{ ucfirst($order->order_status) }}
                </span>
            </p>
        </div>
    </div>

    <h3 class="mb-4">Chi tiết sản phẩm</h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 0, '.', ',') }} đ</td>
                        <td>{{ number_format($item->price * $item->quantity, 0, '.', ',') }} đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
