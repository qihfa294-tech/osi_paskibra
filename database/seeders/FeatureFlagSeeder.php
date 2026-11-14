<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureFlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\FeatureFlag::insert([
        ['key'=>'kelola_anggota','name'=>'Kelola Anggota','is_enabled'=>1, 'created_at'=>now(), 'updated_at'=>now()],
        ['key'=>'e_kas','name'=>'E-KAS','is_enabled'=>1, 'created_at'=>now(), 'updated_at'=>now()],
        ['key'=>'absensi','name'=>'Absensi','is_enabled'=>1, 'created_at'=>now(), 'updated_at'=>now()],
        ['key'=>'e_inventaris','name'=>'E-Inventaris','is_enabled'=>1, 'created_at'=>now(), 'updated_at'=>now()],
        ['key'=>'materi','name'=>'Materi','is_enabled'=>1, 'created_at'=>now(), 'updated_at'=>now()],
    ]);
    }
}
