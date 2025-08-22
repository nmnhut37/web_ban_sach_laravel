@extends('layout.shop_layout')

@section('content')
<div class="container my-4">
    <!-- Carousel -->
    @include('layout.carousel', ['banners' => $banners])
    
    <h2 class="my-4">Danh mục: {{ $category->name }}</h2>
    <div class="row">
        @if ($products->isEmpty())
            <p class="text-muted">Không có sản phẩm nào trong danh mục này.</p>
        @else
            @foreach ($products as $product)
                <div class="col-6 col-sm-4 col-md-3 custom-5-column mb-4">
                    <div class="card h-100" style="max-width: 180px;">
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
                            <a href="#" class="btn btn-primary">Thêm vào giỏ</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
