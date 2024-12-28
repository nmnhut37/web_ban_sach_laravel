<!-- Phần đánh giá sản phẩm -->
<div class="reviews-section">
    @if(auth()->check() && $userHasPurchased)
        <div class="review-form mb-4">
            <input type="hidden" id="product-id" value="{{ $product->id }}">
            
            <div class="mb-3">
                <label class="form-label">Đánh giá sao:</label>
                <div class="stars-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star star-item" data-rating="{{ $i }}" style="cursor: pointer;"></i>
                    @endfor
                </div>
                <input type="hidden" id="selected-rating" value="0"> <!-- Số sao đã chọn -->
            </div>

            <div class="mb-3">
                <label for="review-text" class="form-label">Nhận xét của bạn:</label>
                <textarea class="form-control" id="review-text" rows="3" placeholder="Nhập nhận xét của bạn về sản phẩm..."></textarea>
            </div>

            <button type="button" class="btn btn-primary" id="submit-review">Gửi đánh giá</button>
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            Bạn cần đăng nhập và mua sản phẩm này để có thể đánh giá.
        </div>
    @endif

    <!-- Hiển thị đánh giá -->
    <div class="reviews-list mt-4">
        <h5>Đánh giá từ khách hàng</h5>
        @forelse($reviews as $review)
            <div class="review-item card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $review->user->name }}</strong>
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="stars my-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                        @endfor
                    </div>
                    <p class="mb-0">{{ $review->comment }}</p>
                </div>
            </div>
        @empty
            <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
        @endforelse

        <!-- Phân trang -->
        {{ $reviews->links() }}
    </div>
</div>
<script src="{{ asset('js/reviews.js') }}"></script>