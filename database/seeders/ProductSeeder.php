<?php

namespace Database\Seeders;

use App\Models\Products;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Products::create([
            'name' => 'Pepsi',
            'barcode' => '123123',
            'description' => 'A soft drink',
        ]);
        Products::create([
            'name' => 'Coke',
            'barcode' => '321321',
            'description' => 'A soft drink',
        ]);
        Products::create([
            'name' => 'Fanta',
            'barcode' => '121212',
            'description' => 'A soft drink',
        ]);
    }
}
