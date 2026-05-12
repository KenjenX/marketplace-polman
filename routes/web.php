<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// User Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentReceiptController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\XenditCallbackController;

// Admin Controllers
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController; // <-- TAMBAHAN BARU
use App\Http\Controllers\RegionController;

// Models
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::post('/xendit/callback', [XenditCallbackController::class, 'handleInvoice']);

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');

    // Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::patch('/profile/update', 'update')->name('profile.update');
        Route::post('/profile/address', 'updateAddress')->name('profile.address.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Cart
    Route::controller(CartController::class)->prefix('cart')->name('cart.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/add/{variant}', 'add')->name('add');
        Route::patch('/{item}', 'update')->name('update');
        Route::delete('/{item}', 'destroy')->name('destroy');
    });

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Orders
    Route::controller(OrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{order:uuid}', 'show')->name('show'); 
        Route::get('/{order:uuid}/track', 'track')->name('track'); 
    });

    // Payment Receipt (Sudah dirapikan, tidak dobel lagi)
    Route::post('/orders/{order:uuid}/upload-receipt', [PaymentReceiptController::class, 'store'])
        ->name('orders.upload_receipt');

    Route::post('/notifications/mark-all-read', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    })->name('notifications.markAllRead');

    // Region API
    Route::prefix('regions')->group(function () {
        Route::get('/provinces', [RegionController::class, 'provinces']);
        Route::get('/cities/{provinceId}', [RegionController::class, 'cities']);
        Route::get('/districts/{cityId}', [RegionController::class, 'districts']);
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // ROUTE DASHBOARD ADMIN SUDAH MENGGUNAKAN CONTROLLER
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', AdminProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('payment-methods', PaymentMethodController::class)->except(['show']);

    Route::controller(ProductVariantController::class)->group(function () {
        Route::get('products/{product}/variants', 'index')->name('products.variants.index');
        Route::get('products/{product}/variants/create', 'create')->name('products.variants.create');
        Route::post('products/{product}/variants', 'store')->name('products.variants.store');
        Route::get('variants/{variant}/edit', 'edit')->name('variants.edit');
        Route::put('variants/{variant}', 'update')->name('variants.update');
        Route::delete('variants/{variant}', 'destroy')->name('variants.destroy');
    });

    Route::controller(AdminOrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{order:uuid}', 'show')->name('show'); 
        Route::patch('/{order:uuid}/payment-status', 'updatePaymentStatus')->name('updatePaymentStatus');
        Route::patch('/{order:uuid}/status', 'updateOrderStatus')->name('updateStatus');
        Route::patch('/{order:uuid}/update-tracking', 'updateTracking')->name('update-tracking');
    });
});

require __DIR__.'/auth.php';