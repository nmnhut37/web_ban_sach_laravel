document.addEventListener('DOMContentLoaded', function() {
    // Khai báo các elements
    const starsContainer = document.querySelector('.stars-rating');
    const stars = document.querySelectorAll('.star-item');
    const selectedRating = document.getElementById('selected-rating');
    const submitButton = document.getElementById('submit-review');
    const reviewText = document.getElementById('review-text');
    const productId = document.getElementById('product-id');

    let isSubmitting = false;

    // Hàm cập nhật hiển thị sao
    function updateStars(rating, isHover = false) {
        stars.forEach(star => {
            const starRating = parseInt(star.dataset.rating);
            if (starRating <= rating) {
                star.classList.remove('bi-star');
                star.classList.add('bi-star-fill');
                if (!isHover) star.classList.add('active');
            } else {
                star.classList.remove('bi-star-fill', 'active');
                star.classList.add('bi-star');
            }
        });
    }

    // Xử lý click sao
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            selectedRating.value = rating;
            updateStars(rating);
        });
    });

    // Xử lý hover sao
    stars.forEach(star => {
        star.addEventListener('mouseover', function() {
            const rating = parseInt(this.dataset.rating);
            updateStars(rating, true);
        });
    });

    // Xử lý mouseleave khỏi container sao
    starsContainer.addEventListener('mouseleave', function() {
        const rating = parseInt(selectedRating.value);
        updateStars(rating);
    });

    // Hàm kiểm tra form
    function validateForm() {
        const rating = parseInt(selectedRating.value);
        const review = reviewText.value.trim();

        if (rating === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Chưa chọn số sao',
                text: 'Vui lòng chọn số sao đánh giá!'
            });
            return false;
        }

        if (review.length < 10) {
            Swal.fire({
                icon: 'warning',
                title: 'Nội dung quá ngắn',
                text: 'Vui lòng nhập nhận xét ít nhất 10 ký tự!'
            });
            return false;
        }

        return true;
    }

    // Xử lý submit form
    submitButton.addEventListener('click', async function() {
        if (isSubmitting || !validateForm()) return;

        try {
            isSubmitting = true;
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang gửi...';

            const response = await fetch('/reviews', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId.value,
                    rating: selectedRating.value,
                    comment: reviewText.value.trim()
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Có lỗi xảy ra');
            }

            // Hiển thị thông báo thành công
            await Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500
            });

            // Reload trang
            window.location.reload();

        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: error.message || 'Có lỗi xảy ra, vui lòng thử lại sau!'
            });
        } finally {
            isSubmitting = false;
            submitButton.disabled = false;
            submitButton.innerHTML = 'Gửi đánh giá';
        }
    });
});