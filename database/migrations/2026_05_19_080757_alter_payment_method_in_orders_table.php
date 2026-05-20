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
        Schema::table('orders', function (Blueprint $table) {
            // Mengubah tipe data kolom payment_method menjadi VARCHAR(50) agar mendukung 'free_pass'
            // NULLABLE disetel sesuai dengan kondisi database kamu saat ini
            $table->string('payment_method', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kembalikan ke format awal jika dilakukan rollback (sesuaikan jika awalnya ENUM)
            $table->string('payment_method', 255)->nullable()->change();
        });
    }
};