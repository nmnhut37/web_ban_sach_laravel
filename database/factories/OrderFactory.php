<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        // Chọn một người dùng ngẫu nhiên hoặc null nếu không có người dùng
        $user = User::inRandomOrder()->first();

        return [
            'user_id' => $user->id ?? null,
            'order_number' => 'ORD-' . $this->faker->unique()->numberBetween(100000, 999999),
            'name' => $user->name ?? $this->faker->name, // Lấy tên từ User hoặc đặt ngẫu nhiên
            'email' => $user->email ?? $this->faker->unique()->safeEmail, // Lấy email từ User hoặc đặt ngẫu nhiên
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'note' => $this->faker->optional()->sentence,
            'total_amount' => $this->faker->numberBetween(100000, 1000000),
            'discount_amount' => $this->faker->numberBetween(0, 100000),
            'final_amount' => $this->faker->numberBetween(100000, 1000000),
            'payment_method' => $this->faker->randomElement(['COD', 'MoMo', 'VNPay']),
            'order_status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
            'created_at' => $this->faker->dateTimeBetween('2025-01-01', '2025-12-31'),
            'updated_at' => $this->faker->dateTimeBetween('2025-01-01', '2025-12-31'),
        ];
    }
}
