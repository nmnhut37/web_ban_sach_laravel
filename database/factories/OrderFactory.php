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
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? null, // Chọn một người dùng ngẫu nhiên hoặc null nếu không có người dùng
            'order_number' => 'ORD-' . $this->faker->unique()->numberBetween(100000, 999999),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'note' => $this->faker->optional()->sentence,
            'total_amount' => $this->faker->numberBetween(100000, 1000000),
            'discount_amount' => $this->faker->numberBetween(0, 100000),
            'final_amount' => $this->faker->numberBetween(100000, 1000000),
            'payment_method' => $this->faker->randomElement(['COD', 'MoMo', 'VNPay']),
            'order_status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),

            'created_at' => $this->faker->dateTimeBetween('2024-01-01', '2024-12-31'),
            'updated_at' => $this->faker->dateTimeBetween('2024-01-01', '2024-12-31'),
        ];
    }
}
