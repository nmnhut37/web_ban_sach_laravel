<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{

    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'), // Mật khẩu giả
            'role' => $this->faker->randomElement(['super_admin', 'admin', 'user']), // Role ngẫu nhiên
            'phone' => $this->faker->numerify('###########'), // Số điện thoại 11 chữ số
            'date_of_birth' => $this->faker->date(),
            'address' => $this->faker->address(),
            'avatar' => null,
            'status' => $this->faker->randomElement(['verified', 'unverified']),
            'verification_token' => null, // Đặt token trống
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'unverified',
                'verification_token' => sha1(time()),
            ];
        });
    }

    public function verified()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'verified',
                'verification_token' => null,
            ];
        });
    }
}
