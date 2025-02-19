<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng thành công</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
        }
        .email-body {
            padding: 20px;
        }
        .email-body h3 {
            color: #007bff;
        }
        .order-details {
            margin-bottom: 20px;
        }
        .order-details p {
            margin: 5px 0;
        }
        .order-items {
            margin-top: 10px;
        }
        .order-items li {
            margin-bottom: 10px;
        }
        .total {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Đơn hàng của bạn đã được xác nhận!</h1>
        </div>

        <div class="email-body">
            <p>Cảm ơn bạn đã đặt hàng tại cửa hàng của chúng tôi. Dưới đây là thông tin đơn hàng của bạn:</p>

            <div class="order-details">
                <h3>Mã đơn hàng: {{ $order->order_number }}</h3>
                <p><strong>Họ và tên:</strong> {{ $order->name }}</p>
                <p><strong>Email:</strong> {{ $order->email }}</p>
                <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
            </div>

            <div class="order-items">
                <h3>Chi tiết sản phẩm:</h3>
                <ul>
                    @foreach ($order->items as $item)
                        <li>{{ $item->product->product_name }} - Số lượng: {{ $item->quantity }} - Giá: {{ number_format($item->price, 0, '.', ',') }} đ</li>
                    @endforeach
                </ul>
            </div>

            <div class="total">
                <p><strong>Tổng giá trị đơn hàng:</strong> {{ number_format($order->total_amount, 0, '.', ',') }} đ</p>
                <p><strong>Giảm giá:</strong> {{ number_format($order->discount_amount, 0, '.', ',') }} đ</p>
                <p><strong>Tổng cộng:</strong> {{ number_format($order->final_amount, 0, '.', ',') }} đ</p>
            </div>

            <p>Chúng tôi sẽ liên hệ với bạn sớm để xác nhận đơn hàng.</p>
        </div>

        <div class="footer">
            <p>Trân trọng, <br> Cửa hàng của chúng tôi</p>
        </div>
    </div>
</body>
</html>
