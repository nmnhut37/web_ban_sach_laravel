    // Lắng nghe sự kiện cho tất cả các nút giảm
    document.querySelectorAll('.decrease').forEach(button => {
        button.addEventListener('click', function() {
            var quantityInput = this.nextElementSibling;  // Trường nhập liệu số lượng
            var currentQuantity = parseInt(quantityInput.value, 10);

            if (currentQuantity > 1) {
                quantityInput.value = currentQuantity - 1;
            }
        });
    });

    // Lắng nghe sự kiện cho tất cả các nút tăng
    document.querySelectorAll('.increase').forEach(button => {
        button.addEventListener('click', function() {
            var quantityInput = this.previousElementSibling;  // Trường nhập liệu số lượng
            var currentQuantity = parseInt(quantityInput.value, 10);

            quantityInput.value = currentQuantity + 1;
        });
    });

    // Xử lý thêm vào giỏ hàng
    document.querySelectorAll('.addToCart').forEach(function(button) {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const quantityInput = document.getElementById('quantity_' + productId);
            const quantity = quantityInput ? quantityInput.value : 1;

            // Lấy CSRF token từ meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Gửi Ajax request
            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    quantity: parseInt(quantity),
                    _token: csrfToken 
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Xảy ra lỗi vui lòng thử lại');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Thành công!',
                        text: 'Sản phẩm đã được thêm vào giỏ hàng',
                        icon: 'success',
                        confirmButtonText: 'Xem giỏ hàng',
                        showCancelButton: true,
                        cancelButtonText: 'Tiếp tục mua sắm'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/cart';
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Lỗi!',
                    text: 'Không thể thêm sản phẩm vào giỏ hàng',
                    icon: 'error',
                    confirmButtonText: 'Đóng'
                });
            });
        });
    });