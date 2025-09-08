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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_item_id');
            $table->foreign('kategori_item_id')->references('id')->on('kategori_items')->onUpdate('cascade')->onDelete('cascade');
            $table->string('kode')->nullable();
            $table->string('nama_item');
            $table->string('barcode')->nullable();
            $table->string('besaran',15);
            $table->float('isi', precision: 2);
            $table->string('satuan',15);
            $table->decimal('hpp', total: 12, places: 2)->default(0);
            $table->decimal('hna', total: 12, places: 2)->default(0);
            $table->decimal('ppn', total: 12, places: 2)->default(0);
            $table->decimal('harga_jual', total: 12, places: 2);
            $table->float('reorder_point', precision: 2);
            $table->integer('total_dibeli')->default(0);
            $table->unsignedBigInteger('inserted_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('inserted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('deleted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
