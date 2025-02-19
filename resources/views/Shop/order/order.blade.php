<div class="row">
    <div class="col-lg-8 ">
        <h4 class="mt-2">Thông tin hóa đơn</h4>
        <p class="text-muted mb-4">Điền thông tin dưới đây để chúng tôi có thể gửi hóa đơn cho bạn.</p>
            <div class="row my-2">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="billing-full-name">Họ và tên <span class="text-danger">*</span></label>
                        <input class="form-control" 
                               type="text" 
                               placeholder="Nhập họ và tên của bạn" 
                               id="billing-full-name" 
                               value="{{ old('full_name', $user->name ?? '') }}"
                               name="full_name"
                               required>
                        <div class="invalid-feedback">Vui lòng nhập họ tên của bạn</div>
                    </div>
                </div>
            </div> <!-- end row -->
            <div class="row my-2">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="billing-email-address">Địa chỉ email <span class="text-danger">*</span></label>
                        <input class="form-control" 
                               type="email" 
                               placeholder="Nhập email của bạn" 
                               id="billing-email-address" 
                               value="{{ old('email', $user->email ?? '') }}"
                               name="email"
                               required>
                        <div class="invalid-feedback">Vui lòng nhập email hợp lệ</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="billing-phone">Số điện thoại <span class="text-danger">*</span></label>
                        <input class="form-control" 
                               type="text" 
                               placeholder="(xx) xxx xxxx xxx" 
                               id="billing-phone" 
                               value="{{ old('phone', $user->phone ?? '') }}"
                               name="phone"
                               required>
                        <div class="invalid-feedback">Vui lòng nhập số điện thoại hợp lệ</div>
                    </div>
                </div>
            </div> <!-- end row -->
            <div class="row my-2">
                <div class="col-12">
                    <div class="form-group">
                        <label for="billing-address">Địa chỉ <span class="text-danger">*</span></label>
                        <input class="form-control" 
                               type="text" 
                               placeholder="Nhập địa chỉ của bạn" 
                               id="billing-address" 
                               value="{{ old('address', $user->address ?? '') }}"
                               name="address"
                               required>
                        <div class="invalid-feedback">Vui lòng nhập địa chỉ của bạn</div>
                    </div>
                </div>
            </div> <!-- end row -->
            <div class="row my-2">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="billing-note">Ghi chú đơn hàng:</label>
                        <textarea class="form-control" 
                                  id="billing-note" 
                                  rows="3"
                                  name="note"
                                  placeholder="Viết ghi chú cho đơn hàng..">{{ old('note') }}</textarea>
                    </div>
                </div>
            </div> <!-- end row -->
            <div class="row mt-4">
                <div class="col-sm-6">
                    <a href="{{ route('cart.index') }}" class="btn text-muted d-none d-sm-inline-block btn-link font-weight-semibold">
                        <i class="mdi mdi-arrow-left"></i> Quay lại giỏ hàng </a>
                </div> <!-- end col -->
                <div class="col-sm-6">
                    <div class="text-sm-right">
                        <a href="#" id="proceed-to-payment" class="btn btn-danger">
                            <i class="mdi mdi-truck-fast mr-1"></i> Tiến hành thanh toán
                        </a>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
    </div>
    <x-order-summary 
    :cart="$cart" 
    :cart-total="$cart_total" 
    :discount-amount="$discount_amount" 
    :final-total="$final_total" 
    :coupon="$coupon"
    />
</div>

<style>
.invalid-feedback {
    display: none;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}

.is-invalid ~ .invalid-feedback {
    display: block;
}

.is-invalid {
    border-color: #dc3545;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const proceedButton = document.getElementById('proceed-to-payment');
    const requiredFields = [
        {
            id: 'billing-full-name',
            message: 'Vui lòng nhập họ tên của bạn'
        },
        {
            id: 'billing-email-address',
            message: 'Vui lòng nhập email hợp lệ'
        },
        {
            id: 'billing-phone',
            message: 'Vui lòng nhập số điện thoại hợp lệ'
        },
        {
            id: 'billing-address',
            message: 'Vui lòng nhập địa chỉ của bạn'
        }
    ];

    // Hàm validate form
    function validateForm() {
        let isValid = true;
        
        // Reset tất cả trạng thái validation
        requiredFields.forEach(field => {
            const input = document.getElementById(field.id);
            input.classList.remove('is-invalid');
        });

        // Validate từng trường
        requiredFields.forEach(field => {
            const input = document.getElementById(field.id);
            const value = input.value.trim();
            
            if (!value) {
                isValid = false;
                input.classList.add('is-invalid');
            } else if (field.id === 'billing-email-address') {
                // Validate email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    isValid = false;
                    input.classList.add('is-invalid');
                }
            } else if (field.id === 'billing-phone') {
                // Validate phone (10 số)
                const phoneRegex = /^[0-9]{10}$/;
                if (!phoneRegex.test(value)) {
                    isValid = false;
                    input.classList.add('is-invalid');
                }
            }
        });
        
        return isValid;
    }

    // Xử lý sự kiện click nút "Tiến hành thanh toán"
    proceedButton.addEventListener('click', function(event) {
        event.preventDefault();
        
        if (validateForm()) {
            // Nếu form hợp lệ, chuyển sang tab thanh toán
            var myTab = new bootstrap.Tab(document.getElementById('tab-payment'));
            myTab.show();
        } else {
            // Cuộn đến trường lỗi đầu tiên
            const firstInvalidField = document.querySelector('.is-invalid');
            if (firstInvalidField) {
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Clear validation khi user nhập liệu
    requiredFields.forEach(field => {
        const input = document.getElementById(field.id);
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
});
</script>