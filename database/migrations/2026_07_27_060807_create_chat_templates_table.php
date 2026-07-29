<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title'); // Contoh: "Balasan Tanya Ongkir"
            $table->string('category'); // Kategori: Balasan Chat, Follow-up, Komplain, Broadcast
            $table->text('content'); // Isi pesan template-nya
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_templates');
    }
};
