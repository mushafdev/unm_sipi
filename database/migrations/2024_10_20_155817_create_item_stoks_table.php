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
        Schema::create('item_stoks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('gudang_id')->index();
            $table->foreign('gudang_id')->references('id')->on('gudangs')->onUpdate('cascade')->onDelete('restrict');
            $table->decimal('stok', 10, 2)->default(0);
            $table->string('no_batch')->nullable()->index();
            $table->date('tgl_kadaluarsa')->nullable()->index();
            $table->dateTime('waktu_masuk')->nullable();
            $table->decimal('hpp', total: 12, places: 2)->default(0);
            $table->decimal('total', total: 12, places: 2)->default(0);
            $table->enum('is_active', ['Y', 'N'])->default('Y')->comment('Y = Aktif, N = Tidak Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_stoks');
    }
};
