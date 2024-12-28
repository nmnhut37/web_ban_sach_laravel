<div class="row">
    <div class="col-lg-8">
        <h4 class="mt-2">Chọn phương thức thanh toán</h4>
        <p class="text-muted mb-4">Chọn một trong các phương thức thanh toán bên dưới để hoàn tất đơn hàng.</p>

        <!-- Thanh toán khi nhận hàng (COD) -->
        <div class="border p-3 mb-3 rounded">
            <div class="row my-2">
                <div class="col-md-8">
                    <div class="custom-control custom-radio">
                        <input type="radio" id="BillingOptCOD" name="payment_method" class="custom-control-input" value="cod" checked>
                        <label class="custom-control-label font-16 font-weight-bold" for="BillingOptCOD">Thanh toán khi nhận hàng (COD)</label>
                    </div>
                    <p class="mb-0 pl-3 pt-1">Bạn sẽ thanh toán khi nhận được hàng.</p>
                </div>
                <div class="col-md-4 text-sm-right mt-3 mt-sm-0">
                    <img src="{{asset('storage/images/payment/cod-logo.jpg')}}" height="80" alt="cod-img">
                </div>
            </div>
        </div>

        <!-- Thanh toán qua MoMo -->
        <!--
        <div class="border p-3 mb-3 rounded">
            <div class="row my-2">
                <div class="col-md-12">
                    <div class="custom-control custom-radio">
                        <input type="radio" id="BillingOptMoMo" name="payment_method" class="custom-control-input" value="momo">
                        <label class="custom-control-label font-16 font-weight-bold" for="BillingOptMoMo">Thanh toán qua MoMo</label>
                    </div>
                    <p class="mb-0 pl-3 pt-1">Bạn sẽ được chuyển đến ứng dụng hoặc trang web MoMo để thanh toán.</p>
                </div>
            </div>
        </div>
        -->
        
        <!-- Thanh toán qua VNPay -->
        <div class="border p-3 mb-3 rounded">
            <div class="row my-2">
                <div class="col-md-8">
                    <div class="custom-control custom-radio">
                        <input type="radio" id="BillingOptVNPay" name="payment_method" class="custom-control-input" value="vnpay">
                        <label class="custom-control-label font-16 font-weight-bold" for="BillingOptVNPay">Thanh toán qua VNPay</label>
                    </div>
                    <p class="mb-0 pl-3 pt-1">Bạn sẽ được chuyển đến cổng thanh toán VNPay để hoàn tất giao dịch.</p>
                </div>
                <div class="col-md-4 text-sm-right mt-3 mt-sm-0">
                    <img src="{{asset('storage/images/payment/vnpay-logo.jpg')}}" height="80" alt="vnpay-img">
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm-6">
                <a href="{{ route('cart.index') }}" class="btn text-muted d-none d-sm-inline-block btn-link font-weight-semibold">
                    <i class="mdi mdi-arrow-left"></i> Quay lại giỏ hàng </a>
            </div>
            <div class="col-sm-6">
                <div class="text-sm-right">
                    <button type="submit" id="complete-order" class="btn btn-danger">
                        <i class="mdi mdi-cash-multiple mr-1"></i> Hoàn tất đơn hàng
                    </button>
                </div>
            </div>
        </div>
    </div>
    <x-order-summary 
        :cart="$cart" 
        :cart-total="$cart_total" 
        :discount-amount="$discount_amount" 
        :final-total="$final_total" 
        :coupon="$coupon"
    />
</div>
