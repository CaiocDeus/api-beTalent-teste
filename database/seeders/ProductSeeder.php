<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Mochila', 'amount' => 50.5
        ]);

        Product::create([
            'name' => 'Caderno', 'amount' => 29.99
        ]);

        Product::create([
            'name' => 'Lapis', 'amount' => 3
        ]);

        Product::create([
            'name' => 'Caneta', 'amount' => 5.25
        ]);
    }
}
