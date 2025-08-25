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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            // Bisa sender_id nullable (misal pesan dari volunteer ke anggota, atau sebaliknya)
            $table->unsignedBigInteger('sender_id')->nullable();
            
            // Bisa juga receiver_id nullable kalau nanti diperlukan (opsional)
            $table->unsignedBigInteger('receiver_id')->nullable();
            
            // Tambahkan tipe pengirim agar bisa bedakan anggota atau volunteer
            $table->enum('sender_type', ['anggota', 'volunteer'])->default('anggota');

            $table->text('message');

            $table->timestamps();

            // Index untuk pencarian cepat
            $table->index('sender_id');
            $table->index('receiver_id');

            // Optional foreign keys (pastikan tabel dan kolom sesuai)
            // $table->foreign('sender_id')->references('id')->on('anggota')->onDelete('cascade');
            // $table->foreign('receiver_id')->references('id')->on('volunteers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
