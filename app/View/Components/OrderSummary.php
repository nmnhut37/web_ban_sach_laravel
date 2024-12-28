<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Session;

class OrderSummary extends Component
{
    public $cartItems;
    public $total;

    public function __construct()
    {
        $this->cartItems = session('cart', []);
        $this->total = $this->calculateTotal($this->cartItems);
    }

    public function render()
    {
        // Tính giảm giá và tổng tiền sau khi giảm
        $discount_amount = $this->calculateDiscount($this->total);  // Tính giảm giá
        $final_total = $this->total - $discount_amount;  // Tổng tiền cuối cùng sau khi áp dụng giảm giá
        
        // Lấy mã giảm giá từ session (nếu có)
        $coupon = session('coupon', null); 

        return view('components.order-summary', [
            'cart' => $this->cartItems,
            'cart_total' => $this->total,
            'discount_amount' => $discount_amount,
            'final_total' => $final_total,
            'coupon' => $coupon,
        ]);
    }

    private function calculateDiscount($total)
    {
        // Lấy thông tin mã giảm giá từ session
        $coupon = session('coupon', null);
        if ($coupon && isset($coupon['discount'])) {
            return $total * ($coupon['discount'] / 100); // Giảm giá theo phần trăm
        }
        return 0;  // Nếu không có mã giảm giá, không giảm giá
    }


    private function calculateTotal($cartItems)
    {
        $total = 0;
        foreach ($cartItems as $item) {
            // Tính tổng giá trị giỏ hàng
            $total += $item['price'] * $item['quantity'];
        }
        return $total;  // Trả về tổng
    }
}
