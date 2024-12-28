<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * Mối quan hệ với Order (đơn hàng chứa sản phẩm này)
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * Mối quan hệ với Product (sản phẩm trong mục đơn hàng)
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    /**
     * Tính tổng tiền của item
     */
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }
}
