<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Models\FeatureFlag;

class CheckFeature
{
    public function handle(Request $request, Closure $next, $key)
    {
        // 1. Normalisasi key yang dari route: IDC -> idc, E-KAS -> e_kas, Kelola Anggota -> kelola_anggota
        $raw = strtolower(str_replace(['-', ' '], '_', trim($key)));
        $alias = [
            'id_card' => 'idc',
            'idcard' => 'idc',
            'kelolaanggota' => 'kelola_anggota',
            'kelola_anggota' => 'kelola_anggota',
            'e_kas' => 'e_kas',
            'ekas' => 'e_kas',
            'kas' => 'e_kas',
            'e_inventaris' => 'e_inventaris',
            'einventaris' => 'e_inventaris',
            'inventaris' => 'e_inventaris',
        ];
        $keyNorm = $alias[$raw] ?? $raw;

        // 2. Cari flag nya pake cara yang gak kaku: key persis, key lower, ATAU name nya
        // Biar kalo di DB lu key nya 'Kelola Anggota' / 'KELOLA_ANGGOTA' tetep ketemu
        $flag = FeatureFlag::where('key', $keyNorm)
            ->orWhere('key', $key)
            ->orWhere('key', $raw)
            ->orWhereRaw('LOWER(`key`) = ?', [$keyNorm])
            ->orWhereRaw('LOWER(REPLACE(REPLACE(`key`, "-", "_"), " ", "_")) = ?', [$keyNorm])
            ->first();

        // 3. Kalo tetep gak ketemu, coba cari by kolom name (soalnya seeder lu pake name)
        if (!$flag) {
            $flag = FeatureFlag::whereRaw('LOWER(REPLACE(REPLACE(`name`, "-", "_"), " ", "_")) = ?', [$keyNorm])->first();
        }

        // 4. Kalo udah dicari pake 6 cara masih gak ketemu, baru losin (anggap ON)
        // Tapi jangan diem-diem, log biar ketauan
        if (!$flag) {
            \Log::warning('CheckFeature: flag tidak ketemu untuk key: '.$key.' (norm: '.$keyNorm.')');
            return $next($request);
        }

        // 5. Baca is_enabled, kalo null baru baca enabled
        $enabled = $flag->is_enabled ?? $flag->enabled ?? true;

        if (!(bool)$enabled) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Fitur dikunci oleh Developer'], 403);
            }
            // Biar gak dapet halaman putih 403 jelek, balikin ke dashboard + pesan
            $role = (int) (auth()->user()->role_id ?? 0);
            $back = match($role){
                2 => route('pps.dashboard'),
                3 => route('ppl.dashboard'),
                4 => route('alumni.dashboard'),
                5 => route('capas.dashboard'),
                default => route('dashboard'),
            };
            return redirect($back)->with('error', 'Fitur '.$keyNorm.' dikunci oleh Developer.');
        }

        return $next($request);
    }
}