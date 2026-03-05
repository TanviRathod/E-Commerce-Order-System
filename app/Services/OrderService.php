<?php

namespace App\Services;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Exception;

use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function index()
    {
        return Order::with('items.product')->where('user_id', Auth::id())->get();
    }

    public function placeOrder()
    {
        $userId = Auth::id();
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();
        if ($cartItems->isEmpty()) {
            throw new Exception('Cart is empty');
        }
        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($cartItems as $item) {
                if ($item->quantity > $item->product->stock) {
                    throw new Exception("Product {$item->product->name} is out of stock.");
                }
                $total += $item->product->price * $item->quantity;
            }
            $order = Order::create([
                'user_id' => $userId,
                'total_amount' => $total,
                'status' => 'pending'
            ]);
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'subtotal' => $item->product->price * $item->quantity,
                ]);
                $item->product->decrement('stock', $item->quantity);
            }
            Cart::where('user_id', $userId)->delete();
            DB::commit();
            return $order;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function cancelOrderById($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        if ($order->status === 'cancelled') {
            throw new \Exception('Order already cancelled');
        }
        DB::beginTransaction();
        try {
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
            $order->update(['status' => 'cancelled']);
            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function payOrderById($orderId)
    {
        $order = Order::findOrFail($orderId);
        if ($order->status !== 'pending') {
            throw new \Exception('Only pending orders can be paid');
        }
        $order->update(['status' => 'paid']);
        return $order;
    }
}