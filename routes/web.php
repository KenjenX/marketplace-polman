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

    // Orders (FIXED: Menghapus redundansi /orders/orders)
    Route::controller(OrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{order:uuid}', 'show')->name('show'); // URL: /orders/{uuid}
        Route::get('/{order:uuid}/track', 'track')->name('track'); // URL: /orders/{uuid}/track
        Route::post('/orders/{order}/upload-receipt', [PaymentReceiptController::class, 'store'])->name('orders.upload_receipt');
    });

    // Payment Receipt
    Route::post('/orders/{order:uuid}/upload-receipt', [PaymentReceiptController::class, 'store'])
        ->name('orders.upload_receipt');

    Route::post('/notifications/mark-all-read', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    })->name('notifications.markAllRead');

    // Region API
    Route::middleware('auth')->group(function () {

        Route::get('/regions/provinces', [RegionController::class, 'provinces']);

        Route::get('/regions/cities/{provinceId}', [RegionController::class, 'cities']);

        Route::get('/regions/districts/{cityId}', [RegionController::class, 'districts']);
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard', [
            'categoryCount' => Category::count(),
            'productCount' => Product::count(),
            'orderCount' => Order::count(),
            'waitingValidationCount' => Order::where('status', 'waiting_receipt_validation')->count(),
        ]);
    })->name('dashboard');

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