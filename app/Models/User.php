<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone', 
        'date_of_birth',
        'address',
        'avatar',
        'status',
        'verification_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    // Các scope truy vấn
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeUnverified($query)
    {
        return $query->where('status', 'unverified');
    }
    // Phương thức kiểm tra vai trò
    public function hasRole($role)
    {
        return $this->role === $role;
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class); 
    }
}
