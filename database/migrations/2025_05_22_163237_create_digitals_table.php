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
        Schema::create('digitals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_buku');   // Relasi ke tabel bukus
            $table->string('file_url');
            $table->integer('jumlahHalaman')->nullable(); // Jumlah halaman file
            $table->timestamps();

            // Foreign key
            $table->foreign('id_buku')->references('id')->on('bukus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digitals');
    }
};
