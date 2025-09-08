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
        Schema::create('item_histories', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_table',30)->nullable();// nama table
            $table->string('jenis_text',30)->nullable();// nama transaksi
            $table->enum('type',['masuk','keluar','reset'])->nullable(); // jenis transaksi : masuk, keluar, reset
            $table->string('no_referensi',30);
            $table->dateTime('waktu',$precision = 0);
            $table->unsignedBigInteger('transaksi_id')->nullable();
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudangs')->onUpdate('cascade')->onDelete('restrict');
            $table->string('no_batch')->nullable()->index();
            $table->date('tgl_kadaluarsa')->nullable();
            $table->decimal('qty', 10, 2)->default(0);
            $table->decimal('selisih', 10, 2)->default(0);
            $table->decimal('hpp', total: 12, places: 2)->default(0);
            $table->decimal('total', total: 12, places: 2)->default(0);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_histories');
    }
};
