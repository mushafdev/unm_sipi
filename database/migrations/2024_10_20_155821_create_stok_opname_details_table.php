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
        Schema::create('stok_opname_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stok_opname_id');
            $table->foreign('stok_opname_id')->references('id')->on('stok_opnames')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('item_stok_id')->nullable();
            $table->foreign('item_stok_id')->references('id')->on('item_stoks')->onUpdate('cascade')->onDelete('set null');
            $table->string('no_batch')->nullable()->index();
            $table->date('tgl_kadaluarsa')->nullable();
            $table->decimal('stok_fisik', 12, 2)->default(0);
            $table->decimal('stok_system', 12, 2)->default(0);
            $table->decimal('selisih', 12, 2)->default(0);
            $table->decimal('hpp', total: 12, places: 2)->default(0);
            $table->decimal('total', total: 12, places: 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_opname_details');
    }
};
