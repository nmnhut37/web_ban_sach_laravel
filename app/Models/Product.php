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
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
