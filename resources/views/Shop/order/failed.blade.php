@extends('layout.shop_layout')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-times-circle text-danger" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="mb-3">Đặt hàng không thành công!</h2>
                    <p class="text-muted mb-4">
                        Rất tiếc, đã có lỗi xảy ra trong quá trình xử lý đơn hàng của bạn.
                        @if(session('error'))
                            <br>{{ session('error') }}
                        @endif
                    </p>

                    @if(isset($order))
                    <div class="order-details bg-light p-4 rounded mb-4">
                        <h5 class="mb-3">Thông tin đơn hàng</h5>
                        <div class="row">
                            <div class="col-sm-6 text-start">
                                <p class="mb-1"><strong>Mã đơn hàng:</strong></p>
                                <p class="text-muted">{{ $order->order_number }}</p>
                            </div>
                            <div class="col-sm-6 text-start">
                                <p class="mb-1"><strong>Trạng thái:</strong></p>
                                <p class="text-danger">{{ $order->order_status}}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        Nếu bạn đã bị trừ tiền, vui lòng liên hệ với chúng tôi qua hotline: <strong>1900 xxxx</strong>
                    </div>

                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary px-4 me-sm-3">
                            <i class="fas fa-shopping-cart me-2"></i>Giỏ hàng
                        </a>
                        <a href="{{ route('checkout') }}" class="btn btn-primary px-4">
                            <i class="fas fa-redo me-2"></i>Thử lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection