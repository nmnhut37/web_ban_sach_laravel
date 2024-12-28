$(document).ready(function() {
    updateCartCount();

    // Hàm cập nhật số lượng giỏ hàng
    function updateCartCount() {
        $.ajax({
            url: '/cart/count',
            method: 'GET',
            success: function(response) {
                var cartCount = response.cart_count;

                // Cập nhật số lượng giỏ hàng trên navbar
                if (cartCount > 0) {
                    $('#cart-count').text(cartCount); // Hiển thị số lượng sản phẩm
                    $('#cart-count').show(); // Hiển thị badge
                } else {
                    $('#cart-count').hide(); // Ẩn badge nếu không có sản phẩm
                }
            }
        });
    }
    // Thêm sản phẩm vào giỏ hàng
    $('.add-to-cart').click(function(e) {
        e.preventDefault();

        var productId = $(this).closest('.product-card').data('product-id');
        var quantity = $(this).closest('.product-card').find('.quantity-input').val();

        $.ajax({
            url: `/cart/add/${productId}`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Thành công!',
                        text: response.message,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Xem giỏ hàng',
                        cancelButtonText: 'Tiếp tục mua sắm'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/cart';  // Chuyển đến trang giỏ hàng
                        }
                    });
                    // Cập nhật giỏ hàng, ví dụ cập nhật tổng giỏ hàng
                    updateCartCount();  // Cập nhật lại số lượng sản phẩm
                    $('#grand-total').text(response.cart_total);
                } else {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    });

    // Xóa sản phẩm khỏi giỏ hàng
    $(document).on('click', '.remove-from-cart', function(e) {
        e.preventDefault();

        var ele = $(this);
        var id = ele.data('id');  // Lấy ID sản phẩm từ data-id

        Swal.fire({
            title: 'Bạn có chắc muốn xóa sản phẩm này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/cart/delete/${id}`,
                    method: "DELETE",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: id
                    },
                    success: function(response) {
                        if (response.success) {
                            // Cập nhật lại giỏ hàng
                            ele.parents("tr").remove();  // Xóa dòng sản phẩm trong giỏ hàng
                            $('#grand-total').text(response.cart_total);  // Cập nhật tổng giỏ hàng
                            $('#final-total').text(response.cart_total);  // Cập nhật tổng cộng
                        } else {
                            Swal.fire({
                                title: 'Lỗi!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            }
        });
    });

    // Áp dụng mã giảm giá
    $('#apply-coupon').click(function () {
        let couponCode = $('#coupon-code').val();
        if (!couponCode) {
            $('#coupon-error').removeClass('d-none').text('Vui lòng nhập mã giảm giá');
            return;
        }

        $.ajax({
            url: '/cart/apply-coupon',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                coupon: couponCode
            },
            success: function(response) {
                if (response.success) {
                    $('#discount-row').removeClass('d-none');
                    $('#discount-amount').text(response.discount);
                    $('#final-total').text(response.final_total);
                    $('#coupon-success').removeClass('d-none').text('Mã giảm giá đã được áp dụng');
                    $('#coupon-error').addClass('d-none');
                } else {
                    $('#coupon-error').removeClass('d-none').text(response.message);
                    $('#coupon-success').addClass('d-none');
                }
            }
        });
    });

    // Cập nhật số lượng sản phẩm
    $(document).on('change', '.update-quantity', function() {
        var ele = $(this);
        var id = ele.data('id');  // Lấy ID sản phẩm từ data-id
        var quantity = ele.val();  // Lấy số lượng sản phẩm từ input

        $.ajax({
            url: `/cart/update/${id}`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    // Cập nhật lại giỏ hàng
                    ele.parents("tr").find(".item-total").text(response.item_total);  // Cập nhật tổng tiền của sản phẩm
                    $('#grand-total').text(response.cart_total);  // Cập nhật tổng giỏ hàng
                    $('#final-total').text(response.cart_total);  // Cập nhật tổng cộng
                } else {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', () => {
    // Tăng số lượng
    document.querySelectorAll('.increase').forEach(button => {
        button.addEventListener('click', function () {
            const card = this.closest('.product-card');
            const stockQuantity = parseInt(card.dataset.stockQuantity, 10);
            const input = card.querySelector('.quantity-input');
            let quantity = parseInt(input.value, 10);

            if (quantity < stockQuantity) {
                input.value = quantity + 1;
            } else {
                alert(`Số lượng tối đa là ${stockQuantity}`);
            }
        });
    });

    // Giảm số lượng
    document.querySelectorAll('.decrease').forEach(button => {
        button.addEventListener('click', function () {
            const card = this.closest('.product-card');
            const input = card.querySelector('.quantity-input');
            let quantity = parseInt(input.value, 10);

            if (quantity > 1) {
                input.value = quantity - 1;
            } else {
                //alert('Số lượng tối thiểu là 1');
            }
        });
    });

    // Kiểm tra khi nhập số lượng trực tiếp
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', function () {
            // Chỉ cho phép nhập số
            this.value = this.value.replace(/[^0-9]/g, '');

            const card = this.closest('.product-card');
            const stockQuantity = parseInt(card.dataset.stockQuantity, 10);
            let quantity = parseInt(this.value, 10);

            if (quantity > stockQuantity) {
                this.value = stockQuantity;
                alert(`Số lượng tối đa là ${stockQuantity}`);
            } else if (quantity < 1 || isNaN(quantity)) {
                this.value = 1;
                //alert('Số lượng tối thiểu là 1');
            }
        });
    });
});

