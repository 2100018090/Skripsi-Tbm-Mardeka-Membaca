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
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_akun');
            $table->string('nama', 50);     // VARCHAR(15)
            $table->string('alamat', 50)->nullable(); // VARCHAR(50)
            $table->string('notlp', 15)->nullable();  // VARCHAR(12)
            $table->string('img', 100)->nullable();
            $table->enum('status', ['active', 'offline'])->default('offline');
            $table->enum('akses', ['reguler', 'pending', 'plus'])->default('reguler');
            $table->timestamps();
            $table->foreign('id_akun')->references('id')->on('akuns')->onDelete('cascade');
        });          

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};
