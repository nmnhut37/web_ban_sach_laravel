    @extends('Layout.shop_layout')
    @push('styles')
    <style>
        .img-fluid {
            border: 1px solid;
            border-color: #dee2e6;
            border-radius: 10px;
        }
    </style>
    @endpush
    @section('content')
    <div class="container my-5">
        <div class="row">
            <!-- Phần 1: Thông tin sản phẩm -->
            <div class="col-md-6">
                <img src="{{ asset('storage/images/product/' . $product->img) }}" class="img-fluid" alt="{{ $product->product_name }}">
            </div>
            <div class="col-md-6">
                <h2 style="font-size: 3rem;">{{ $product->product_name }}</h2>
                <p class="text-primary mt-4" style="font-size: 1.5rem;">{{ number_format($product->price, 0, ',', '.') }} đ</p>

                <hr class="dropdown-divider my-4">

                <!-- Số lượng mua -->
                <div class="mb-3">
                    <label for="quantity" class="form-label">Số lượng mua:</label>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-outline-secondary" type="button" id="decrease">-</button>
                        <input type="text" id="quantity" class="form-control mx-1" value="1" style="width: 60px; text-align: center;">
                        <button class="btn btn-outline-secondary" type="button" id="increase">+</button>
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="btn btn-success">Mua ngay</a>
                    <a href="javascript:void(0)" class="btn btn-primary" id="addToCart">
                        <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                    </a>
                </div>
                <hr class="dropdown-divider my-4">
                <!-- Số lượng còn lại -->
                <div class="mb-3">
                    <span class="text-muted">Số lượng còn lại: {{ $product->stock_quantity}}</span>
                </div>
            </div>
        </div>

        <!-- Phần 2: Tab thông tin chi tiết và đánh giá sản phẩm -->
        <div class="mt-5">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="info-tab" data-bs-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">Thông tin chi tiết</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="reviews-tab" data-bs-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Đánh giá sản phẩm</a>
                </li>
            </ul>
            <div class="tab-content" id="productTabsContent">
                <!-- Tab 1: Thông tin chi tiết -->
                <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                    <div class="mt-3">
                        <h4>Thông tin chi tiết</h4>
                        <!-- Hiển thị danh mục của sản phẩm -->
                        <div class="mt-4">
                            <strong>Danh mục: </strong>
                            <span>{{ $product->category->name }}</span>
                        </div>
                        <p>{{ $product->description }}</p>
                    </div>
                </div>

                <!-- Tab 2: Đánh giá sản phẩm -->
                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    <div class="mt-3">
                        <h4>Đánh giá sản phẩm</h4>
                        <div class="mb-3">
                            <label for="review" class="form-label">Viết đánh giá của bạn:</label>
                            <textarea class="form-control" id="review" rows="3"></textarea>
                        </div>
                        <button class="btn btn-primary">Gửi đánh giá</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection
    @push('scripts')
    <script>
        // Thêm chức năng tăng/giảm số lượng
        document.getElementById('decrease').addEventListener('click', function() {
            var quantity = document.getElementById('quantity');
            var value = parseInt(quantity.value, 10);
            if (value > 1) {
                quantity.value = value - 1;
            }
        });

        document.getElementById('increase').addEventListener('click', function() {
            var quantity = document.getElementById('quantity');
            var value = parseInt(quantity.value, 10);
            quantity.value = value + 1;
        });

        // Chức năng thêm sản phẩm vào giỏ hàng
        document.getElementById('addToCart').addEventListener('click', function() {
            var productId = {{ $product->id }};
            var quantity = document.getElementById('quantity').value;

            // Gửi Ajax request để thêm sản phẩm vào giỏ
            fetch("/cart/add/" + productId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hiển thị thông báo thành công với SweetAlert
                    Swal.fire({
                        title: 'Sản phẩm đã được thêm vào giỏ!',
                        text: 'Bạn có thể tiếp tục mua sắm hoặc xem giỏ hàng.',
                        icon: 'success',
                        confirmButtonText: 'Xem giỏ hàng',
                        showCancelButton: true,
                        cancelButtonText: 'Tiếp tục mua sắm',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('cart.index') }}';  // Điều hướng tới trang giỏ hàng
                        }
                    });
                } else {
                    Swal.fire('Lỗi', 'Không thể thêm sản phẩm vào giỏ. Vui lòng thử lại!', 'error');
                }
            });
        });
    </script>
    @endpush
