<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product; 
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = ['active', 'inactive'];
          for ($i = 1; $i <= 10; $i++) {
            Product::create([
                'name'  => 'Product ' . $i,
                'sku'   => 'SKU' . Str::upper(Str::random(5)) . $i,
                'price' => rand(100, 1000), 
                'stock' => rand(10, 50),
                 'status'=> $status[array_rand($status)],
            ]);
        }   
    }
}
