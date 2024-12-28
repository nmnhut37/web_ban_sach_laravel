<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'description',
        'price',
        'img',
        'stock_quantity',
        'purchase_count',
        'authors',
        'category_id',
    ];

    /**
     * Mối quan hệ với Category (danh mục sản phẩm)
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Mối quan hệ với OrderItem (các đơn hàng chứa sản phẩm này)
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
