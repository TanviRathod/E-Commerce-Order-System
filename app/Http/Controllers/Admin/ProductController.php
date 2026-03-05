<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;


class ProductController extends Controller
{
     protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
        $products = $this->productService->list(); 
        return view('admin.products.index', compact('products'));
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Failed to retrieve products: ' . $e->getMessage());
        }
     
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
            return view('admin.products.create');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Failed to load create product form: '.$e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try{
            $this->productService->create($request->validated());
            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Failed to create product: ' . $e->getMessage());
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
        try {
            $product = $this->productService->find($id);
            return view('admin.products.create', compact('product'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load edit form: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        try {
            $this->productService->update($request->validated(),$id);
            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->productService->delete($id); 
            return response()->json([ 'success' => true,'message' => 'Product deleted successfully.']);
        } catch (\Exception $e) {
                return response()->json(['success' => false,'message' => 'Failed to delete: ' . $e->getMessage()]);
        }
    }

    public function Status(Request $request){
        $status = $this->productService->StatusUpdate($request->id);
        return response()->json([
        'success' => true,
        'status'  => $status
    ]);
    }
}
