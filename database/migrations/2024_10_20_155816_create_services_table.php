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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_service_id');
            $table->foreign('kategori_service_id')->references('id')->on('kategori_services')->onUpdate('cascade')->onDelete('cascade');
            $table->string('kode')->nullable();
            $table->string('nama_service');
            $table->string('barcode')->nullable();
            $table->string('besaran',15)->nullable();
            $table->float('isi', precision: 2)->nullable();
            $table->string('satuan',15)->nullable();
            $table->decimal('ppn', total: 12, places: 2)->default(0);
            $table->decimal('harga_jual', total: 12, places: 2);
            $table->integer('durasi')->default(0);
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
        Schema::dropIfExists('services');
    }
};
