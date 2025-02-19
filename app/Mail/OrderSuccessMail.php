<?php 

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Tạo một thể hiện mới của lớp.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Xây dựng thông điệp email.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Đơn hàng của bạn đã được xác nhận')
                    ->view('mails.order_success');
    }
}
