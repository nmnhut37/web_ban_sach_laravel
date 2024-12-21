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

    public function isValid()
    {
        return $this->expires_at >= now();
    }
}
