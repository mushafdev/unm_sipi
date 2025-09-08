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
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->string('no_rm')->unique();
            $table->date('tgl_daftar');
            $table->string('nama');
            $table->string('gelar_depan')->nullable();
            $table->string('gelar_belakang')->nullable();
            $table->string('panggilan');
            $table->enum('display_nama', ['l', 'p'])->default('l');
            $table->string('nik')->nullable();
            $table->string('foto')->nullable();
            $table->string('no_hp',20)->nullable();
            $table->string('email')->nullable();
            $table->string('alamat')->nullable();
            $table->date('tgl_lahir');
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->unsignedBigInteger('agama_id')->nullable();
            $table->unsignedBigInteger('pekerjaan_id')->nullable();
            $table->unsignedBigInteger('pendidikan_id')->nullable();
            $table->foreign('agama_id')->references('id')->on('agamas')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('pekerjaan_id')->references('id')->on('pekerjaans')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('pendidikan_id')->references('id')->on('pendidikans')->onUpdate('cascade')->onDelete('restrict');
            $table->string('status_kawin',3)->nullable();
            $table->string('golongan_darah',3)->nullable();
            $table->unsignedBigInteger('prov_id')->nullable();
            $table->unsignedBigInteger('kab_id')->nullable();
            $table->unsignedBigInteger('kec_id')->nullable();
            $table->unsignedBigInteger('kel_id')->nullable();
            $table->foreign('prov_id')->references('id')->on('provinsis')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kab_id')->references('id')->on('kabupatens')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kec_id')->references('id')->on('kecamatans')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kel_id')->references('id')->on('kelurahans')->onUpdate('cascade')->onDelete('restrict');
            $table->string('kode_pos',10)->nullable();
            $table->string('tag')->nullable();
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('pasiens');
    }
};
