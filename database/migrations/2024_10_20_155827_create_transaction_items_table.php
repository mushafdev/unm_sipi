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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade')->onDelete('restrict');
            $table->decimal('qty', 10, 2)->default(0);
            $table->decimal('hpp', total: 12, places: 2)->default(0);
            $table->decimal('harga_jual', total: 12, places: 2)->default(0);
            $table->decimal('harga_jual_ppn', total: 12, places: 2)->default(0);
            $table->tinyInteger('diskon')->default(0);
            $table->decimal('diskon_rp', total: 12, places: 2)->default(0);
            $table->decimal('total_diskon', total: 12, places: 2)->default(0);
            $table->decimal('harga_diskon', total: 12, places: 2)->default(0);
            $table->tinyInteger('ppn')->default(0);
            $table->decimal('ppn_rp', total: 12, places: 2)->default(0);
            $table->decimal('sub_total', total: 12, places: 2)->default(0);
            $table->unsignedBigInteger('item_stok_id')->nullable();
            $table->foreign('item_stok_id')->references('id')->on('item_stoks')->onUpdate('cascade')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
