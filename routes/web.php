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
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderManageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReviewController;

Route::get('/test', function () {return view('test.test');});

//Home
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');
Route::get('/categories/{id}', [CategoryController::class, 'showCategory'])->name('category.show');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

Route::post('/search', [ProductController::class, 'searchshop'])->name('search.shop');
Route::get('/search/products', [ProductController::class, 'searchSuggestions'])->name('product.search.suggestions');




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

// Profile
Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.showProfile');
Route::post('/profile/update-profile', [ProfileController::class, 'updateProfile'])->name('profile.updateProfile');
Route::post('/profile/update-avatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateAvatar');
Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
Route::get('/profile/orders/{order}', [ProfileController::class, 'show'])->name('order.details');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('/cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');


// Order
Route::get('checkout', [CartController::class, 'checkout'])->name('checkout.index');
Route::post('checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('checkout/{orderId}', [ProfileController::class, 'show'])->name('checkout.show');

// Payment
Route::prefix('order')->name('order.')->group(function () {
    Route::post('/store', [OrderController::class, 'store'])->name('store');
    Route::get('/success', [OrderController::class, 'success'])->name('success');
    Route::get('/failed', [OrderController::class, 'failed'])->name('failed');
});
Route::get('/payment/callback/{method}', [OrderController::class, 'handlePaymentCallback'])->name('payment.callback');




// Middleware auth và admin vẫn được giữ nguyên
Route::middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('admin', [DashboardController::class, 'index'])->name('dashboard');

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

    // Discount Routes
    Route::get('admin/discounts', [DiscountController::class, 'index'])->name('discounts.index');
    Route::get('admin/discounts/create', [DiscountController::class, 'create'])->name('discounts.create');
    Route::post('admin/discounts', [DiscountController::class, 'store'])->name('discounts.store');
    Route::get('admin/discounts/{discount}/edit', [DiscountController::class, 'edit'])->name('discounts.edit');
    Route::put('admin/discounts/{discount}', [DiscountController::class, 'update'])->name('discounts.update');
    route::delete('admin/discounts/{discount}', [DiscountController::class, 'destroy'])->name('discounts.destroy');

    // Order Manage
    Route::get('admin/orders', [OrderManageController::class, 'index'])->name('orders.index');
    route::get('admin/orders/create', [OrderManageController::class, 'create'])->name('orders.create');
    Route::post('admin/orders', [OrderManageController::class, 'store'])->name('orders.store');
    Route::get('admin/orders/{order}/edit', [OrderManageController::class, 'edit'])->name('orders.edit');
    Route::put('admin/orders/{order}', [OrderManageController::class, 'update'])->name('orders.update');
    Route::delete('admin/orders/{order}', [OrderManageController::class, 'destroy'])->name('orders.destroy');
    Route::get('admin/orders/{order}', [OrderManageController::class, 'show'])->name('orders.show');
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::post('/orders/{orderId}/items/add', [OrderManageController::class, 'addItem'])->name('orders.items.add');
    Route::delete('/orders/{orderId}/items/{itemId}', [OrderManageController::class, 'destroyOrderItem'])->name('orders.items.destroy');

    // Review Manage
    Route::get('admin/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('admin/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('admin/reviews', [ReviewController::class, 'add'])->name('reviews.add');
    Route::get('admin/reviews/{id}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('admin/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('admin/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('reviews/getProductsByCategory', [ReviewController::class, 'getProductsByCategory'])->name('reviews.getProductsByCategory');

});