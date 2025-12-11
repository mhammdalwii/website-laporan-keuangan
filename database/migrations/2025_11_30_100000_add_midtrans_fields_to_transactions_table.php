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
            // Menambahkan kolom untuk Midtrans
            if (!Schema::hasColumn('transactions', 'order_id')) {
                $table->string('order_id')->unique()->after('id');
            }
            if (!Schema::hasColumn('transactions', 'status')) {
                $table->string('status')->default('pending')->after('total_hpp');
            }
            if (!Schema::hasColumn('transactions', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('status');
            }
            if (!Schema::hasColumn('transactions', 'payment_response')) {
                $table->text('payment_response')->nullable()->after('payment_type');
            }

            // Mengubah kolom yang ada agar bisa null, karena callback mungkin tidak berisi info ini
            if (Schema::hasColumn('transactions', 'product_id')) {
                $table->foreignId('product_id')->nullable()->change();
            }
            if (Schema::hasColumn('transactions', 'quantity')) {
                $table->integer('quantity')->nullable()->change();
            }
            if (Schema::hasColumn('transactions', 'total_price')) {
                $table->decimal('total_price', 10, 2)->nullable()->change();
            }
            if (Schema::hasColumn('transactions', 'total_hpp')) {
                $table->decimal('total_hpp', 10, 2)->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $columnsToDrop = ['order_id', 'status', 'payment_type', 'payment_response'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('transactions', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Mengembalikan kolom ke kondisi semula (jika diperlukan)
            if (Schema::hasColumn('transactions', 'product_id')) {
                $table->foreignId('product_id')->nullable(false)->change();
            }
            if (Schema::hasColumn('transactions', 'quantity')) {
                $table->integer('quantity')->nullable(false)->change();
            }
            if (Schema::hasColumn('transactions', 'total_price')) {
                $table->decimal('total_price', 10, 2)->nullable(false)->change();
            }
            if (Schema::hasColumn('transactions', 'total_hpp')) {
                $table->decimal('total_hpp', 10, 2)->nullable(false)->change();
            }
        });
    }
};
