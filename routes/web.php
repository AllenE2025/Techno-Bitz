<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ─── Public Routes ────────────────────────────────────────────────────────────

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'         => Route::has('login'),
        'canRegister'      => Route::has('register'),
        'laravelVersion'   => Application::VERSION,
        'phpVersion'       => PHP_VERSION,
        'categories'       => \App\Models\Category::all(),
        'featuredProducts' => \App\Models\Product::where('is_featured', true)
            ->where('is_active', true)
            ->limit(8)
            ->get(),
    ]);
})->name('home');

// Shop
Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
Route::get('/shop/{product:slug}', [ProductController::class, 'show'])->name('shop.show');

// ─── Authenticated Routes ──────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        $user = request()->user();
        return Inertia::render('Dashboard', [
            'stats' => [
                'totalOrders'     => \App\Models\Order::where('user_id', $user->id)->count(),
                'pendingOrders'   => \App\Models\Order::where('user_id', $user->id)->where('status', 'pending')->count(),
                'deliveredOrders' => \App\Models\Order::where('user_id', $user->id)->where('status', 'delivered')->count(),
            ],
            'recentOrders' => \App\Models\Order::where('user_id', $user->id)->latest()->limit(5)->get(),
        ]);
    })->middleware('verified')->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// ─── Admin Routes ──────────────────────────────────────────────────────────────

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/Dashboard', [
                'totalOrders'      => \App\Models\Order::count(),
                'totalRevenue'     => \App\Models\Order::where('status', 'delivered')->sum('total_amount'),
                'totalProducts'    => \App\Models\Product::where('is_active', true)->count(),
                'totalCategories'  => \App\Models\Category::count(),
                'recentOrders'     => \App\Models\Order::with('user')->latest()->limit(5)->get(),
                'lowStockProducts' => \App\Models\Product::where('stock', '<=', 5)->where('is_active', true)->orderBy('stock')->limit(10)->get(),
            ]);
        })->name('dashboard');

        // Products
        Route::resource('products', AdminProductController::class);

        // Categories
        Route::resource('categories', AdminCategoryController::class);

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });

require __DIR__ . '/auth.php';
