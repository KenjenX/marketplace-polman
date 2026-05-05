<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentReceiptController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\HomeController; // Pastikan ini ada
use App\Http\Controllers\DashboardController;

Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

// PERBAIKAN DI SINI:
// Kita arahkan ke HomeController@index agar variabel $products dan $categories terkirim
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->middleware(['auth'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->middleware(['auth'])->name('profile.update');
    Route::post('/profile/address', [ProfileController::class, 'updateAddress'])->middleware(['auth'])->name('profile.address.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->middleware(['auth'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index')->middleware(['auth']);
    Route::post('/cart/add/{variant}', [CartController::class, 'add'])->name('cart.add')->middleware(['auth']);
    Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update')->middleware(['auth']);
    Route::delete('/cart/{item}', [CartController::class, 'destroy'])->name('cart.destroy')->middleware(['auth']);

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index')->middleware(['auth']);
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::post('/orders/{order}/upload-receipt', [PaymentReceiptController::class, 'store'])->name('orders.uploadReceipt');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

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

    Route::get('products/{product}/variants', [ProductVariantController::class, 'index'])->name('products.variants.index');
    Route::get('products/{product}/variants/create', [ProductVariantController::class, 'create'])->name('products.variants.create');
    Route::post('products/{product}/variants', [ProductVariantController::class, 'store'])->name('products.variants.store');

    Route::get('variants/{variant}/edit', [ProductVariantController::class, 'edit'])->name('variants.edit');
    Route::put('variants/{variant}', [ProductVariantController::class, 'update'])->name('variants.update');
    Route::delete('variants/{variant}', [ProductVariantController::class, 'destroy'])->name('variants.destroy');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateOrderStatus'])->name('orders.updateStatus');
    Route::resource('payment-methods', PaymentMethodController::class)->except(['show']);
});

// Rute untuk email verification
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back();
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Rute untuk dashboard yang hanya bisa diakses oleh pengguna yang sudah terverifikasi emailnya
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'index']);
});

// Rute untuk halaman edit profile yang hanya bisa diakses oleh pengguna yang sudah terverifikasi emailnya
Route::get('/profile/edit', function () {
    return view('profile.edit', [
        'user' => Auth::user()
    ]);
})->middleware(['auth'])->name('profile.edit');

// Rute untuk halaman dashboard yang hanya bisa diakses oleh pengguna yang sudah terverifikasi emailnya
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/dashboard')->with('verified', 'Email berhasil diverifikasi!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('resent', 'Link verifikasi dikirim ulang!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'store']);

require __DIR__.'/auth.php';