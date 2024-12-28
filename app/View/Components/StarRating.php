<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Product;

class StarRating extends Component
{
    public $productId;
    public $averageRating;

    public function __construct($productId)
    {
        $this->productId = $productId;
        // Tính toán điểm sao trung bình của sản phẩm
        $this->averageRating = Product::find($this->productId)->reviews()->avg('rating');
    }

    public function render()
    {
        return view('components.star-rating');
    }
}
