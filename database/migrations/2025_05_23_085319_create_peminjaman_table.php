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

        Schema::create('peminjamans', function (Blueprint $table) {
            
            $table->id();
            $table->unsignedBigInteger('id_anggota');
            $table->unsignedBigInteger('id_buku');
            $table->unsignedBigInteger('id_detail_buku')->nullable(); // ⬅️ Tambahkan kolom ini
            $table->unsignedBigInteger('id_voluntter_pinjam')->nullable();    // volunteer saat menyerahkan buku
            $table->unsignedBigInteger('id_voluntter_kembali')->nullable();   // volunteer saat menerima kembali buku


            $table->date('tanggal_pinjam');
            $table->date('tanggal_ambil')->nullable();
            $table->date('tanggal_pengembalian');

            $table->enum('status_pengembalian', [
                'Belum Diambil', 'Dipinjam', 'Kembalikan', 'Terlambat', 'Tolak'
            ]);

            $table->enum('status_kondisi', [
                'Baik', 'Rusak', 'Hilang'
            ])->default('Baik');

            $table->integer('denda')->default(0);

            $table->tinyInteger('rating')->nullable(); // nilai dari 1 sampai 5
            $table->text('ulasan')->nullable(); // komentar opsional

            $table->timestamps();

            $table->foreign('id_anggota')->references('id')->on('anggotas')->onDelete('cascade');
            $table->foreign('id_buku')->references('id')->on('bukus')->onDelete('cascade');
            $table->foreign('id_detail_buku')->references('id')->on('detailbukus')->onDelete('set null'); // ⬅️ Foreign key-nya

            $table->foreign('id_voluntter_pinjam')->references('id')->on('voluntters')->onDelete('set null');
            $table->foreign('id_voluntter_kembali')->references('id')->on('voluntters')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
