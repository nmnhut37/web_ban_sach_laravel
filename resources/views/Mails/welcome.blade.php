<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận tài khoản</title>
</head>
<body>
    <h1>Xin chào {{ $user->name }},</h1>
    <p>Cảm ơn bạn đã đăng ký tài khoản tại website của chúng tôi. Vui lòng nhấn vào liên kết bên dưới để xác nhận tài khoản:</p>
    <p>
        <a href="{{ route('verify', $user->verification_token) }}">
            Xác nhận tài khoản
        </a>
    </p>
    <p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>
    <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
</body>
</html>
