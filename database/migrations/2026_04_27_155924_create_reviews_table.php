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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ulasan dengan id user yang login
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Menghubungkan ulasan dengan id event/konser yang dibeli
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            // Kolom rating (angka 1 sampai 5)
            $table->integer('rating');
            // Kolom teks komentar ulasan dari user
            $table->text('comment'); 
            // Status untuk menampilkan/menyembunyikan ulasan ('show' atau 'hide')
            $table->string('status')->default('show'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};