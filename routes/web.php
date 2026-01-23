<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TiktokDealController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CardExchangeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Product routes
Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::post('/product/{product}/comment', [ProductController::class, 'storeComment'])->name('product.comment')->middleware('auth');
Route::get('/product/{id}/download', [ProductController::class, 'download'])->name('product.download')->middleware('auth');

// Blog routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/category/{category}', [BlogController::class, 'category'])->name('blog.category');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/increment/{id}', [CartController::class, 'increment'])->name('cart.increment');
Route::post('/cart/decrement/{id}', [CartController::class, 'decrement'])->name('cart.decrement');
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/checkout/place', [CartController::class, 'placeOrder'])->name('checkout.place');

// User Account routes (requires auth)
Route::middleware('auth')->group(function () {
    Route::get('/account', [UserController::class, 'account'])->name('user.account');
    Route::put('/account', [UserController::class, 'updateAccount'])->name('user.account.update');
    Route::put('/account/password', [UserController::class, 'updatePassword'])->name('user.password.update');
    Route::get('/orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/orders/{order}', [UserController::class, 'orderDetail'])->name('user.orders.detail');
});

// Card Exchange routes
Route::middleware('auth')->group(function () {
    Route::get('/card-exchange', [CardExchangeController::class, 'index'])->name('card-exchange.index');
    Route::post('/card-exchange', [CardExchangeController::class, 'store'])->name('card-exchange.store');
});

// Admin routes (requires auth and admin role)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Order Management
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('admin.orders.show');
    Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.update-status');
    Route::delete('/orders/{order}', [AdminController::class, 'deleteOrder'])->name('admin.orders.delete');
    
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{user}/history', [AdminController::class, 'userHistory'])->name('admin.users.history');
    
    // Product Management
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/products/create/{category?}', [AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');
    
    // Features Management
    Route::get('/features', [AdminController::class, 'features'])->name('admin.features');
    Route::get('/features/create', [AdminController::class, 'createFeature'])->name('admin.features.create');
    Route::post('/features', [AdminController::class, 'storeFeature'])->name('admin.features.store');
    Route::get('/features/{feature}/edit', [AdminController::class, 'editFeature'])->name('admin.features.edit');
    Route::put('/features/{feature}', [AdminController::class, 'updateFeature'])->name('admin.features.update');
    Route::delete('/features/{feature}', [AdminController::class, 'deleteFeature'])->name('admin.features.delete');
    
    // Blog Management
    Route::get('/blogs', [AdminController::class, 'blogs'])->name('admin.blogs');
    Route::get('/blogs/create', [AdminController::class, 'createBlog'])->name('admin.blogs.create');
    Route::post('/blogs', [AdminController::class, 'storeBlog'])->name('admin.blogs.store');
    Route::get('/blogs/{blog}/edit', [AdminController::class, 'editBlog'])->name('admin.blogs.edit');
    Route::put('/blogs/{blog}', [AdminController::class, 'updateBlog'])->name('admin.blogs.update');
    Route::delete('/blogs/{blog}', [AdminController::class, 'deleteBlog'])->name('admin.blogs.delete');
    
    // Tiktok Deals Management
    Route::get('/tiktok-deals', [TiktokDealController::class, 'index'])->name('admin.tiktok-deals.index');
    Route::get('/tiktok-deals/create', [TiktokDealController::class, 'create'])->name('admin.tiktok-deals.create');
    Route::post('/tiktok-deals', [TiktokDealController::class, 'store'])->name('admin.tiktok-deals.store');
    Route::get('/tiktok-deals/{tiktokDeal}/edit', [TiktokDealController::class, 'edit'])->name('admin.tiktok-deals.edit');
    Route::put('/tiktok-deals/{tiktokDeal}', [TiktokDealController::class, 'update'])->name('admin.tiktok-deals.update');
    Route::delete('/tiktok-deals/{tiktokDeal}', [TiktokDealController::class, 'destroy'])->name('admin.tiktok-deals.destroy');
    Route::post('/tiktok-deals/{tiktokDeal}/toggle', [TiktokDealController::class, 'toggleActive'])->name('admin.tiktok-deals.toggle');
    
    // Card Exchange Management
    Route::get('/card-exchanges', [AdminController::class, 'cardExchanges'])->name('admin.card-exchanges');
    Route::put('/card-exchanges/{cardExchange}/status', [AdminController::class, 'updateCardExchangeStatus'])->name('admin.card-exchanges.update-status');
});
