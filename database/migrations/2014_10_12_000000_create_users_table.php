<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('angkatan_masuk')->nullable();
            $table->string('password');
            
            // ROLE LAMA LU TETEP GW PERTAHANIN
            $table->unsignedBigInteger('role_id')->default(5); // 1=dev, 2=ppl, 3=pps, 4=alumni, 5=capas
            
            // FIX: JANGAN PAKE constrained() DISINI
            // Tabel anggotas belum ada pas migration ini jalan, makanya errno 150
            // Cukup simpen id nya aja, relasi di Model tetep jalan
            $table->unsignedBigInteger('anggota_id')->nullable();
            
            // STATUS - GW BIKIN ENUM BIAR RAPI
            // active = bisa login full, alumni = view only, inactive = gak bisa login
            $table->enum('status', ['active','alumni','inactive'])->default('active');
            
            $table->boolean('must_change_password')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};