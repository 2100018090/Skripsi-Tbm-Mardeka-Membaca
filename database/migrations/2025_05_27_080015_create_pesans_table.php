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
        Schema::create('pesans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengirim_id');
            $table->unsignedBigInteger('penerima_id')->nullable(); // nullable kalau dikirim ke banyak volunteer
            $table->text('isi');
            $table->timestamps();

            $table->foreign('pengirim_id')->references('id')->on('akuns');
            $table->foreign('penerima_id')->references('id')->on('akuns');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesans');
    }
};
