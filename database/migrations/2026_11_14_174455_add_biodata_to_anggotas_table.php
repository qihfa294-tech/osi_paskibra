<?php
// database/migrations/2026_11_15_000000_add_biodata_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nama_lengkap')) {
                $table->string('nama_lengkap', 100)->nullable();
            }
            if (!Schema::hasColumn('users', 'nama_panggilan')) {
                $table->string('nama_panggilan', 50)->nullable();
            }
            if (!Schema::hasColumn('users', 'jurusan')) {
                $table->string('jurusan', 20)->nullable();
            }
            if (!Schema::hasColumn('users', 'kelas')) {
                $table->string('kelas', 20)->nullable();
            }
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->text('alamat')->nullable();
            }
            if (!Schema::hasColumn('users', 'tempat_lahir')) {
                $table->string('tempat_lahir', 100)->nullable();
            }
            if (!Schema::hasColumn('users', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable();
            }
            if (!Schema::hasColumn('users', 'no_hp')) {
                $table->string('no_hp', 20)->nullable();
            }
            if (!Schema::hasColumn('users', 'agama')) {
                $table->string('agama', 20)->nullable();
            }
            if (!Schema::hasColumn('users', 'jenis_kelamin')) {
                $table->string('jenis_kelamin', 20)->nullable();
            }
            if (!Schema::hasColumn('users', 'foto')) {
                $table->string('foto')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['nama_lengkap', 'nama_panggilan', 'jurusan', 'kelas', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'no_hp', 'agama', 'jenis_kelamin', 'foto'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};