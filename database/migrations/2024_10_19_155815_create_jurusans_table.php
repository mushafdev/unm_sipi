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
        Schema::create('jurusans', function (Blueprint $table) {
            $table->id();
            $table->string('jurusan');
            $table->string('pengelola_nama')->nullable();
            $table->string('pengelola_nip')->nullable();
            $table->string('kajur_nama')->nullable();
            $table->string('kajur_nip')->nullable();
            $table->string('sekjur_nama')->nullable();
            $table->string('sekjur_nip')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telp')->nullable();
            $table->string('fax')->nullable();
            $table->string('hp')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('kota')->nullable();
            $table->unsignedBigInteger('fakultas_id')->nullable();
            $table->foreign('fakultas_id')->references('id')->on('fakultas')->onUpdate('cascade')->onDelete('restrict');
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
        Schema::dropIfExists('jurusans');
    }
};
