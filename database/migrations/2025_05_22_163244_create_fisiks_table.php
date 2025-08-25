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
        Schema::create('fisiks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_buku'); // Nama kolom yang kamu minta
            $table->integer('stok');
            $table->timestamps();

            // Foreign key (disarankan)
            $table->foreign('id_buku')->references('id')->on('bukus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fisiks');
    }
};
