<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\OrderController;

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // return view('welcome');
     return redirect('/login');
});

Route::middleware('auth')->get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role->name === 'admin') {
        return redirect()->route('products.index');
    } elseif ($user->role->name === 'customer') {
        return redirect()->route('customer.index');
    }abort(403);
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::patch('/products/{id}/status',[ProductController::class, 'Status'])->name('admin.product.status');
    Route::resource('products', ProductController::class);
});


Route::middleware(['auth','customer'])->group(function () {
    Route::post('/customer/cart/add/{id}', [CustomerController::class, 'addToCart'])->name('customer.cart.add');
    Route::get('/cart', [CustomerController::class, 'cart'])->name('customer.cart.view');
    Route::delete('/cart/remove/{id}', [CustomerController::class, 'removeCart'])->name('cart.remove');
    Route::post('/cart/update/{id}',[CustomerController::class, 'updateQuantity'])->name('cart.update');
    Route::resource('customer', CustomerController::class);

    Route::post('/orders/{orderId}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{orderId}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::resource('orders',OrderController::class);
});


require __DIR__.'/auth.php';

