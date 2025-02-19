@props([
    'cart' => [],
    'cart_total' => 0,
    'discount_amount' => 0,
    'final_total' => 0,
    'coupon' => null,
])

<div class="col-lg-4">
    <div class="border p-3 mt-4 mt-lg-0 rounded">
        <h4 class="header-title mb-3">Tóm tắt hóa đơn</h4>
        <div class="table-responsive">
            <table class="table table-centered mb-0">
                <tbody>
                    @foreach($cart as $item)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/images/product/' . ($item['img'] ?? 'no-image.jpg')) }}" alt="product-img" class="rounded mr-2" height="48">
                            <p class="m-0 d-inline-block align-middle">
                                <span class="text-body font-weight-semibold">{{ $item['product_name'] }}</span>
                                <br>
                                <small>{{ $item['quantity'] }} x {{ number_format($item['price'], 0) }} đ</small>
                            </p>
                        </td>
                        <td class="text-right">
                            {{ number_format($item['price'] * $item['quantity'], 0) }} đ
                        </td>
                    </tr>
                    @endforeach

                    <tr class="text-right">
                        <td>
                            <h6 class="m-0">Tổng cộng:</h6>
                        </td>
                        <td class="text-right">
                            {{ number_format($cart_total, 0) }} đ
                        </td>
                    </tr>

                    @if($discount_amount > 0)
                    <tr class="text-right">
                        <td>
                            <h6 class="m-0">Giảm giá ({{ $coupon }}):</h6>
                        </td>
                        <td class="text-right text-danger">
                            -{{ number_format($discount_amount, 0) }} đ
                        </td>
                    </tr>
                    @endif

                    <tr class="text-right">
                        <td>
                            <h5 class="m-0">Tổng tiền:</h5>
                        </td>
                        <td class="text-right font-weight-semibold">
                            {{ number_format($final_total, 0) }} đ
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
