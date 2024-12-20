<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SocialAuthController;

Route::get('/test', function () {return view('test.test');});

//Home
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');
Route::get('/categories/{id}', [CategoryController::class, 'showCategory'])->name('category.show');

// Auth
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/verify/{token}', [AuthController::class, 'verify'])->name('verify');
Route::get('forgot-password', [AuthController::class, 'showForgotForm'])->name('forgot');
Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.password');
Route::get('password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('login/google', [SocialAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('login/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

// Order
Route::get('checkout', [OrderController::class, 'index'])->name('checkout.index');
Route::get('checkout/create', [OrderController::class, 'create'])->name('checkout.create');
Route::post('checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('checkout/{orderId}', [OrderController::class, 'show'])->name('checkout.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add'); // Sửa name để đồng nhất
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update'); // Route duy nhất cho cập nhật giỏ hàng
Route::post('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/apply-discount', [CartController::class, 'applyDiscount'])->name('cart.applyDiscount');

// Middleware auth và admin vẫn được giữ nguyên
Route::middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('admin', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // User Routes
    Route::middleware(['superadmin'])->group(function () { // Middleware chỉ dành cho Super Admin
        Route::get('admin/users/create', [UserController::class, 'create'])->name('user.create');
        Route::post('admin/users', [UserController::class, 'store'])->name('user.store');
        Route::get('admin/users/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('admin/users/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('admin/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    });

    Route::get('admin/users', [UserController::class, 'index'])->name('user.index'); // Admin và Super Admin được xem danh sách
    Route::get('admin/users/{user}', [UserController::class, 'show'])->name('user.show'); // Xem chi tiết

    // Product Routes
    Route::get('admin/products', [ProductController::class, 'index'])->name('product_list');
    Route::get('admin/products/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('admin/products', [ProductController::class, 'store'])->name('product.store');
    Route::get('admin/products/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('admin/products/{product}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('admin/products/{product}', [ProductController::class, 'destroy'])->name('product.destroy');

    // Category Routes
    Route::get('admin/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('admin/categories/{category}/children', [CategoryController::class, 'showChildren'])->name('categories_children');
    Route::post('admin/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('admin/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('admin/categories/{category}/children', [CategoryController::class, 'storeChildren'])->name('categories.storeChildren');
    Route::get('admin/categories/{category}/children/create', [CategoryController::class, 'createChildren'])->name('categories.createChildren');
    Route::get('admin/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('admin/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('admin/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Banner Routes
    Route::get('admin/banners', [BannerController::class, 'index'])->name('banners.index');
    Route::get('admin/banners/create', [BannerController::class, 'create'])->name('banners.create');
    Route::post('admin/banners', [BannerController::class, 'store'])->name('banners.store');
    Route::get('admin/banners/{banner}/edit', [BannerController::class, 'edit'])->name('banners.edit');
    Route::put('admin/banners/{banner}', [BannerController::class, 'update'])->name('banners.update');
    Route::delete('admin/banners/{banner}', [BannerController::class, 'destroy'])->name('banners.destroy');
});