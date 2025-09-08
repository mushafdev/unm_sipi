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
        Schema::create('pendaftaran_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftaran_id');
            $table->foreign('pendaftaran_id')->references('id')->on('pendaftarans')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('services')->onUpdate('cascade')->onDelete('restrict');
            $table->decimal('qty', 10, 2)->default(0);
            $table->decimal('hpp', total: 12, places: 2)->default(0);
            $table->decimal('harga_jual', total: 12, places: 2)->default(0);
            $table->decimal('harga_jual_ppn', total: 12, places: 2)->default(0);
            $table->tinyInteger('diskon')->default(0);
            $table->decimal('diskon_rp', total: 12, places: 2)->default(0);
            $table->decimal('harga_diskon', total: 12, places: 2)->default(0);
            $table->decimal('total_diskon', total: 12, places: 2)->default(0);
            $table->tinyInteger('ppn')->default(0);
            $table->decimal('ppn_rp', total: 12, places: 2)->default(0);
            $table->decimal('sub_total', total: 12, places: 2)->default(0);
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
        Schema::dropIfExists('pendaftaran_services');
    }
};
