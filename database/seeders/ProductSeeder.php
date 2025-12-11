<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product; // Pastikan Anda mengimpor model Product

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan data produk awal ke dalam tabel 'products'
        Product::updateOrCreate(['name' => 'Kopi Hitam'], [
            'price' => 8000,
            'hpp' => 3000
        ]);

        Product::updateOrCreate(['name' => 'Kopi Susu'], [
            'price' => 10000,
            'hpp' => 4000
        ]);

        Product::updateOrCreate(['name' => 'Kopi Gula Aren'], [
            'price' => 12000,
            'hpp' => 5000
        ]);

        Product::updateOrCreate(['name' => 'Teh Manis'], [
            'price' => 7000,
            'hpp' => 2500
        ]);

        Product::updateOrCreate(['name' => 'Coklat Panas'], [
            'price' => 12000,
            'hpp' => 5000
        ]);
    }
}
