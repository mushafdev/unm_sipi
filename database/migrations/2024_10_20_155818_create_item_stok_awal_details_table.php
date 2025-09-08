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
        Schema::create('item_stok_awal_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_stok_awal_id');
            $table->foreign('item_stok_awal_id')->references('id')->on('item_stok_awals')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade')->onDelete('restrict');
            $table->decimal('stok_awal', 10, 2)->default(0);
            $table->string('no_batch')->nullable()->index();
            $table->date('tgl_kadaluarsa')->nullable()->index();
            $table->dateTime('waktu_masuk')->nullable();
            $table->decimal('hpp', total: 12, places: 2)->default(0);
            $table->decimal('total', total: 12, places: 2)->default(0);
            $table->unsignedBigInteger('inserted_by')->nullable();
            $table->foreign('inserted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_stok_awal_details');
    }
};
