@extends('layout.shop_layout')

@section('content')
<form id="order-form" action="{{ route('order.store') }}" method="POST">
    @csrf
    <div class="card mx-5 mt-2">
        <div class="card-body">

            <!-- Checkout Steps -->
            <ul class="nav nav-pills bg-nav-pills nav-justified mb-3" id="checkoutTabs">
                <li class="nav-item">
                    <a href="#billing-information" data-bs-toggle="pill" class="nav-link rounded-0 active">
                        <i class="mdi mdi-account-circle font-18"></i>
                        <span class="d-none d-lg-block">Thông tin hóa đơn</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#payment-information" data-bs-toggle="pill" class="nav-link rounded-0" id="tab-payment">
                        <i class="mdi mdi-cash-multiple font-18"></i>
                        <span class="d-none d-lg-block">Thanh toán</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane show active" id="billing-information">
                    @include('shop.order.order')
                </div>
                <div class="tab-pane" id="payment-information">
                    @include('shop.order.payment')
                </div>
            </div>
        </div>
    </div>
</form>
@endsection