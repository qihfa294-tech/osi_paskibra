<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();
            
            // NYAMBUNG KE USER LOGIN
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('nama');
            $table->string('angkatan'); // 2025
            
            // KATEGORI UTAMA - CAPAS / PPL / PPS
            $table->enum('kategori', ['CAPAS','PPL','PPS'])->default('CAPAS');
            
            // JABATAN DI DALAM KATEGORI - Koordinator, Sekretaris, Bendahara, PH, Binlat, Anggota
            $table->string('jabatan')->default('Anggota');
            
            // STATUS UNTUK ALUMNI - ini kunci buat view only
            $table->enum('status', ['aktif','alumni','nonaktif'])->default('aktif');
            
            $table->string('foto')->nullable();
            $table->timestamps();

            // index biar query kategori cepet
            $table->index(['kategori','status']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('anggotas');
    }
};