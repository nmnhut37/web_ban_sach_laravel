<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'product_name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(1000, 100000), // Giá từ 10 đến 1000
            'img' => 'demo.png',
            'stock_quantity' => $this->faker->numberBetween(1, 100), // Số lượng tồn kho ngẫu nhiên
            'purchase_count' => $this->faker->numberBetween(0, 500), // Số lượng mua ngẫu nhiên
            'category_id' => Category::inRandomOrder()->first()->id, // Liên kết đến một danh mục ngẫu nhiên có sẵn
        ];
    }
}
