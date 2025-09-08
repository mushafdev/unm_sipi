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
        Schema::create('item_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_transaction_id');
            $table->foreign('item_transaction_id')->references('id')->on('item_transactions')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade')->onDelete('restrict');
            $table->date('tgl_kadaluarsa')->nullable();
            $table->decimal('qty', 10, 2)->default(0);
            $table->decimal('hpp', total: 12, places: 2)->default(0);
            $table->decimal('total', total: 12, places: 2)->default(0);
            $table->string('no_batch')->nullable()->index();
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
        Schema::dropIfExists('item_transaction_details');
    }
};
