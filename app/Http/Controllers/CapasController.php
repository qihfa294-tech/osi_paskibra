<?php
// app/Http/Controllers/CapasController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Anggota;
use App\Models\Kas;
use App\Models\Absensi;

class CapasController extends Controller
{
    private function tierDariTotal($total, $target = 100000)
    {
        if ($target <= 0) $target = 100000;
        $persen = $total / $target * 100;
        if ($persen >= 100) return 'BLACK';
        if ($persen >= 50) return 'GOLD';
        if ($persen >= 25) return 'SILVER';
        return 'BRONZE';
    }

    private function targetKas($user = null)
    {
        return 100000;
    }

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

        if (!$anggota) {
            $anggota = (object)['foto' => $user->foto ?? null, 'nis' => $user->nis ?? $user->email ?? '-', 'nama' => $namaLengkap];
        }

        $totalPps = 0;
        $totalPpl = 0;
        $totalCapasSeangkatan = 1;
        $totalCapas = 1;
        $totalAnggota = 1;

        try {
            $idsNonAktif = Schema::hasTable('anggotas')
                ? Anggota::where('angkatan', $angkatanFix)->where('status', 'Non-Aktif')->pluck('user_id')
                : collect();

            $totalPps = User::where('angkatan_masuk', $angkatanFix)->where('role_id', 2)->whereNotIn('id', $idsNonAktif)->count();
            $totalPpl = User::where('angkatan_masuk', $angkatanFix)->where('role_id', 3)->whereNotIn('id', $idsNonAktif)->count();
            $totalCapasSeangkatan = User::where('angkatan_masuk', $angkatanFix)->where('role_id', 5)->whereNotIn('id', $idsNonAktif)->count();
            if ($totalCapasSeangkatan == 0) { $totalCapasSeangkatan = 1; }
            $totalCapas = $totalCapasSeangkatan;
            $totalAnggota = $totalPps + $totalPpl + $totalCapasSeangkatan;
        } catch (\Exception $e) {
            try {
                $totalCapasSeangkatan = Anggota::where('angkatan', $angkatanFix)->whereHas('user', fn($q) => $q->where('role_id', 5))->count();
                $totalCapas = $totalCapasSeangkatan ?: 1;
                $totalAnggota = $totalPps + $totalPpl + $totalCapas;
            } catch (\Exception $e2) {
                $totalCapasSeangkatan = 1;
                $totalCapas = 1;
                $totalAnggota = 1;
            }
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

        $perluLengkapiBiodata = ProfileController::perluLengkapi($user);

        return view('capas.dashboard', compact('namaLengkap', 'angkatanFix', 'anggota', 'totalCapasSeangkatan', 'totalCapas', 'totalPps', 'totalPpl', 'totalAnggota', 'statusKas', 'persenAbsen', 'perluLengkapiBiodata'));
    }

    public function idc()
    {
        $user = Auth::user();
        $anggota = null;
        try { if (Schema::hasTable('anggotas')) { $anggota = Anggota::where('user_id', $user->id)->first(); } } catch (\Exception $e) { $anggota = null; }
        $namaLengkap = $anggota?->nama_lengkap ?? $anggota?->nama ?? $user->nama_lengkap ?? $user->name ?? $user->username ?? 'CAPAS';
        $angkatanFix = $anggota?->angkatan ?? $user->angkatan_masuk ?? $user->angkatan ?? '2023';
        if (!$anggota) { $anggota = (object)['foto' => $user->foto ?? null, 'nis' => $user->nis ?? $user->email ?? '-', 'nama' => $namaLengkap]; }
        return view('capas.idc', compact('namaLengkap', 'angkatanFix', 'anggota'));
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
            return response()->json(['success' => true, 'foto_url' => asset('storage/' . $path)]);
        }
        return response()->json(['success' => false], 400);
    }

    public function anggota(Request $r)
    {
        $user = Auth::user();

        $anggota = Anggota::where('user_id', $user->id)->first();
        if (!$anggota && Schema::hasTable('anggotas')) {
            try {
                $anggota = Anggota::create([
                    'user_id' => $user->id,
                    'nama' => $user->nama_lengkap ?? $user->name ?? $user->username,
                    'nama_lengkap' => $user->nama_lengkap ?? $user->name ?? $user->username,
                    'angkatan' => $user->angkatan_masuk ?? now()->year,
                    'status' => 'Aktif',
                ]);
            } catch (\Exception $e) {}
        }
        if (empty($user->angkatan_masuk) && $anggota?->angkatan) {
            $user->angkatan_masuk = $anggota->angkatan;
            $user->save();
        }

        $angkatanFix = $anggota?->angkatan ?? $user->angkatan_masuk ?? now()->year;
        $namaLengkap = $anggota?->nama_lengkap ?? $anggota?->nama ?? $user->username ?? 'CAPAS';

        $search = trim($r->get('search', '') ?? '');

        $filterStatus = $r->get('status', 'semua');
        if ($filterStatus === '' || $filterStatus === null) $filterStatus = 'semua';

        $filterAngkatan = $r->get('angkatan', $angkatanFix);
        if ($filterAngkatan === '' || $filterAngkatan === null) $filterAngkatan = 'semua';

        $listAngkatan = User::whereIn('role_id', [2, 3, 5])
            ->whereNotNull('angkatan_masuk')
            ->distinct()->pluck('angkatan_masuk')->filter()->sort()->reverse()->values();
        if ($listAngkatan->isEmpty()) {
            $listAngkatan = Anggota::distinct()->pluck('angkatan')->filter()->sort()->reverse()->values();
        }

        $getByRole = function ($roleId) use ($filterAngkatan, $search, $filterStatus, $user) {
            $q = User::where('role_id', $roleId);

            if ($filterAngkatan && $filterAngkatan != 'semua') {
                $idsDariAnggota = Anggota::where('angkatan', $filterAngkatan)->pluck('user_id');
                $q->where(function ($qq) use ($roleId, $filterAngkatan, $idsDariAnggota) {
                    $qq->where('role_id', $roleId)->where('angkatan_masuk', $filterAngkatan);
                    if ($idsDariAnggota->count()) {
                        $qq->orWhere(function ($q2) use ($roleId, $idsDariAnggota) {
                            $q2->where('role_id', $roleId)->whereIn('id', $idsDariAnggota);
                        });
                    }
                });
            }

            $users = $q->orderBy('username')->get();
            $map = Anggota::whereIn('user_id', $users->pluck('id'))->get()->keyBy('user_id');

            return $users->map(function ($u) use ($map, $user) {
                $a = $map->get($u->id);
                $status = $a?->status ?? 'Aktif';
                $isMe = $u->id === $user->id;
                $jabatan = $status === 'Non-Aktif' ? '-' : ($isMe ? ($u->jabatan ?? $a?->jabatan ?? 'Anggota') : '-');
                return (object)[
                    'id' => $u->id, 'user_id' => $u->id,
                    'nama' => $a?->nama_lengkap ?? $a?->nama ?? $u->username,
                    'nama_lengkap' => $a?->nama_lengkap ?? $a?->nama ?? $u->username,
                    'username' => $u->username,
                    'angkatan' => $u->angkatan_masuk ?? $a?->angkatan ?? '-',
                    'angkatan_masuk' => $u->angkatan_masuk ?? $a?->angkatan ?? '-',
                    'jabatan' => $jabatan,
                    'status' => $status,
                    'foto' => $a?->foto ?? $u->foto,
                    'user' => $u,
                ];
            })->filter(function ($item) use ($search, $filterStatus) {
                if ($filterStatus != 'semua' && strtolower(trim($item->status)) != strtolower(trim($filterStatus))) return false;
                if ($search !== '' && !str_contains(strtolower($item->nama . ' ' . $item->username), strtolower($search))) return false;
                return true;
            })->values();
        };

        $capasList = $getByRole(5);
        $pplList = $getByRole(3);
        $ppsList = $getByRole(2);

        $anggotas = $capasList;
        $anggotaList = $capasList;
        $capas = $capasList;
        $ppl = $pplList;
        $pps = $ppsList;

        return view('capas.anggota', compact(
            'anggotaList', 'anggotas', 'namaLengkap', 'angkatanFix',
            'listAngkatan', 'filterAngkatan', 'search',
            'capasList', 'pplList', 'ppsList', 'filterStatus',
            'capas', 'ppl', 'pps'
        ));
    }

    public function kas(Request $r)
    {
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        $namaLengkap = $anggota?->nama_lengkap ?? $anggota?->nama ?? $user->username ?? 'CAPAS';
        $angkatanFix = $anggota?->angkatan ?? $user->angkatan_masuk ?? '2023';

        $filterAngkatan = $r->get('angkatan', 'semua');
        if ($filterAngkatan === '' || $filterAngkatan === null) $filterAngkatan = 'semua';
        $filterRole = $r->get('role', 'semua');
        if ($filterRole === '' || $filterRole === null) $filterRole = 'semua';
        $filterTipe = $r->get('tipe', 'semua');
        if ($filterTipe === '' || $filterTipe === null) $filterTipe = 'semua';
        $search = trim($r->get('search', '') ?? '');

        $hasKasTable = Schema::hasTable('kas');
        $hasNominal = $hasKasTable && Schema::hasColumn('kas', 'nominal');
        $hasStatus = $hasKasTable && Schema::hasColumn('kas', 'status');
        $kolomTipe = $hasKasTable && Schema::hasColumn('kas', 'tipe') ? 'tipe' : ($hasKasTable && Schema::hasColumn('kas', 'jenis') ? 'jenis' : ($hasKasTable && Schema::hasColumn('kas', 'kategori') ? 'kategori' : null));

        $listAngkatan = User::whereIn('role_id', [2, 3, 4, 5])
            ->whereNotNull('angkatan_masuk')
            ->distinct()->pluck('angkatan_masuk')->filter()->sort()->reverse()->values();

        $perAngkatan = $listAngkatan->map(function ($angkatan) use ($hasKasTable, $hasNominal, $hasStatus, $kolomTipe) {
            $idsAngkatan = User::whereIn('role_id', [2, 3, 4, 5])->where('angkatan_masuk', $angkatan)->pluck('id');
            $jumlah = User::whereIn('role_id', [2, 3, 5])->where('angkatan_masuk', $angkatan)->count();
            $masuk = 0; $keluar = 0;
            if ($hasKasTable && $hasNominal && $idsAngkatan->count()) {
                $q = Kas::whereIn('user_id', $idsAngkatan);
                if ($hasStatus) $q->where('status', 'lunas');
                if ($kolomTipe) {
                    $masuk = (clone $q)->whereIn($kolomTipe, ['masuk', 'pemasukan', 'in', 'iuran'])->sum('nominal');
                    $keluar = (clone $q)->whereIn($kolomTipe, ['keluar', 'pengeluaran', 'out'])->sum('nominal');
                    $masuk += (clone $q)->whereNull($kolomTipe)->sum('nominal');
                } else {
                    $masuk = (clone $q)->sum('nominal');
                }
            }
            return (object)['angkatan' => $angkatan, 'jumlah' => $jumlah, 'total' => $masuk - $keluar, 'masuk' => $masuk, 'keluar' => $keluar];
        });

        $perFiltered = $filterAngkatan != 'semua' ? $perAngkatan->where('angkatan', $filterAngkatan) : $perAngkatan;
        $totalMasuk = $perFiltered->sum('masuk');
        $totalKeluar = $perFiltered->sum('keluar');
        $saldo = $totalMasuk - $totalKeluar;

        $trackRecord = collect();
        if ($hasKasTable) {
            $queryUser = User::whereIn('role_id', [2, 3, 4, 5]);
            if ($filterAngkatan != 'semua') $queryUser->where('angkatan_masuk', $filterAngkatan);
            if ($filterRole != 'semua') {
                $roleId = $filterRole == 'PPS' ? 2 : ($filterRole == 'PPL' ? 3 : ($filterRole == 'ALUMNI' ? 4 : 5));
                $queryUser->where('role_id', $roleId);
            }
            $usersFiltered = $queryUser->get();
            $userMap = $usersFiltered->keyBy('id');
            $userIds = $usersFiltered->pluck('id');
            $anggotaMap = Anggota::whereIn('user_id', $userIds)->get()->keyBy('user_id');

            $q = Kas::whereIn('user_id', $userIds)->orderBy('created_at', 'desc');

            if ($search !== '') {
                $s = $search;
                $idsSearch = $anggotaMap->filter(fn($a) => str_contains(strtolower($a->nama_lengkap ?? $a->nama ?? ''), strtolower($s)))->keys();
                $idsUserSearch = $userMap->filter(fn($u) => str_contains(strtolower($u->username ?? ''), strtolower($s)))->keys();
                $idsNama = $idsSearch->merge($idsUserSearch)->unique()->values();

                $q->where(function ($qq) use ($s, $idsNama) {
                    if ($idsNama->count()) $qq->whereIn('user_id', $idsNama);
                    $qq->orWhere('keterangan', 'like', "%{$s}%")
                        ->orWhere('metode_pembayaran', 'like', "%{$s}%")
                        ->orWhere('status', 'like', "%{$s}%")
                        ->orWhere('bulan', 'like', "%{$s}%")
                        ->orWhere('nominal', 'like', "%{$s}%");
                    if (Schema::hasColumn('kas', 'alasan_penolakan')) {
                        $qq->orWhere('alasan_penolakan', 'like', "%{$s}%");
                    }
                    if (Schema::hasColumn('kas', 'deskripsi')) {
                        $qq->orWhere('deskripsi', 'like', "%{$s}%");
                    }
                });
            }

            if ($filterTipe !== 'semua' && $kolomTipe) {
                $q->where($kolomTipe, $filterTipe);
            }

            $trackRecord = $q->limit(100)->get()->map(function ($k) use ($userMap, $anggotaMap) {
                $u = $userMap->get($k->user_id);
                $a = $anggotaMap->get($k->user_id);
                $k->nama_pembayar = $a?->nama_lengkap ?? $a?->nama ?? $u?->username ?? 'Unknown';
                $k->angkatan_pembayar = $u?->angkatan_masuk ?? '-';
                $k->role_pembayar = $u?->role_id == 2 ? 'PPS' : ($u?->role_id == 3 ? 'PPL' : ($u?->role_id == 4 ? 'ALUMNI' : 'CAPAS'));
                return $k;
            });
        }

        $myKasQuery = $hasKasTable ? Kas::where('user_id', $user->id)->latest() : null;

        if ($myKasQuery && $search !== '') {
            $s = $search;
            $myKasQuery->where(function ($qq) use ($s) {
                $qq->where('keterangan', 'like', "%{$s}%")
                    ->orWhere('metode_pembayaran', 'like', "%{$s}%")
                    ->orWhere('status', 'like', "%{$s}%")
                    ->orWhere('bulan', 'like', "%{$s}%")
                    ->orWhere('nominal', 'like', "%{$s}%");
                if (Schema::hasColumn('kas', 'alasan_penolakan')) {
                    $qq->orWhere('alasan_penolakan', 'like', "%{$s}%");
                }
                if (Schema::hasColumn('kas', 'deskripsi')) {
                    $qq->orWhere('deskripsi', 'like', "%{$s}%");
                }
            });
        }

        if ($myKasQuery && $filterTipe !== 'semua' && $kolomTipe) {
            $myKasQuery->where($kolomTipe, $filterTipe);
        }

        $myKas = $myKasQuery ? $myKasQuery->get() : collect();
        $myTotal = $hasNominal ? $myKas->where('status', 'lunas')->sum('nominal') : 0;
        $myPending = $hasNominal ? $myKas->where('status', 'pending')->sum('nominal') : 0;
        $myStatus = 'Belum Ada Data';
        if ($myKas->count()) {
            if ($myPending > 0) $myStatus = 'Pending Rp' . number_format($myPending, 0, ',', '.');
            elseif ($myKas->where('status', 'lunas')->count() == $myKas->count()) $myStatus = 'Lunas';
            else $myStatus = 'Belum Lunas';
        }
        $listKas = $myKas;

        $target = $this->targetKas($user);
        $tierSekarang = $this->tierDariTotal($myTotal, $target);

        if (!session()->has('naik_tier')) {
            $keyLast = 'last_tier_user_' . $user->id;
            $tierTerakhir = session($keyLast, null);

            if ($tierTerakhir === null) {
                session([$keyLast => $tierSekarang]);
            } elseif ($tierTerakhir !== $tierSekarang) {
                $urutan = ['BRONZE' => 0, 'SILVER' => 1, 'GOLD' => 2, 'BLACK' => 3];
                $naik = ($urutan[$tierSekarang] ?? 0) > ($urutan[$tierTerakhir] ?? 0);
                if ($naik) {
                    session()->flash('naik_tier', $tierSekarang);
                    session()->flash('tier_lama', $tierTerakhir);
                }
                session([$keyLast => $tierSekarang]);
            }
        } else {
            session(['last_tier_user_' . $user->id => $tierSekarang]);
        }

        return view('capas.kas', compact(
            'listKas', 'namaLengkap', 'angkatanFix',
            'perAngkatan', 'listAngkatan', 'totalMasuk', 'totalKeluar', 'saldo',
            'myKas', 'myTotal', 'myStatus', 'myPending', 'trackRecord',
            'filterAngkatan', 'filterRole', 'search', 'filterTipe',
            'target'
        ));
    }

    public function kasStore(Request $r)
    {
        $user = Auth::user();

        $r->validate([
            'nominal' => 'required|numeric|min:1000|max:10000000',
            'metode_pembayaran' => 'required|in:cash,qris,transfer',
            'bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable|string|max:255',
            'bulan' => 'nullable|string|max:20',
        ]);

        $buktiPath = $r->file('bukti')->store('bukti_kas', 'public');

        $data = [
            'user_id' => $user->id,
            'nominal' => $r->nominal,
            'status' => 'pending',
        ];
        if (Schema::hasColumn('kas', 'keterangan')) $data['keterangan'] = $r->keterangan ?? 'Setoran Kas - ' . $user->username;
        elseif (Schema::hasColumn('kas', 'deskripsi')) $data['deskripsi'] = $r->keterangan ?? 'Setoran Kas - ' . $user->username;
        if (Schema::hasColumn('kas', 'bulan')) $data['bulan'] = $r->bulan ?? now()->format('Y-m');
        if (Schema::hasColumn('kas', 'tipe')) $data['tipe'] = 'masuk';
        elseif (Schema::hasColumn('kas', 'jenis')) $data['jenis'] = 'masuk';
        if (Schema::hasColumn('kas', 'metode_pembayaran')) $data['metode_pembayaran'] = $r->metode_pembayaran;
        if (Schema::hasColumn('kas', 'bukti')) $data['bukti'] = $buktiPath;

        $kas = Kas::create($data);

        if (Schema::hasTable('kas_logs')) {
            DB::table('kas_logs')->insert([
                'kas_id' => $kas->id,
                'user_id' => $user->id,
                'aksi' => 'setor',
                'keterangan' => 'CAPAS setor kas Rp' . number_format($r->nominal, 0, ',', '.') . ' - ' . $r->metode_pembayaran . ' - menunggu validasi',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('capas.kas')->with('success', 'Setoran kas Rp' . number_format($r->nominal, 0, ',', '.') . ' terkirim, menunggu validasi PPS / PPL.');
    }

    public function absensi()
    {
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        $namaLengkap = $anggota?->nama_lengkap ?? $anggota?->nama ?? $user->username ?? 'CAPAS';
        $angkatanFix = $anggota?->angkatan ?? $user->angkatan_masuk ?? '2023';
        $riwayatAbsen = Schema::hasTable('absensis') ? Absensi::where('user_id', $user->id)->latest()->get() : collect([]);
        return view('capas.absensi', compact('riwayatAbsen', 'namaLengkap', 'anggota'));
    }

    public function materi()
    {
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        $namaLengkap = $anggota?->nama_lengkap ?? $anggota?->nama ?? $user->username ?? 'CAPAS';
        $angkatanFix = $anggota?->angkatan ?? $user->angkatan_masuk ?? '2023';
        return view('capas.materi', compact('namaLengkap', 'angkatanFix'));
    }
}