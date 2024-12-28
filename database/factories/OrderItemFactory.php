<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {
        return [
            'order_id' => Order::inRandomOrder()->first()->id, // Liên kết ngẫu nhiên với một đơn hàng
            'product_id' => Product::inRandomOrder()->first()->id, // Liên kết ngẫu nhiên với một sản phẩm
            'quantity' => $this->faker->numberBetween(1, 5), // Số lượng sản phẩm trong đơn
            'price' => $this->faker->numberBetween(50000, 500000), // Giá sản phẩm
        ];
    }
}
