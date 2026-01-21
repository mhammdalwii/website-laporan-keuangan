<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('google_id')->nullable()->after('email');
        $table->string('avatar')->nullable()->after('google_id'); // Opsional, buat foto profil
        $table->string('password')->nullable()->change(); // Password jadi boleh kosong (karena login via google)
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['google_id', 'avatar']);
        $table->string('password')->nullable(false)->change();
    });
}
};
