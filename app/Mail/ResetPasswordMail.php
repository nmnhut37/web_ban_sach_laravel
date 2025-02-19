<?php
namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use SerializesModels;

    public $user;
    public $token;

    // Constructor nhận user và token
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    // Build email
    public function build()
    {
        return $this->view('mails.reset_password') // View sẽ sử dụng để gửi email
            ->subject('Đặt lại mật khẩu') // Tiêu đề email
            ->with([
                'user' => $this->user,
                'resetUrl' => route('password.reset', ['token' => $this->token]), // Đảm bảo route là password.reset
            ]);
    }
}
