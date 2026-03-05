<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
        $cartCount = 0;
        $orderCount = 0;

        if (Auth::check()) {
            if (Auth::user()->role->name === 'customer') {
                $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
            }
            $orderCount = Order::where('user_id', Auth::id())->count();
        }
        $view->with([
            'cartCount' => $cartCount,
            'orderCount' => $orderCount,
        ]);
    });
    }
}
