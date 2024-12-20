    
    // Cập nhật giỏ hàng khi số lượng thay đổi
    function updateQuantity(button, change, productId) {
        const input = button.parentElement.querySelector('input');
        const newValue = parseInt(input.value) + change;
        if (newValue >= 1) {
            input.value = newValue;
            updateCart(productId, newValue);
        }
    }

    function updateCart(productId, quantity) {
        fetch("{{ route('cart.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ productId, quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật lại tổng cộng của giỏ hàng và từng sản phẩm
                document.getElementById('total-price').innerText = data.totalPrice + ' đ';
                const itemTotal = document.querySelector(`.item-total[data-product-id="${productId}"]`);
                if (itemTotal) {
                    itemTotal.innerText = data.itemTotal + ' đ';
                }
            } else {
                alert('Cập nhật không thành công');
            }
        });
    }

    // Xóa sản phẩm khỏi giỏ hàng
    function removeFromCart(productId) {
        fetch("/cart/remove/" + productId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ productId: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const rowToRemove = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
                if (rowToRemove) {
                    rowToRemove.remove(); // Xóa sản phẩm khỏi giỏ hàng

                    // Cập nhật tổng cộng giỏ hàng sau khi xóa
                    document.getElementById('total-price').innerText = data.totalPrice + ' đ';

                    // Nếu giỏ hàng trống
                    if (data.cartEmpty) {
                        document.querySelector('table tbody').innerHTML = `
                            <tr><td colspan="5" class="text-center py-5">
                                <i class="mdi mdi-cart-outline text-muted" style="font-size: 48px;"></i>
                                <h4 class="text-muted mb-3">Giỏ hàng trống</h4>
                                <a href="{{ route('index') }}" class="btn btn-primary">
                                    <i class="mdi mdi-shopping me-1"></i> Tiếp tục mua sắm
                                </a>
                            </td></tr>
                        `;
                    }
                }
            } else {
                Swal.fire('Lỗi', 'Không thể xóa sản phẩm khỏi giỏ hàng. Vui lòng thử lại!', 'error');
            }
        });
    }

    // Đặt hàng
    function placeOrder() {
        fetch("{{ route('checkout.create') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = "{{ route('checkout.index') }}";
            } else {
                alert('Đặt hàng không thành công');
            }
        });
    }
    // Xóa hết tất cả sản phẩm trong giỏ hàng
    function clearCart() {
        // Sử dụng SweetAlert để xác nhận
        Swal.fire({
            title: 'Bạn có chắc chắn muốn xóa hết tất cả sản phẩm trong giỏ hàng?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Có, xóa hết',
            cancelButtonText: 'Hủy bỏ',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Gửi yêu cầu xóa tất cả sản phẩm khỏi giỏ hàng
                fetch("{{ route('cart.clear') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cập nhật giao diện sau khi xóa hết sản phẩm
                        document.querySelector('table tbody').innerHTML = `
                            <tr><td colspan="5" class="text-center py-5">
                                <i class="mdi mdi-cart-outline text-muted" style="font-size: 48px;"></i>
                                <h4 class="text-muted mb-3">Giỏ hàng trống</h4>
                                <a href="{{ route('index') }}" class="btn btn-primary">
                                    <i class="mdi mdi-shopping me-1"></i> Tiếp tục mua sắm
                                </a>
                            </td></tr>
                        `;
                        document.getElementById('total-price').innerText = '0 đ';
                        
                        // Hiển thị thông báo thành công
                        Swal.fire(
                            'Đã xóa!',
                            'Tất cả sản phẩm trong giỏ hàng đã được xóa.',
                            'success'
                        );
                    } else {
                        // Thông báo lỗi nếu không thể xóa
                        Swal.fire(
                            'Lỗi',
                            'Xóa sản phẩm không thành công, vui lòng thử lại.',
                            'error'
                        );
                    }
                });
            }
        });
    }