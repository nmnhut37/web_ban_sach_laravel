<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Banner;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Order;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->take(10)->get();
        $bestSellingProducts = Product::orderBy('purchase_count', 'desc')->take(10)->get();
        $banners = Banner::orderBy('order')->get();
        return view('shop.index', compact('products', 'bestSellingProducts', 'banners'));
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $userHasPurchased = false;
        if (auth::check()) {
            $userHasPurchased = Order::where('user_id', auth::id())
            ->whereHas('orderItems', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists();
        }
        
        $reviews = Review::where('product_id', $product->id)
        ->latest()
        ->paginate(5);
        return view('shop.product', compact('product', 'userHasPurchased', 'reviews'));
    }
}
