<?php
// ===================================================================
// File 2: database/migrations/...create_transactions_table.php
// ===================================================================
// Tujuan: Membuat tabel `transactions` untuk mencatat setiap penjualan
//         dan menghubungkannya ke tabel `products`.
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // Kolom ID unik untuk setiap transaksi (Primary Key)

            // Kolom ini menghubungkan ke tabel 'products'
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->integer('quantity'); // Kolom untuk jumlah produk terjual
            $table->decimal('total_price', 10, 2); // Kolom untuk total harga
            $table->decimal('total_hpp', 10, 2); // Kolom untuk total modal
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
