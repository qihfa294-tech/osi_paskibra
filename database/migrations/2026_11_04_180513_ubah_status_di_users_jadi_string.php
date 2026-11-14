<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Gedein kolom status jadi varchar 20, biar "Non-Aktif" muat
        DB::statement("ALTER TABLE users MODIFY status VARCHAR(20) DEFAULT 'Aktif'");
    }
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY status VARCHAR(20) DEFAULT 'Aktif'");
    }
};