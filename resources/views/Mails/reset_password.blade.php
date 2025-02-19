<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
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
        .email-body h1 {
            color: #007bff;
        }
        .email-body p {
            font-size: 16px;
            line-height: 1.5;
            margin: 10px 0;
        }
        .email-body a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .email-body a:hover {
            text-decoration: underline;
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
            <h1>Đặt lại mật khẩu</h1>
        </div>

        <div class="email-body">
            <h1>Xin chào {{ $user->name }},</h1>
            <p>Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Vui lòng nhấn vào liên kết dưới đây để đặt lại mật khẩu của bạn:</p>
            <p>
                <a href="{{ $resetUrl }}">Đặt lại mật khẩu</a>
            </p>
            <p>Nếu bạn không yêu cầu thay đổi mật khẩu, vui lòng bỏ qua email này.</p>
        </div>

        <div class="footer">
            <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
        </div>
    </div>
</body>
</html>
