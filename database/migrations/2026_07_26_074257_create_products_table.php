<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->integer('modal_bahan')->default(0);
            $table->integer('biaya_kemasan')->default(0);
            $table->integer('biaya_operasional')->default(0);
            $table->decimal('admin_fee_persen', 5, 2)->default(0); // Persentase potongan platform (misal: 4.5%)
            $table->integer('harga_jual')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
