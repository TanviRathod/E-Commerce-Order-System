<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderService;


class OrderController extends Controller
{

    protected $orderService;                                
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $orders = $this->orderService->index();
            return view('customer.orders.index',compact('orders'));
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Failed to retrieve orders: ' . $e->getMessage());
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
         try {
            $order = $this->orderService->placeOrder();
            return redirect()->route('orders.index', $order->id)
                ->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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

    public function cancel($orderId)
    {
        try {
          $this->orderService->cancelOrderById($orderId);
            return redirect()->back()->with('success', 'Order cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function pay($orderId)
    {
            try {
                $this->orderService->payOrderById($orderId);
                return redirect()->back()->with('success', 'Order paid successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
    }
}
