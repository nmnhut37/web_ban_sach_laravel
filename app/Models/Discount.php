<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 
        'discount_percentage', 
        'expires_at',
    ];

    // Bạn có thể thêm các phương thức tùy chỉnh để kiểm tra mã giảm giá còn hạn hay không
    public function isValid()
    {
        return $this->expires_at >= now(); // Kiểm tra xem mã giảm giá còn hiệu lực không
    }
}
