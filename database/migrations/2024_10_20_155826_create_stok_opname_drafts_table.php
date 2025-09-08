<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stok_opname_drafts', function (Blueprint $table) {
            $table->id();

            // Optional: agar bisa multi user / multi sesi
            $table->unsignedBigInteger('user_id')->index(); // siapa yang sedang input
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('gudang_id')->index(); // lokasi opname
            $table->foreign('gudang_id')->references('id')->on('gudangs')->onUpdate('cascade')->onDelete('restrict');

            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade')->onDelete('restrict');

            $table->unsignedBigInteger('item_stok_id')->nullable();
            $table->foreign('item_stok_id')->references('id')->on('item_stoks')->onUpdate('cascade')->onDelete('set null');

            $table->string('no_batch')->nullable()->index();
            $table->date('tgl_kadaluarsa')->nullable();

            $table->decimal('stok_system', 12, 2)->default(0);
            $table->decimal('stok_fisik', 12, 2)->default(0);
            $table->decimal('selisih', 12, 2)->default(0);

            $table->decimal('hpp', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stok_opname_drafts');
    }
};
