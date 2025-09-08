<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksi_kas', function (Blueprint $table) {
            $table->id();

            $table->string('no_transaksi')->uniqid()->index();
            $table->dateTime('waktu')->index()->comment('Waktu transaksi, bisa diisi manual atau otomatis');
            $table->unsignedBigInteger('kategori_kas_id')->nullable()->index();
            $table->foreign('kategori_kas_id')->references('id')->on('kategori_kas')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('metode_pembayaran_id')->nullable()->index();
            $table->foreign('metode_pembayaran_id')->references('id')->on('metode_pembayarans')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('akun_kas_id')->index();
            $table->foreign('akun_kas_id')->references('id')->on('akun_kas')->onUpdate('cascade')->onDelete('restrict');
            
            $table->enum('type',['income', 'expense'])->default('income')->index()->comment('income = Pemasukan, expense = Pengeluaran');
            $table->string('keterangan')->nullable();
            $table->decimal('jumlah', 12, 2)->default(0);
            $table->enum('cancel', ['N', 'Y']);
            $table->enum('terkunci', ['N', 'Y']);
            $table->unsignedBigInteger('closing_kas_id')->nullable();
            $table->foreign('closing_kas_id')->references('id')->on('closing_kas')->onUpdate('cascade')->onDelete('restrict');
            
            $table->unsignedBigInteger('inserted_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('inserted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('deleted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi_kas');
    }
};
