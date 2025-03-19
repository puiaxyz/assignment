<?php

namespace Database\Seeders;

use App\Models\Products;
use App\Models\Stocks;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $products = Products::all();

        foreach ($products as $product) {
            Stocks::create([
                'products_id' => $product->id,
                'price' => rand(100, 500),
                'quantity' => rand(5, 20),

            ]);
        }
    }
}
