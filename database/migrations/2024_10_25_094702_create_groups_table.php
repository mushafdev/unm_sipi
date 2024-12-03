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
        Schema::create('groups', function (Blueprint $table) {
          
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id')->nullable();
            $table->unsignedBigInteger('lokasi_pi_id')->nullable();
            $table->unsignedBigInteger('pembimbing_id')->nullable();
            $table->unsignedSmallInteger('start_month');
            $table->unsignedSmallInteger('end_month');
            $table->year('year');
            $table->string('nama_pembimbing_lapangan')->nullable();
            $table->string('no_pembimbing_lapangan')->nullable();
            $table->string('no_surat')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('catatan')->nullable();
            $table->enum('send',['N','Y']);
            $table->enum('admin_verify',['N','Y','X']);
            $table->enum('done',['N','Y']);
            $table->string('catatan_done')->nullable();
            $table->unsignedBigInteger('inserted_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswas')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('lokasi_pi_id')->references('id')->on('lokasi_pis')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('pembimbing_id')->references('id')->on('dosens')->onUpdate('cascade')->onDelete('restrict');
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
        Schema::dropIfExists('groups');
    }
};
