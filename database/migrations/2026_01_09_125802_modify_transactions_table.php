<?php

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
        Schema::table('transactions', function (Blueprint $table) {

            // 1. Cek dulu: Jika kolom 'name' BELUM ada, baru buat.
            if (!Schema::hasColumn('transactions', 'name')) {
                $table->string('name')->nullable()->after('id');
            }

            // 2. Cek dulu: Jika kolom 'payment_type' BELUM ada, baru buat.
            if (!Schema::hasColumn('transactions', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('status');
            }

            // 3. Ubah product_id jadi BOLEH KOSONG (nullable)
            // Function ->change() aman dijalankan berulang kali
            $table->unsignedBigInteger('product_id')->nullable()->change();

            // 4. Ubah quantity agar default-nya 1
            $table->integer('quantity')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Rollback logika (opsional, hati-hati menghapus data)
            if (Schema::hasColumn('transactions', 'name')) {
                $table->dropColumn('name');
            }
            // Kita tidak hapus payment_type karena ternyata sudah ada sebelumnya

            $table->unsignedBigInteger('product_id')->nullable(false)->change();
        });
    }
};
