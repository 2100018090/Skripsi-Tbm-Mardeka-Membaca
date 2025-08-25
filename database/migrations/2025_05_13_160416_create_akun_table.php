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
        Schema::create('akuns', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('username', 15);
            $table->string('password', 60);
            $table->enum('role', ['admin', 'anggota', 'voluntter']);
            $table->string('session_token')->nullable();
            $table->string('email_verification_token')->nullable(); // ✅ Token verifikasi email
            $table->timestamp('email_verified_at')->nullable();     // ✅ Waktu verifikasi

            // ✅ Tambahan untuk reset password
            $table->string('reset_password_token')->nullable();       // Token reset password
            $table->timestamp('reset_password_expires_at')->nullable(); // Waktu kedaluwarsa token

            // ✅ Tambahan untuk gambar identitas
            $table->string('img_identitas')->nullable(); // Menyimpan path/nama file

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akuns');
    }
};
