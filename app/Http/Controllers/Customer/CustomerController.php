<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $products = $this->customerService->index();
            return view('customer.products.index',compact('products'));
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Failed to retrieve products: ' . $e->getMessage());
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function addToCart($id)
    {
        try{
            $this->customerService->addToCart($id);
            return redirect()->back()->with('success', 'Product added to cart successfully.');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Failed to add product to cart: '.$e->getMessage());
        }
    }

    public function cart()
    {
        try{
            $data = $this->customerService->getCart();
           return view('customer.products.cart', $data);
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Failed to load cart: '.$e->getMessage());
        }
    }

    public function removeCart($id)
    {
        try {
            $this->customerService->removeCartItem($id);
            return response()->json([
                'success' => true,
                'message' => 'Item removed successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item: ' . $e->getMessage()
            ]);
        }
    }

    public function updateQuantity($id)
    {
        try {
            $result = $this->customerService->updateCartQuantity($id, request('type'));

            return response()->json([
                'success' => true,
                'quantity' => $result['quantity'],
                'subtotal' => $result['subtotal'],
                'total' => $result['total']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
