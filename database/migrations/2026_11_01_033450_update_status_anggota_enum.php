<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // STEP 1: Ubah jadi VARCHAR dulu biar bebas case
        Schema::table('anggotas', function (Blueprint $table) {
            $table->string('status_temp')->nullable()->after('status');
        });
        
        // Copy data lama ke temp
        DB::statement("UPDATE anggotas SET status_temp = status");
        
        // Hapus kolom enum lama, ganti jadi string
        DB::statement("ALTER TABLE anggotas MODIFY COLUMN status VARCHAR(20)");

        // STEP 2: Update datanya sekarang bebas
        DB::table('anggotas')->where('status', 'aktif')->update(['status' => 'Aktif']);
        DB::table('anggotas')->where('status', 'nonaktif')->update(['status' => 'Non-Aktif']);
        DB::table('anggotas')->where('status', 'alumni')->update(['status' => 'Lulus']);

        // STEP 3: Baru jadiin ENUM baru yang lu mau
        DB::statement("ALTER TABLE anggotas MODIFY COLUMN kategori ENUM('CAPAS','PPL','PPS','ALUMNI','DEVELOPER') DEFAULT 'CAPAS'");
        DB::statement("ALTER TABLE anggotas MODIFY COLUMN status ENUM('Aktif','Non-Aktif','Lulus') DEFAULT 'Aktif'");

        // Hapus temp
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropColumn('status_temp');
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE anggotas MODIFY COLUMN status VARCHAR(20)");
        DB::table('anggotas')->where('status', 'Aktif')->update(['status' => 'aktif']);
        DB::table('anggotas')->where('status', 'Non-Aktif')->update(['status' => 'nonaktif']);
        DB::table('anggotas')->where('status', 'Lulus')->update(['status' => 'alumni']);

        DB::statement("ALTER TABLE anggotas MODIFY COLUMN kategori ENUM('CAPAS','PPL','PPS') DEFAULT 'CAPAS'");
        DB::statement("ALTER TABLE anggotas MODIFY COLUMN status ENUM('aktif','alumni','nonaktif') DEFAULT 'aktif'");
    }
};