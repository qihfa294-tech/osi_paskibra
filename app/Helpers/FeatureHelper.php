<?php
use App\Models\FeatureFlag;

if (!function_exists('isFeatureEnabled')) {
    function isFeatureEnabled($key) {
        try {
            // Bikin varian biar e_kas = ekas = e-kas kebaca semua
            $variants = array_unique([
                $key,
                str_replace('-', '_', $key),
                str_replace('_', '-', $key),
                str_replace(['-', '_'], '', $key), // ekas
            ]);

            $value = FeatureFlag::whereIn('key', $variants)->value('is_enabled');
            
            // kalau gak ada di DB sama sekali, anggap ON
            return $value !== null ? (bool)$value : true;
            
        } catch (\Exception $e) {
            return true; // kalo tabel belum ada, anggap nyala semua biar gak blank
        }
    }
}