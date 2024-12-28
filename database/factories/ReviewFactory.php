<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,  // Chọn ngẫu nhiên một user
            'product_id' => Product::inRandomOrder()->first()->id,  // Chọn ngẫu nhiên một sản phẩm
            'rating' => $this->faker->numberBetween(1, 5),  // Đánh giá từ 1 đến 5
            'comment' => $this->faker->sentence(10),  // Một câu nhận xét ngẫu nhiên
        ];
    }
}
