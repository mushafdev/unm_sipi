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
        Schema::create('closing_kas_trkas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('closing_kas_id');
            $table->foreign('closing_kas_id')->references('id')->on('closing_kas')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('akun_kas_id');
            $table->foreign('akun_kas_id')->references('id')->on('akun_kas')->onUpdate('cascade')->onDelete('restrict');
            $table->decimal('penerimaan_system', 12, 2)->default(0);
            $table->decimal('penerimaan_aktual', 12, 2)->default(0);
            $table->decimal('pengeluaran_system', 12, 2)->default(0);
            $table->decimal('pengeluaran_aktual', 12, 2)->default(0);
            $table->decimal('penerimaan_selisih', 12, 2)->default(0);
            $table->decimal('pengeluaran_selisih', 12, 2)->default(0);
            $table->enum('cancel', ['N', 'Y']);
            $table->string('catatan')->nullable();
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
        Schema::dropIfExists('closing_kas_trkas');
    }
};
