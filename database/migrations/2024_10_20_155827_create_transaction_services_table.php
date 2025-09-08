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
        Schema::create('transaction_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('services')->onUpdate('cascade')->onDelete('restrict');
            $table->decimal('qty', 10, 2)->default(0);
            $table->decimal('hpp', total: 12, places: 2)->default(0);
            $table->decimal('harga_jual', total: 12, places: 2)->default(0);
            $table->decimal('harga_jual_ppn', total: 12, places: 2)->default(0);
            $table->tinyInteger('diskon')->default(0);
            $table->decimal('diskon_rp', total: 12, places: 2)->default(0);
            $table->decimal('total_diskon', total: 12, places: 2)->default(0);
            $table->decimal('harga_diskon', total: 12, places: 2)->default(0);
            $table->tinyInteger('ppn')->default(0);
            $table->decimal('ppn_rp', total: 12, places: 2)->default(0);
            $table->decimal('sub_total', total: 12, places: 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_services');
    }
};
