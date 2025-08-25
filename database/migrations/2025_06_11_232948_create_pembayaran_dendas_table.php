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
        Schema::create('pembayaran_dendas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_peminjaman');
            $table->integer('jumlah_denda');
            $table->enum('metode_pembayaran', ['Bayar di Tempat', 'QRIS'])->nullable();
            $table->enum('status_pembayaran', ['Belum Dibayar', 'Sudah Dibayar', 'Transaksi Gagal'])->default('Belum Dibayar');
            $table->string('bukti_pembayaran')->nullable(); // ← tambahan bukti
            $table->timestamp('tanggal_pembayaran')->nullable();
            $table->timestamps();

            $table->foreign('id_peminjaman')->references('id')->on('peminjamans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_dendas');
    }
};
