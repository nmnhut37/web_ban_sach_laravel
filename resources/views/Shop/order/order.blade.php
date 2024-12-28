<div class="row">
    <div class="col-lg-8 ">
        <h4 class="mt-2">Thông tin hóa đơn</h4>
        <p class="text-muted mb-4">Điền thông tin dưới đây để chúng tôi có thể gửi hóa đơn cho bạn.</p>
            <div class="row my-2">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="billing-full-name">Họ và tên</label>
                        <input class="form-control" 
                               type="text" 
                               placeholder="Nhập họ và tên của bạn" 
                               id="billing-full-name" 
                               value="{{ old('full_name', $user->name ?? '') }}"
                               name="full_name">
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
                               name="phone">
                    </div>
                </div>
            </div> <!-- end row -->
            <div class="row my-2">
                <div class="col-12">
                    <div class="form-group">
                        <label for="billing-address">Địa chỉ</label>
                        <input class="form-control" 
                               type="text" 
                               placeholder="Nhập địa chỉ của bạn" 
                               id="billing-address" 
                               value="{{ old('address', $user->address ?? '') }}"
                               name="address"
                               required>
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
                    <a href="apps-ecommerce-shopping-cart.html" class="btn text-muted d-none d-sm-inline-block btn-link font-weight-semibold">
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Lắng nghe sự kiện click của nút "Tiến hành thanh toán"
        document.getElementById('proceed-to-payment').addEventListener('click', function(event) {
            event.preventDefault();  // Ngừng hành động mặc định (không reload trang)

            // Chuyển đến tab Thanh toán bằng Bootstrap Tab API
            var myTab = new bootstrap.Tab(document.getElementById('tab-payment'));
            myTab.show();  // Chuyển sang tab thanh toán
        });
    });
</script>
