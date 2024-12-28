<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'name',
        'email',
        'phone',
        'address',
        'note',
        'total_amount',
        'discount_amount',
        'final_amount',
        'payment_method',
        'order_status',
    ];

    /**
     * Mối quan hệ với User (người đặt hàng)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Mối quan hệ với OrderItem (các sản phẩm trong đơn hàng)
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
    public function orderItems()
    {
    return $this->hasMany(OrderItem::class);
    }
    /**
     * Cập nhật tổng tiền đơn hàng
     */
    public function updateTotalAmount()
    {
        // Tính tổng tiền từ các sản phẩm
        $total = $this->orderItems()
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->selectRaw('SUM(order_items.quantity * order_items.price) as total')
            ->first()
            ->total ?? 0;

        // Cập nhật tổng tiền
        $this->total_amount = $total;
        
        // Tính final_amount (sau khi trừ giảm giá)
        $this->final_amount = $total - ($this->discount_amount ?? 0);
        
        // Lưu vào database
        $this->save();

        return $this;
    }

    /**
     * Cập nhật số tiền giảm giá và tính lại tổng tiền
     */
    public function updateDiscountAmount($amount)
    {
        $this->discount_amount = $amount;
        $this->final_amount = $this->total_amount - $amount;
        $this->save();

        return $this;
    }

    /**
     * Tính tổng tiền của đơn hàng
     */
    public function calculateTotal()
    {
        return $this->orderItems()
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->selectRaw('SUM(order_items.quantity * order_items.price) as total')
            ->first()
            ->total ?? 0;
    }
}
