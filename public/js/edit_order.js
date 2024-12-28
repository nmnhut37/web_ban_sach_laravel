$(document).ready(function () {
    // Thiết lập CSRF token cho tất cả các request AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Validate form trước khi submit
    $('#orderForm').on('submit', function(e) {
        let isValid = true;
        const requiredFields = ['name', 'email', 'phone', 'address'];
        
        requiredFields.forEach(field => {
            const input = $(`#${field}`);
            if (!input.val()) {
                isValid = false;
                input.addClass('is-invalid');
            } else {
                input.removeClass('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            Swal.fire('Lỗi!', 'Vui lòng điền đầy đủ thông tin bắt buộc', 'error');
        }

        // Kiểm tra có ít nhất một sản phẩm trong đơn hàng
        if ($('input[name^="items"][name$="[quantity]"]').length === 0) {
            e.preventDefault();
            Swal.fire('Lỗi!', 'Đơn hàng phải có ít nhất một sản phẩm', 'error');
        }
    });

    // Xử lý xóa đơn hàng
    $('.btn-delete-order').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Bạn có chắc muốn xóa đơn hàng?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-order-form').submit();
            }
        });
    });

    // Xử lý nút thêm sản phẩm
    $('#btn-add-item').on('click', function() {
        $('#addProductModal').modal('show');
    });

    // Xử lý nút xóa sản phẩm
    $('.btn-delete-item').on('click', function () {
        const orderId = $(this).data('order-id');
        const itemId = $(this).data('item-id');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Bạn có chắc muốn xóa sản phẩm?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/orders/${orderId}/items/${itemId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            row.remove();
                            // Cập nhật tổng tiền
                            $('#total_amount').val(response.order.total_amount);
                            $('#final_amount').val(response.order.final_amount);
                            updateTotals();
                            Swal.fire('Thành công!', 'Đã xóa sản phẩm khỏi đơn hàng.', 'success');
                        } else {
                            Swal.fire('Lỗi!', response.message || 'Có lỗi xảy ra khi xóa sản phẩm.', 'error');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr.responseText);
                        Swal.fire('Lỗi!', 'Có lỗi xảy ra khi xóa sản phẩm.', 'error');
                    }
                });
            }
        });
    });

    // Bắt sự kiện thay đổi số lượng
    $(document).on('change', 'input[name^="items"][name$="[quantity]"]', updateTotals);

    // Bắt sự kiện thay đổi giảm giá
    $('#discount_amount').on('change', updateTotals);

    // Tìm kiếm sản phẩm
    let typingTimer;
    const doneTypingInterval = 500;

    $('#categoryFilter').on('change', function() {
        searchProducts();
    });

    $('#searchProduct').on('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            searchProducts();
        }, doneTypingInterval);
    });

    // Xử lý thêm sản phẩm vào đơn hàng
    function searchProducts() {
        const searchTerm = $('#searchProduct').val();
        const categoryId = $('#categoryFilter').val();
    
        $.ajax({
            url: searchProductUrl, // Sử dụng biến đã định nghĩa
            type: 'GET',
            data: {
                search: searchTerm,
                category_id: categoryId
            },
            beforeSend: function() {
                $('#productList').html('<tr><td colspan="4" class="text-center">Đang tìm kiếm...</td></tr>');
            },
            success: function(response) {
                if (response.products && response.products.length > 0) {
                    let html = '';
                    response.products.forEach(function(product) {
                        html += `
                            <tr>
                                <td>${product.product_name}</td>
                                <td>${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(product.price)}</td>
                                <td>
                                    <input type="number" class="form-control form-control-sm product-quantity" 
                                        value="1" min="1">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm add-product-to-order"
                                        data-id="${product.id}"
                                        data-name="${product.product_name}"
                                        data-price="${product.price}">
                                        Thêm
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#productList').html(html);
                } else {
                    $('#productList').html('<tr><td colspan="4" class="text-center">Không tìm thấy sản phẩm nào</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Lỗi:', error);
                $('#productList').html(`
                    <tr>
                        <td colspan="4" class="text-center text-danger">
                            Có lỗi xảy ra khi tìm kiếm. 
                            Vui lòng thử lại sau.
                        </td>
                    </tr>
                `);
            }
        });
    }


    // Xử lý thêm sản phẩm vào đơn hàng
    $(document).on('click', '.add-product-to-order', function() {
        const button = $(this);
        const productId = button.data('id');
        const quantity = button.closest('tr').find('.product-quantity').val();
        
        // Thêm loading state
        button.prop('disabled', true);
        
        $.ajax({
            url: addItemUrl,
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const product = response.product;
                    
                    // Định dạng tiền tệ
                    const formatter = new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    });

                    // Kiểm tra sản phẩm đã tồn tại
                    const existingRow = $(`input[name^="items"][value="${productId}"]`).closest('tr');
                    
                    if (existingRow.length) {
                        // Cập nhật số lượng
                        const quantityInput = existingRow.find('input[name^="items"][name$="[quantity]"]');
                        const newQuantity = parseInt(quantityInput.val()) + parseInt(quantity);
                        quantityInput.val(newQuantity);
                        
                        // Cập nhật tổng tiền của sản phẩm
                        const totalPrice = product.price * newQuantity;
                        existingRow.find('td:eq(3)').text(formatter.format(totalPrice));
                    } else {
                        // Thêm dòng mới ở đầu bảng
                        const newRowIndex = $('#order-items-table tbody tr').length;
                        const newRow = `
                            <tr>
                                <td>${product.name}</td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" 
                                        name="items[${newRowIndex}][quantity]" 
                                        value="${quantity}" min="1">
                                    <input type="hidden" name="items[${newRowIndex}][id]" value="${productId}">
                                </td>
                                <td>${formatter.format(product.price)}</td>
                                <td>${formatter.format(product.price * quantity)}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete-item" 
                                        data-order-id="${orderId}" 
                                        data-item-id="${productId}">
                                        Xóa
                                    </button>
                                </td>
                            </tr>
                        `;
                        
                        // Thêm dòng mới vào đầu bảng
                        $('#order-items-table tbody').prepend(newRow);
                    }

                    // Cập nhật tổng tiền đơn hàng
                    $('#total_amount').text(formatter.format(response.order.total_amount));
                    $('#final_amount').text(formatter.format(response.order.final_amount));

                    // Đóng modal
                    $('#addProductModal').modal('hide');
                    
                    // Thông báo thành công
                    Swal.fire({
                        title: 'Thành công!',
                        text: 'Đã thêm sản phẩm vào đơn hàng.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Lỗi!', response.message || 'Không thể thêm sản phẩm.', 'error');
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                Swal.fire('Lỗi!', 'Có lỗi xảy ra khi thêm sản phẩm.', 'error');
            },
            complete: function() {
                // Bỏ loading state
                button.prop('disabled', false);
            }
        });
    });

    
    // Hàm cập nhật tổng tiền
    function updateTotals() {
        let total = 0;
        const formatter = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        });

        $('tbody tr').each(function() {
            const quantity = $(this).find('input[name^="items"][name$="[quantity]"]').val() || 0;
            const priceText = $(this).find('td:eq(2)').text();
            const price = parseFloat(priceText.replace(/[^0-9.-]+/g,"")); 
            
            if (!isNaN(quantity) && !isNaN(price)) {
                const subtotal = quantity * price;
                total += subtotal;
                $(this).find('td:eq(3)').text(formatter.format(subtotal));
            }
        });

        $('#total_amount').val(total);
        const discount = parseFloat($('#discount_amount').val()) || 0;
        $('#final_amount').val(total - discount);
    }
});
