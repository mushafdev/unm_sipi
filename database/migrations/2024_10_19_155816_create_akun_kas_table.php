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
        Schema::create('akun_kas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_akun',20);
            $table->string('nomor_akun',20);
            $table->string('nomor_rekening',20)->nullable()->comment('Nomor rekening bank, jika akun kas adalah bank');
            $table->string('bank',50)->nullable()->comment('Nama bank, jika akun kas adalah bank');
            $table->decimal('opening_balance', 12, 2)->default(0);
            $table->decimal('current_balance', 12, 2)->default(0);
            $table->enum('is_active',['Y', 'N'])->default('Y')->comment('Y = Aktif, N = Tidak Aktif');
            $table->enum('type',['cash', 'bank'])->default('cash');
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
        Schema::dropIfExists('akun_kas');
    }
};
