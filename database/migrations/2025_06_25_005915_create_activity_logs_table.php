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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('aksi'); // contoh: "Login", "Tambah Buku"
            $table->text('deskripsi')->nullable(); // penjelasan aktivitas
            $table->string('ip_address')->nullable(); // IP pengguna
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('akuns')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
