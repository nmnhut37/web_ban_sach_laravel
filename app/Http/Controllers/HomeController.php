<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Banner;

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
        return view('shop.product', compact('product'));
    }
}
