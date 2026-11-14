<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Anggota;
use App\Models\Kas;
use App\Models\Absensi;

class CapasController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $anggota = null;
        try {
            if (Schema::hasTable('anggotas')) {
                $anggota = Anggota::where('user_id', $user->id)->first();
            }
        } catch (\Exception $e) { $anggota = null; }
        $namaLengkap = $anggota?->nama_lengkap ?? $anggota?->nama ?? $user->nama_lengkap ?? $user->name ?? $user->username ?? 'CAPAS';
        $angkatanFix = $anggota?->angkatan ?? $user->angkatan_masuk ?? $user->angkatan ?? '2023';
        if(!$anggota){
            $anggota = (object)['foto' => $user->foto ?? null,'nis' => $user->nis ?? $user->email ?? '-','nama' => $namaLengkap];
        }
        try {
            $idsNonAktif = Anggota::where('angkatan', $angkatanFix)->where('status','Non-Aktif')->pluck('user_id');
            $totalCapasSeangkatan = User::where('angkatan_masuk', $angkatanFix)->where('role_id', 5)->whereNotIn('id', $idsNonAktif)->count();
            if($totalCapasSeangkatan == 0){ $totalCapasSeangkatan = 1; }
        } catch (\Exception $e) {
            try { $totalCapasSeangkatan = Anggota::where('angkatan', $angkatanFix)->whereHas('user', fn($q) => $q->where('role_id', 5))->count(); }
            catch (\Exception $e2) { $totalCapasSeangkatan = 1; }
        }
        try {
            if (!Schema::hasTable('kas')) { $statusKas = 'Belum Ada Data'; }
            else {
                $exists = Kas::where('user_id', $user->id)->exists();
                if (!$exists) { $statusKas = 'Belum Ada Data'; }
                else {
                    $lunasBulanIni = Kas::where('user_id', $user->id)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->where('status', 'lunas')->exists();
                    $statusKas = $lunasBulanIni ? 'Lunas' : 'Belum Lunas';
                }
            }
        } catch (\Exception $e) { $statusKas = 'Belum Ada Data'; }
        try {
            if (!Schema::hasTable('absensis')) { $persenAbsen = 0; }
            else {
                $totalJadwalBulanIni = Absensi::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->distinct('tanggal')->count('tanggal');
                if ($totalJadwalBulanIni == 0) { $persenAbsen = 0; }
                else {
                    $hadirSaya = Absensi::where('user_id', $user->id)->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->whereIn('status', ['hadir', 'Hadir', 'HADIR', 'H', '1'])->count();
                    $persenAbsen = round(($hadirSaya / $totalJadwalBulanIni) * 100);
                }
            }
        } catch (\Exception $e) { $persenAbsen = 0; }
        return view('capas.dashboard', compact('namaLengkap','angkatanFix','anggota','totalCapasSeangkatan','statusKas','persenAbsen'));
    }

    public function idc()
    {
        $user = Auth::user();
        $anggota = null;
        try { if (Schema::hasTable('anggotas')) { $anggota = Anggota::where('user_id', $user->id)->first(); } } catch (\Exception $e) { $anggota = null; }
        $namaLengkap = $anggota?->nama_lengkap ?? $anggota?->nama ?? $user->nama_lengkap ?? $user->name ?? $user->username ?? 'CAPAS';
        $angkatanFix = $anggota?->angkatan ?? $user->angkatan_masuk ?? $user->angkatan ?? '2023';
        if(!$anggota){ $anggota = (object)['foto' => $user->foto ?? null,'nis' => $user->nis ?? $user->email ?? '-','nama' => $namaLengkap]; }
        return view('capas.idc', compact('namaLengkap','angkatanFix','anggota'));
    }

    public function updateFotoIdc(Request $request)
    {
        $request->validate(['foto' => 'required|image|mimes:jpg,jpeg,png|max:2048']);
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto_idc', 'public');
            if ($anggota) { $anggota->foto = $path; $anggota->save(); }
            $user->foto = $path; $user->save();
            return response()->json(['success' => true, 'foto_url' => asset('storage/'.$path)]);
        }
        return response()->json(['success' => false], 400);
    }

    public function anggota(Request $r)
    {
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        $angkatanFix = $anggota?->angkatan ?? $user->angkatan_masuk ?? '2023';
        $namaLengkap = $anggota?->nama_lengkap ?? $anggota?->nama ?? $user->username ?? 'CAPAS';
        $search = $r->get('search');
        $filterAngkatan = $r->get('angkatan', $angkatanFix);
        $listAngkatan = User::where('role_id', 5)->distinct()->pluck('angkatan_masuk')->filter()->sort()->reverse()->values();
        $query = User::where('role_id', 5);
        if($filterAngkatan) $query->where('angkatan_masuk', $filterAngkatan);
        if($search){
            $idsDariAnggota = Anggota::where('nama','like','%'.$search.'%')->orWhere('nama_lengkap','like','%'.$search.'%')->pluck('user_id');
            $query->where(function($q) use ($search, $idsDariAnggota){
                $q->where('username','like','%'.$search.'%')->orWhere('angkatan_masuk','like','%'.$search.'%')->orWhereIn('id', $idsDariAnggota);
            });
        }
        $users = $query->orderBy('angkatan_masuk','desc')->orderBy('username')->get();
        $map = Anggota::whereIn('user_id', $users->pluck('id'))->get()->keyBy('user_id');
        $anggotas = $users->map(function($u) use ($map, $user){
            $a = $map->get($u->id);
            $status = $a?->status ?? 'Aktif';
            $isMe = $u->id === $user->id;
            $jabatan = $status === 'Non-Aktif' ? '-' : ($isMe ? ($u->jabatan ?? $a?->jabatan ?? 'Anggota') : '-');
            return (object)[
                'id' => $u->id, 'user_id' => $u->id,
                'nama' => $a?->nama_lengkap ?? $a?->nama ?? $u->username,
                'nama_lengkap' => $a?->nama_lengkap ?? $a?->nama ?? $u->username,
                'username' => $u->username, 'angkatan' => $u->angkatan_masuk, 'angkatan_masuk' => $u->angkatan_masuk,
                'jabatan' => $jabatan, 'status' => $status, 'is_me' => $isMe,
                'foto' => $a?->foto ?? $u->foto, 'user' => $u,
            ];
        });
        $anggotaList = $anggotas;
        return view('capas.anggota', compact('anggotaList','anggotas','namaLengkap','angkatanFix','listAngkatan','filterAngkatan','search'));
    }

    // ===== KAS - TRACK RECORD SEMUA ANGKATAN: PPL + PPS + ALUMNI =====
    // CAPAS cuma liat, gak bisa nambah. Data gak ilang pas naik jadi ALUMNI karena kunci nya user_id + angkatan_masuk, bukan role_id.
    public function kas(Request $r)
    {
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        $namaLengkap = $anggota?->nama_lengkap ?? $anggota?->nama ?? $user->username ?? 'CAPAS';
        $angkatanFix = $anggota?->angkatan ?? $user->angkatan_masuk ?? '2023';

        $filterAngkatan = $r->get('angkatan', 'semua');
        $filterRole = $r->get('role', 'semua');
        $search = $r->get('search');

        $hasKasTable = Schema::hasTable('kas');
        $hasNominal = $hasKasTable && Schema::hasColumn('kas', 'nominal');
        $hasStatus = $hasKasTable && Schema::hasColumn('kas', 'status');
        $hasTipe = $hasKasTable && Schema::hasColumn('kas', 'tipe');
        $kolomTipe = $hasTipe ? 'tipe' : (Schema::hasColumn('kas','jenis') ? 'jenis' : (Schema::hasColumn('kas','kategori') ? 'kategori' : null));
        $hasKolomTipe = $kolomTipe !== null;

        // 1. LIST ANGKATAN DARI PPL+PPS+ALUMNI - BUKAN CAPAS DOANG
        // Kalo angkatan 2020 udah lulus semua jadi ALUMNI, tetep muncul di sini
        $listAngkatan = User::whereIn('role_id', [2,3,4])
            ->whereNotNull('angkatan_masuk')
            ->distinct()->pluck('angkatan_masuk')->filter()->sort()->reverse()->values();

        // 2. REKAP PER ANGKATAN
        $perAngkatan = $listAngkatan->map(function($angkatan) use ($hasKasTable, $hasNominal, $hasStatus, $hasKolomTipe, $kolomTipe) {
            // Kunci anti-hilang: ambil user_id dari role 2,3,4 sekaligus di angkatan yang sama
            $idsAngkatan = User::whereIn('role_id', [2,3,4])->where('angkatan_masuk', $angkatan)->pluck('id');
            $jumlah = User::whereIn('role_id', [2,3])->where('angkatan_masuk', $angkatan)->count();
            $masuk = 0; $keluar = 0;
            if ($hasKasTable && $hasNominal && $idsAngkatan->count()) {
                $q = Kas::whereIn('user_id', $idsAngkatan);
                if ($hasStatus) $q->where('status', 'lunas');
                if ($hasKolomTipe) {
                    $masuk = (clone $q)->whereIn($kolomTipe, ['masuk','pemasukan','in','iuran'])->sum('nominal');
                    $keluar = (clone $q)->whereIn($kolomTipe, ['keluar','pengeluaran','out'])->sum('nominal');
                    $masuk += (clone $q)->whereNull($kolomTipe)->sum('nominal');
                } else {
                    $masuk = (clone $q)->sum('nominal');
                }
            }
            return (object)['angkatan'=>$angkatan,'jumlah'=>$jumlah,'total'=>$masuk-$keluar,'masuk'=>$masuk,'keluar'=>$keluar];
        });

        $perFiltered = $filterAngkatan != 'semua' ? $perAngkatan->where('angkatan', $filterAngkatan) : $perAngkatan;
        $totalMasuk = $perFiltered->sum('masuk');
        $totalKeluar = $perFiltered->sum('keluar');
        $saldo = $totalMasuk - $totalKeluar;

        // 3. TRACK RECORD SEMUA ANGKATAN - INTINYA DI SINI
        $trackRecord = collect();
        if ($hasKasTable) {
            $queryUser = User::whereIn('role_id', [2,3,4]);
            if ($filterAngkatan != 'semua') $queryUser->where('angkatan_masuk', $filterAngkatan);
            if ($filterRole != 'semua') {
                $roleId = $filterRole == 'PPS' ? 2 : ($filterRole == 'PPL' ? 3 : 4);
                $queryUser->where('role_id', $roleId);
            }
            $usersFiltered = $queryUser->get();
            $userMap = $usersFiltered->keyBy('id');
            $userIds = $usersFiltered->pluck('id');
            $anggotaMap = Anggota::whereIn('user_id', $userIds)->get()->keyBy('user_id');

            $q = Kas::whereIn('user_id', $userIds)->orderBy('created_at','desc');
            if ($search) {
                $idsSearch = $anggotaMap->filter(fn($a) => str_contains(strtolower($a->nama_lengkap ?? $a->nama ?? ''), strtolower($search)))->keys();
                $idsUserSearch = $userMap->filter(fn($u) => str_contains(strtolower($u->username ?? ''), strtolower($search)))->keys();
                $q->whereIn('user_id', $idsSearch->merge($idsUserSearch)->unique()->values());
            }
            $trackRecord = $q->limit(100)->get()->map(function($k) use ($userMap, $anggotaMap){
                $u = $userMap->get($k->user_id);
                $a = $anggotaMap->get($k->user_id);
                $k->nama_pembayar = $a?->nama_lengkap ?? $a?->nama ?? $u?->username ?? 'Unknown';
                $k->angkatan_pembayar = $u?->angkatan_masuk ?? '-';
                $k->role_pembayar = $u?->role_id == 2 ? 'PPS' : ($u?->role_id == 3 ? 'PPL' : 'ALUMNI');
                return $k;
            });
        }

        // 4. KAS KAMU SENDIRI (CAPAS) - kecil aja di atas
        $myKas = $hasKasTable ? Kas::where('user_id', $user->id)->latest()->get() : collect();
        $myTotal = $hasNominal ? $myKas->where('status','lunas')->sum('nominal') : 0;
        $myStatus = 'Belum Ada Data';
        if ($myKas->count()) {
            $lunasBulanIni = $myKas->where('status','lunas')->where('created_at','>=', now()->startOfMonth())->count() > 0;
            $myStatus = $lunasBulanIni ? 'Lunas Bulan Ini' : 'Belum Lunas';
            if ($hasStatus && $myKas->where('status','lunas')->count() == $myKas->count()) $myStatus = 'Lunas';
        }
        $listKas = $myKas;

        return view('capas.kas', compact(
            'listKas','namaLengkap','angkatanFix',
            'perAngkatan','listAngkatan','totalMasuk','totalKeluar','saldo',
            'myKas','myTotal','myStatus','trackRecord',
            'filterAngkatan','filterRole','search'
        ));
    }

    public function absensi() {
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        $namaLengkap = $anggota?->nama_lengkap ?? $anggota?->nama ?? $user->username ?? 'CAPAS';
        $angkatanFix = $anggota?->angkatan ?? $user->angkatan_masuk ?? '2023';
        $riwayatAbsen = Schema::hasTable('absensis') ? Absensi::where('user_id', $user->id)->latest()->get() : collect([]);
        return view('capas.absensi', compact('riwayatAbsen','namaLengkap','angkatanFix'));
    }
    public function materi() {
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        $namaLengkap = $anggota?->nama_lengkap ?? $anggota?->nama ?? $user->username ?? 'CAPAS';
        $angkatanFix = $anggota?->angkatan ?? $user->angkatan_masuk ?? '2023';
        return view('capas.materi', compact('namaLengkap','angkatanFix'));
    }
}