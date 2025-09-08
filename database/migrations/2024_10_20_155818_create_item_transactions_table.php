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
        Schema::create('item_transactions', function (Blueprint $table) {
            $table->id();

            $table->string('no_transaksi')->uniqid();
            $table->string('no_referensi')->nullable();
            $table->dateTime('waktu')->nullable();
            $table->unsignedBigInteger('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudangs')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('gudang_tujuan_id')->nullable();
            $table->foreign('gudang_tujuan_id')->references('id')->on('gudangs')->onUpdate('cascade')->onDelete('restrict');
            $table->enum('jenis_transaksi', ['Masuk', 'Keluar','Transfer'])->index();
            $table->string('penanggung_jawab')->nullable();
            $table->decimal('grand_total', total: 12, places: 2)->default(0);
            $table->enum('cancel', ['N', 'Y'])->default('N');
            $table->string('catatan')->nullable();
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
        Schema::dropIfExists('item_transactions');
    }
};
