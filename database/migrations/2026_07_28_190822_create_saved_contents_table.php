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
    Schema::create('saved_contents', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke tabel users
        $table->string('module_name'); // Penanda dari modul mana (Misal: 'CS Center', 'Content Studio')
        $table->string('title'); // Judul simpanan (Misal: 'Promo Shopee 10.10')
        $table->longText('content'); // Isi teks hasil AI (Pakai longText agar muat banyak)
        $table->timestamps(); // Mencatat kapan dibuat & diupdate
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_contents');
    }
};
