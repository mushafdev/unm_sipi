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
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pasien_id')->nullable();
            $table->unsignedBigInteger('jenis_layanan_id')->nullable();
            $table->unsignedBigInteger('dokter_id')->nullable();
            $table->unsignedBigInteger('karyawan_id')->nullable();
            $table->foreign('pasien_id')->references('id')->on('pasiens')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('jenis_layanan_id')->references('id')->on('jenis_layanans')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('dokter_id')->references('id')->on('dokters')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('karyawan_id')->references('id')->on('karyawans')->onUpdate('cascade')->onDelete('restrict');
            $table->date('tgl_pendaftaran');
            $table->enum('prioritas',['N','Y'])->default('N');
            $table->enum('status',['Menunggu','Dilayani','Pembayaran','Selesai'])->default('Menunggu');//menunggu, dilayani,pembayaran, selesai
            $table->string('no_antrian')->nullable();
            $table->string('catatan')->nullable();
            $table->enum('cancel',['N','Y'])->default('N');
            $table->dateTime('mulai_dirawat')->nullable();
            $table->unsignedBigInteger('called_by')->nullable();
            $table->foreign('called_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('bed_id')->nullable();
            $table->foreign('bed_id')->references('id')->on('beds')->onUpdate('cascade')->onDelete('restrict');
            $table->enum('old',['N','Y'])->default('N');
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
        Schema::dropIfExists('pendaftarans');
    }
};
