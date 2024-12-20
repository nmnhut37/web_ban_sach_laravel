<?php
namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    // Khởi tạo đối tượng
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    // Xây dựng email
    public function build()
    {
        return $this->view('Mails.welcome')
            ->subject('Xác nhận tài khoản của bạn')
            ->with([
                'user' => $this->user,
                'verificationUrl' => route('verify', ['token' => $this->user->verification_token]),
            ]);
    }
}
