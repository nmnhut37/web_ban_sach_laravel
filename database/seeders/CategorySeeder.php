<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{

    public function run()
    {
        // Tạo 10 danh mục cha
        Category::factory()->count(10)->create()->each(function ($parentCategory) {
            // Tạo 2 danh mục con cho mỗi danh mục cha
            Category::factory()->count(2)->create([
                'parent_id' => $parentCategory->id,
            ]);
        });
    }
}
