<?php

// ===================================================================
// File 1: database/migrations/...create_products_table.php
// ===================================================================
// Tujuan: Membuat tabel `products` untuk menyimpan daftar produk.
// ===================================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Kolom ID unik untuk setiap produk (Primary Key)
            $table->string('name'); // Kolom untuk nama produk
            $table->decimal('price', 10, 2); // Kolom untuk harga jual
            $table->decimal('hpp', 10, 2); // Kolom untuk harga pokok (modal)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
