<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('kas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('nama_pembayar')->nullable();
            $table->string('role_pembayar')->nullable();
            $table->string('angkatan_pembayar')->nullable();
            $table->bigInteger('nominal');
            $table->string('metode_pembayaran')->default('cash'); // cash, qris, transfer
            $table->string('bukti')->nullable();
            $table->string('bulan')->nullable(); // 2026-11
            $table->string('keterangan')->nullable();
            $table->string('tipe')->default('masuk'); // masuk / keluar
            $table->string('status')->default('pending'); // pending / lunas
            $table->timestamps();
        });
    }
    public function down(){
        Schema::dropIfExists('kas');
    }
};