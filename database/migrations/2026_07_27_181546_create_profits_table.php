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
    Schema::create('profits', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->decimal('total_hpp', 15, 2);
            $table->decimal('harga_jual', 15, 2);
            $table->decimal('profit_bersih', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profits');
    }
};
