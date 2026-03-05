<?php 

namespace App\Services;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CustomerService{

    public function index(){
        return Product::active()->latest()->get();
    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        if($product->stock < 1){
            return back()->with('error', 'Product out of stock');
        }
        $userId = Auth::id();
        $cartItem = Cart::where('user_id', $userId)->where('product_id', $id)->first();
        if ($cartItem) { 
                $cartItem->increment('quantity');
                $cartItem->sub_total = $cartItem->quantity * $product->price;
                $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $id,
                'quantity' => 1,
                'sub_total' => $product->price
            ]);
        }
    }

   public function getCart()
    {
        $perPage = 5;
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->latest()->paginate($perPage);;
        $total = $cartItems->sum(function ($item) {
            return $item->sub_total;
        });
        return [
            'cart' => $cartItems,
            'total' => $total
        ];
    }

    public function removeCartItem($id)
    {
        $cartItem = Cart::find($id);
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.'
            ]);
        }
        $cartItem->delete();
        return response()->json([
            'success' => true,
            'message' => 'Item removed successfully.'
        ]);
    }

    public function updateCartQuantity($id, $type)
    {
        $userId = Auth::id();
        $cartItem = Cart::where('id', $id)->where('user_id', $userId)->first();
        if (!$cartItem) {
            throw new \Exception('Item not found.');
        }
        $product = $cartItem->product;
        if ($type === 'increase') {
            if ($cartItem->quantity >= $product->stock) {
                    throw new \Exception('Out of stock');
            }
            $cartItem->quantity += 1;
        }
        if ($type === 'decrease' && $cartItem->quantity > 1) {
            $cartItem->quantity -= 1;
        }
        $cartItem->save();
        $subtotal = $cartItem->quantity * $cartItem->product->price;
        $total = Cart::where('user_id', $userId)->with('product')->get()
                 ->sum(fn($item) => $item->quantity * $item->product->price);
        $cart_count = Cart::where('user_id', $userId)->sum('quantity');         
        return [
            'quantity' => $cartItem->quantity,
            'subtotal' => $subtotal,
            'total' => $total,
            'stock' => $product->stock,
            'cart_count' => $cart_count
        ];
    }
}