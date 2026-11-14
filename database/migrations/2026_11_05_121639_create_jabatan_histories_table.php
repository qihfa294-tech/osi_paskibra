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
        Schema::create('jabatan_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('kategori_lama')->nullable();
            $table->string('jabatan_lama')->nullable();
            $table->string('kategori_baru')->nullable();
            $table->string('jabatan_baru')->nullable();
            $table->string('keterangan')->nullable(); // misal: Diubah oleh Developer / PPS / Naik jabatan
            $table->foreignId('changed_by')->nullable()->constrained('users')->onDelete('set null'); // siapa yang ngedit
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan_histories');
    }
};