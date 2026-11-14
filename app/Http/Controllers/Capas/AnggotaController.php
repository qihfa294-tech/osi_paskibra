<?php
namespace App\Http\Controllers\Capas;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AnggotaController extends Controller
{
    public function index(Request $r)
    {
        $me = Auth::user();

        // --- AUTO-FIX: user baru langsung punya data Anggota ---
        $anggotaSaya = Anggota::where('user_id', $me->id)->first();
        if (!$anggotaSaya && Schema::hasTable('anggotas')) {
            try {
                $anggotaSaya = Anggota::create([
                    'user_id' => $me->id,
                    'nama' => $me->nama_lengkap ?? $me->name ?? $me->username,
                    'nama_lengkap' => $me->nama_lengkap ?? $me->name ?? $me->username,
                    'angkatan' => $me->angkatan_masuk ?? now()->year,
                    'status' => 'Aktif',
                ]);
            } catch (\Exception $e) {}
        }
        if (empty($me->angkatan_masuk) && $anggotaSaya?->angkatan) {
            $me->angkatan_masuk = $anggotaSaya->angkatan;
            $me->save();
        }

        $angkatanSaya = $me->angkatan_masuk ?? now()->year;
        $angkatanFix = $angkatanSaya;
        $namaLengkap = $anggotaSaya?->nama_lengkap ?? $anggotaSaya?->nama ?? $me->username;

        // --- NORMALISASI FILTER: "" dianggap "semua" ---
        $search = trim($r->get('search', '') ?? '');

        $filterAngkatan = $r->get('angkatan', $angkatanSaya);
        if ($filterAngkatan === '' || $filterAngkatan === null) $filterAngkatan = 'semua';

        $filterStatus = $r->get('status', 'semua');
        if ($filterStatus === '' || $filterStatus === null) $filterStatus = 'semua';

        // List angkatan dari SEMUA role, bukan cuma CAPAS
        $listAngkatan = User::whereIn('role_id', [2,3,5])
            ->whereNotNull('angkatan_masuk')
            ->distinct()->pluck('angkatan_masuk')->filter()->sort()->reverse()->values();
        if ($listAngkatan->isEmpty()) {
            $listAngkatan = Anggota::distinct()->pluck('angkatan')->filter()->sort()->reverse()->values();
        }

        // --- HELPER: ambil user per role ---
        $getByRole = function($roleId) use ($filterAngkatan, $search, $filterStatus, $me) {
            $q = User::where('role_id', $roleId);

            if ($filterAngkatan && $filterAngkatan != 'semua') {
                $idsDariAnggota = Anggota::where('angkatan', $filterAngkatan)->pluck('user_id');
                $q->where(function($qq) use ($roleId, $filterAngkatan, $idsDariAnggota) {
                    $qq->where('role_id', $roleId)->where('angkatan_masuk', $filterAngkatan);
                    if ($idsDariAnggota->count()) {
                        $qq->orWhere(function($q2) use ($roleId, $idsDariAnggota) {
                            $q2->where('role_id', $roleId)->whereIn('id', $idsDariAnggota);
                        });
                    }
                });
            }

            $users = $q->orderBy('username')->get();
            $map = Anggota::whereIn('user_id', $users->pluck('id'))->get()->keyBy('user_id');

            // ID buat search by nama di tabel anggotas
            $idsSearch = collect();
            if ($search !== '') {
                $idsSearch = Anggota::where('nama','like','%'.$search.'%')
                    ->orWhere('nama_lengkap','like','%'.$search.'%')
                    ->pluck('user_id');
            }

            return $users->map(function($u) use ($map, $me) {
                $a = $map->get($u->id);
                $nama = $a?->nama_lengkap ?? $a?->nama ?? $u->username;
                $status = $a?->status ?? 'Aktif';
                // CAPAS CUMA BISA LIAT JABATAN SENDIRI
                $isMe = $u->id === $me->id;
                $jabatan = $status === 'Non-Aktif' ? '-' : ($isMe ? ($u->jabatan ?? $a?->jabatan ?? 'Anggota') : '-');
                return (object)[
                    'id' => $u->id, 'user_id' => $u->id,
                    'nama' => $nama, 'nama_lengkap' => $nama,
                    'username' => $u->username,
                    'angkatan' => $u->angkatan_masuk ?? $a?->angkatan ?? '-',
                    'angkatan_masuk' => $u->angkatan_masuk ?? $a?->angkatan ?? '-',
                    'jabatan' => $jabatan, 'status' => $status, 'is_me' => $isMe,
                    'foto' => $a?->foto ?? $u->foto,
                    'user' => $u,
                ];
            })->filter(function($item) use ($search, $filterStatus, $idsSearch) {
                if ($filterStatus != 'semua' && strtolower(trim($item->status)) != strtolower(trim($filterStatus))) return false;
                if ($search !== '') {
                    $cocokNama = str_contains(strtolower($item->nama.' '.$item->username), strtolower($search));
                    $cocokId = $idsSearch->contains($item->user_id);
                    if (!$cocokNama && !$cocokId) return false;
                }
                return true;
            })->values();
        };

        $capasList = $getByRole(5);
        $pplList = $getByRole(3);
        $ppsList = $getByRole(2);

        // Biar blade lama + blade baru dua-duanya jalan
        $anggotas = $capasList;
        $anggotaList = $capasList;
        $capas = $capasList;
        $ppl = $pplList;
        $pps = $ppsList;

        return view('capas.anggota', compact(
            'anggotas','anggotaList','capas','ppl','pps',
            'capasList','pplList','ppsList',
            'listAngkatan','filterAngkatan','filterStatus','search',
            'namaLengkap','angkatanSaya','angkatanFix'
        ));
    }
}