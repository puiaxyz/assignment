<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Products;
use Illuminate\Database\Seeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin user',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'is_admin' => 1,
        ]);
        User::factory()->create([
            'name' => 'Test user',
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
            'is_admin' => 0,
        ]);

        $this->call([
            ProductSeeder::class,
            StockSeeder::class,
            SalesSeeder::class,
        ]);
    }
}
