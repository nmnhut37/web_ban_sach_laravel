@extends('layout.shop_layout')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="mb-3">Đặt hàng thành công!</h2>
                    <p class="text-muted mb-4">
                        Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất.
                        @if(session('success'))
                            <br>{{ session('success') }}
                        @endif
                    </p>

                    <div class="order-details bg-light p-4 rounded mb-4">
                        <h5 class="mb-3">Thông tin đơn hàng</h5>
                        @if(isset($order))
                        <div class="row">
                            <div class="col-sm-6 text-start">
                                <p class="mb-1"><strong>Mã đơn hàng:</strong></p>
                                <p class="text-muted">{{ $order->order_number }}</p>
                            </div>
                            <div class="col-sm-6 text-start">
                                <p class="mb-1"><strong>Tổng tiền:</strong></p>
                                <p class="text-muted">{{ number_format($order->final_amount) }} VND</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 text-start">
                                <p class="mb-1"><strong>Phương thức thanh toán:</strong></p>
                                <p class="text-muted">{{ $order->payment_method }}</p>
                            </div>
                            <div class="col-sm-6 text-start">
                                <p class="mb-1"><strong>Trạng thái:</strong></p>
                                <p class="text-muted">
                                @if($order->order_status == 'pending')
                                    Đang xử lý
                                @else
                                    {{ $order->order_status }}
                                @endif
                            </div>
                        </div>

                        <!-- Hiển thị chi tiết các sản phẩm trong đơn hàng -->
                        <div class="mt-4">
                            <h5 class="mb-3">Chi tiết sản phẩm</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tên sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Giá</th>
                                        <th>Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->product_name ?? 'Không xác định' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->price) }} VND</td>
                                        <td>{{ number_format($item->quantity * $item->price) }} VND</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>

                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        <a href="{{ route('index') }}" class="btn btn-outline-secondary px-4 me-sm-3">
                            <i class="fas fa-home me-2"></i>Trang chủ
                        </a>
                        <a href="{{ route('checkout.show',$order->id)}}" class="btn btn-primary px-4">
                            <i class="fas fa-list me-2"></i>Xem đơn hàng
                        </a>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
