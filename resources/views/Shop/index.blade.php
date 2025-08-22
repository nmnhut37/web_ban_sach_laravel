@extends('layout.shop_layout')

@section('content')
<div class="container my-4">
    <!-- Carousel -->
    @include('layout.carousel', ['banners' => $banners])

    <!-- Sách mới -->
    <h2 class="my-4">Sách mới</h2>
    <div class="row">
        @foreach ($products as $product)
            <div class="col-6 col-sm-4 col-md-3 custom-5-column mb-4">
                <div class="card h-100 product-card" data-product-id="{{ $product->id }}" data-stock-quantity="{{ $product->stock_quantity }}">
                    <a href="{{ route('product.show', $product->id) }}">
                        <img src="{{ asset('storage/images/product/' . ($product->img ?? 'no-image.jpg')) }}" class="card-img-top" alt="{{ $product->product_name }}">
                    </a>
                    <div class="card-body">
                        <a href="{{ route('product.show', $product->id) }}" class="text-decoration-none">
                            <h5 class="card-title" style="font-size: 1rem;">{{ $product->product_name }}</h5>
                        </a>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-danger fw-bold" style="font-size: 1rem;">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                        </div>
                        <x-star-rating :productId="$product->id" />
                    </div>
                    <div class="card-footer text-center" style="background-color: #fff; border-top: 0px solid #ddd;">
                        <div class="d-flex align-items-center justify-content-center quantity-wrapper">
                            <button class="btn btn-outline-secondary decrease" type="button">-</button>
                            <input 
                                type="text" 
                                class="form-control mx-1 quantity-input" 
                                value="1" 
                                style="width: 60px; text-align: center;">
                            <button class="btn btn-outline-secondary increase" type="button">+</button>
                        </div>
                        <a href="javascript:void(0)" class="btn btn-primary add-to-cart mt-2">Thêm vào giỏ</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Sách bán chạy -->
    <h2 class="mb-4">Sách bán chạy</h2>
    <div class="row">
        @foreach ($bestSellingProducts as $product)
        <div class="col-6 col-sm-4 col-md-3 custom-5-column mb-4">
            <div class="card h-100 product-card" data-product-id="{{ $product->id }}" data-stock-quantity="{{ $product->stock_quantity }}">
                <a href="{{ route('product.show', $product->id) }}">
                    <img src="{{ asset('storage/images/product/' . ($product->img ?? 'no-image.jpg')) }}" class="card-img-top" alt="{{ $product->product_name }}">
                </a>
                <div class="card-body">
                    <a href="{{ route('product.show', $product->id) }}" class="text-decoration-none">
                        <h5 class="card-title" style="font-size: 1rem;">{{ $product->product_name }}</h5>
                    </a>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-danger fw-bold" style="font-size: 1rem;">{{ number_format($product->price, 0, '.', ',') }} đ</span>
                    </div>
                    <x-star-rating :productId="$product->id" />
                </div>
                <div class="card-footer text-center" style="background-color: #fff; border-top: 0px solid #ddd;">
                    <div class="d-flex align-items-center justify-content-center quantity-wrapper">
                        <button class="btn btn-outline-secondary decrease" type="button">-</button>
                        <input 
                            type="text" 
                            class="form-control mx-1 quantity-input " 
                            value="1" 
                            style="width: 60px; text-align: center;">
                        <button class="btn btn-outline-secondary increase" type="button">+</button>
                    </div>
                    <a href="javascript:void(0)" class="btn btn-primary add-to-cart mt-2">Thêm vào giỏ</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection