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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->string('no_transaksi')->uniqid();
            $table->unsignedBigInteger('pendaftaran_id')->nullable();
            $table->foreign('pendaftaran_id')->references('id')->on('pendaftarans')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('sumber_penjualan_id')->nullable();
            $table->foreign('sumber_penjualan_id')->references('id')->on('sumber_penjualans')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('pasien_id')->nullable();
            $table->foreign('pasien_id')->references('id')->on('pasiens')->onUpdate('cascade')->onDelete('restrict');
            
            $table->dateTime('waktu')->nullable();
            $table->decimal('sub_total', total: 12, places: 2)->default(0);
            $table->tinyInteger('diskon')->default(0);
            $table->decimal('diskon_rp', total: 12, places: 2)->default(0);
            $table->decimal('total', total: 12, places: 2)->default(0);
            $table->decimal('ppn', total: 12, places: 2)->default(0);
            $table->decimal('grand_total', total: 12, places: 2)->default(0);
            $table->decimal('bayar', total: 12, places: 2)->default(0);
            $table->decimal('sisa', total: 12, places: 2)->default(0);
            $table->decimal('kembalian', total: 12, places: 2)->default(0);
            $table->enum('cancel', ['N', 'Y'])->default('N');
            $table->enum('terkunci', ['N', 'Y'])->default('N');
            $table->unsignedBigInteger('closing_kas_id')->nullable();
            $table->foreign('closing_kas_id')->references('id')->on('closing_kas')->onUpdate('cascade')->onDelete('restrict');
            
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
        Schema::dropIfExists('transactions');
    }
};
