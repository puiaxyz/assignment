<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sales;
use App\Models\Products;
use App\Models\SaleItems;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        $user = User::first();
        $product = Products::first();
        $sale = Sales::create([
            'user_id' => $user->id,
            'invoice_number' => ('ABC' . random_int(10000, 99999)),
            'total_amount' => 500,
            'discount_type' => 'fixed',
            'discount_value' => 0,
            'final_amount' => 500,
            'payment_method' => 'Cash',
        ]);
        SaleItems::create([
            'sales_id' => $sale->id,
            'products_id' => $product->id,
            'quantity' => 2,
            'price_at_sale' => 250.00, // Example price
        ]);
    }
}
