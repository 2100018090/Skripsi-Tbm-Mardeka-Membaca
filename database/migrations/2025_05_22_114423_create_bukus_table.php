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
        Schema::create('bukus', function (Blueprint $table) {
            $table->id(); // id sebagai primary key (id_buku)
            $table->unsignedBigInteger('id_kategori'); // Foreign key ke kategori
            $table->string('judul', 100);
            $table->string('penulis', 30);
            $table->string('penerbit', 30);
            $table->date('tahun_terbit');
            $table->enum('tipe', ['digital', 'fisik']);
            $table->string('harga', 10);
            $table->string('isbn', 30); // Tambahan kolom ISBN
            $table->text('deskripsi');
            $table->string('img')->nullable();
            $table->timestamps();

            // Relasi ke tabel kategori
            $table->foreign('id_kategori')->references('id')->on('kategoris')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
