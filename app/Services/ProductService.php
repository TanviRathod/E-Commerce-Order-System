<?php

namespace App\Services;
use App\Models\Product;

class ProductService{

    public function list(){
        return Product::orderBy('id', 'desc')->paginate(10);
    }

    public function StatusUpdate($id){
        $product = Product::findOrFail($id);
        $product->status = ($product->status === 'active') ? 'inactive' : 'active';
        $product->save();
        return $product->status;
    }

    public function create($data){
        return Product::create($data);
    }

    public function find($id){
        return Product::findOrFail($id);
    }

    public function update($data,$id){
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function delete($id){
        $product = Product::findOrFail($id);
        return $product->delete();
        
    }
}