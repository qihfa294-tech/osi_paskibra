<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Anggota;
use App\Models\KasPayment;
use App\Models\FeatureFlag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DeveloperController extends Controller
{
    private $map = ['CAPAS'=>5,'PPL'=>3,'PPS'=>2,'ALUMNI'=>4,'DEVELOPER'=>1];

    // Key fitur yang dipake di semua blade - HARUS SAMA PERSIS
    private $featureKeys = ['idc','kelola_anggota','e_kas','absensi','e_inventaris','materi'];

    private $featureLabels = [
        'idc' => 'IDC',
        'kelola_anggota' => 'Kelola Anggota',
        'e_kas' => 'E-KAS',
        'absensi' => 'Absensi',
        'e_inventaris' => 'E-Inventaris',
        'materi' => 'Materi',
    ];

    // Helper biar gak error 1364 lagi - selalu ngirim name + cek kolom beneran ada
    private function makeFlagData($key, $isEnabled = true)
    {
        $label = $this->featureLabels[$key] ?? ucfirst(str_replace('_',' ', $key));
        $data = ['is_enabled' => $isEnabled];

        // Cuma isi kolom yang beneran ada di tabel lu
        // Biar gak error Unknown column 'enabled' / 'label' / 'name'
        if (Schema::hasColumn('feature_flags', 'name')) $data['name'] = $label;
        if (Schema::hasColumn('feature_flags', 'label')) $data['label'] = $label;
        if (Schema::hasColumn('feature_flags', 'enabled')) $data['enabled'] = $isEnabled;
        if (Schema::hasColumn('feature_flags', 'description')) $data['description'] = $label;

        return $data;
    }

    // DASHBOARD
    public function dashboard()
    {
        // FIX 1364: sekarang firstOrCreate ngirim name juga, bukan cuma key + is_enabled
        foreach ($this->featureKeys as $k) {
            FeatureFlag::firstOrCreate(
                ['key' => $k],
                $this->makeFlagData($k, true)
            );
        }

        $flags = FeatureFlag::pluck('is_enabled','key')->toArray();
        $flagsNormalized = [];
        foreach ($flags as $k => $v) {
            $norm = strtolower(str_replace('-', '_', $k));
            $flagsNormalized[$norm] = (bool)$v;
        }
        foreach ($this->featureKeys as $k) {
            if (!isset($flagsNormalized[$k])) $flagsNormalized[$k] = true;
        }
        $flags = $flagsNormalized;

        $totalAnggota = User::where('role_id','!=',1)->count();
        $pendingKas = KasPayment::where('status','pending')->count();
        $saldo = KasPayment::where('status','valid')->sum('amount');
        $saldoTotal = $saldo;

        $idPps=2; $idPpl=3; $idAlumni=4; $idCapas=5;

        $jumlahCapas = User::where('role_id',$idCapas)->count();
        $jumlahPpl   = User::where('role_id',$idPpl)->count();
        $jumlahPps   = User::where('role_id',$idPps)->count();
        $jumlahAlumni= User::where('role_id',$idAlumni)->count();

        $angkatanCapas = User::where('role_id',$idCapas)->distinct()->pluck('angkatan_masuk')->filter()->sortDesc()->join(', ');
        $angkatanCapas = ($jumlahCapas > 0 && $angkatanCapas) ? 'ANGKATAN '.$angkatanCapas : 'ANGKATAN -';

        $angkatanPpl = User::where('role_id',$idPpl)->distinct()->pluck('angkatan_masuk')->filter()->sortDesc()->join(', ');
        $angkatanPpl = ($jumlahPpl > 0 && $angkatanPpl) ? 'ANGKATAN '.$angkatanPpl : 'ANGKATAN -';

        $angkatanPps = User::where('role_id',$idPps)->distinct()->pluck('angkatan_masuk')->filter()->sortDesc()->join(', ');
        $angkatanPps = ($jumlahPps > 0 && $angkatanPps) ? 'ANGKATAN '.$angkatanPps : 'ANGKATAN -';

        $ppsAngkatanRaw = User::where('role_id',$idPps)->min('angkatan_masuk');
        if($ppsAngkatanRaw){
            $angkatanAlumni = 'SEMUA ANGKATAN SEBELUM '.$ppsAngkatanRaw;
        } else {
            $angkatanAlumni = 'SEMUA ANGKATAN';
        }

        return view('developer.dashboard', compact(
            'flags','totalAnggota','pendingKas','saldo','saldoTotal',
            'jumlahCapas','jumlahPpl','jumlahPps','jumlahAlumni',
            'angkatanCapas','angkatanPpl','angkatanPps','angkatanAlumni'
        ));
    }

    public function toggleFeature(Request $request)
    {
        try {
            $key = $request->input('key') ?? $request->input('feature') ?? $request->input('name');
            if (!$key) {
                return response()->json(['success' => false, 'message' => 'Key fitur kosong'], 400);
            }

            $keyNorm = strtolower(str_replace(['-', ' '], '_', trim($key)));
            $alias = [
                'id_card' => 'idc',
                'idcard' => 'idc',
                'kelolaanggota' => 'kelola_anggota',
                'kelola_anggota' => 'kelola_anggota',
                'e-kas' => 'e_kas',
                'ekas' => 'e_kas',
                'kas' => 'e_kas',
                'e-inventaris' => 'e_inventaris',
                'einventaris' => 'e_inventaris',
                'inventaris' => 'e_inventaris',
            ];
            $key = $alias[$keyNorm] ?? $keyNorm;

            // FIX 1364 juga di sini, pake helper yang sama
            $flag = FeatureFlag::firstOrCreate(
                ['key' => $key],
                $this->makeFlagData($key, true)
            );

            if ($request->has('enabled') || $request->has('is_enabled')) {
                $raw = $request->input('enabled', $request->input('is_enabled'));
                $newVal = filter_var($raw, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($newVal === null) $newVal = (bool)$raw;
            } else {
                $newVal = !$flag->is_enabled;
            }

            $flag->is_enabled = $newVal;
            if (Schema::hasColumn('feature_flags', 'enabled')) {
                $flag->enabled = $newVal;
            }
            // Kalo di DB ada kolom name tapi kosong, isi sekalian biar gak error lagi
            if (Schema::hasColumn('feature_flags', 'name') && empty($flag->name)) {
                $flag->name = $this->featureLabels[$key] ?? ucfirst(str_replace('_',' ', $key));
            }
            $flag->save();

            cache()->forget('feature_'.$key);
            cache()->forget('features_all');

            return response()->json(['success' => true, 'key' => $key, 'is_enabled' => $newVal, 'enabled' => $newVal]);
        } catch (\Throwable $e) {
            \Log::error('toggleFeature error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function anggota(Request $request)
    {
        $kategori = $request->get('kategori','CAPAS');
        $filterAngkatan = $request->get('angkatan');
        $search = $request->get('search');
        $roleId = $this->map[$kategori] ?? 5;

        $listAngkatan = User::where('role_id','!=',1)->distinct()->pluck('angkatan_masuk')->filter()->sortDesc()->values();
        
        $listJabatanAll = [
            'CAPAS' => ['Bapak Koor','Ibu Koor','Sekretaris 1','Sekretaris 2','Bendahara 1','Bendahara 2','PH 1','PH 2','Binlat 1','Binlat 2'],
            'PPL' => ['Ketua','Wakil Ketua','Sekretaris 1','Sekretaris 2','Bendahara 1','Bendahara 2','Koordinator Proto','Anggota Proto','Koordinator Binlat','Anggota Binlat','Koordinator SDM','Anggota SDM','Koordinator Humas','Anggota Humas','Koor Kespas & Kerohanian','Anggota Kespas & Kerohanian','Koor Inventaris','Anggota Inventaris'],
            'PPS' => ['Koordinator Lapangan','Ketua PPS','Wakil PPS','Sekretaris','Wakil Sekretaris','Bendahara','Wakil Bendahara','Koordinator Proto','Anggota Proto','Koordinator Binlat','Anggota Binlat','Koordinator SDM','Anggota SDM','Koordinator Humas','Anggota Humas','Koordinator Kerohanian','Anggota Kerohanian','Koordinator Inventaris','Anggota Inventaris'],
            'ALUMNI' => ['Anggota','Alumni'],
            'DEVELOPER' => ['Developer']
        ];
        $listJabatan = $listJabatanAll[$kategori] ?? ['Anggota'];

        $query = User::where('role_id',$roleId);
        if($filterAngkatan) $query->where('angkatan_masuk',$filterAngkatan);

        if($search){
            $idsDariAnggota = Anggota::where('nama','like','%'.$search.'%')
                ->orWhere('nama_lengkap','like','%'.$search.'%')
                ->pluck('user_id');
            $query->where(function($q) use ($search, $idsDariAnggota){
                $q->where('username','like','%'.$search.'%')
                  ->orWhere('angkatan_masuk','like','%'.$search.'%')
                  ->orWhere('jabatan','like','%'.$search.'%')
                  ->orWhereIn('id', $idsDariAnggota);
            });
        }

        $users = $query->orderBy('angkatan_masuk','desc')->get();
        $anggotaMap = Anggota::whereIn('user_id', $users->pluck('id'))->get()->keyBy('user_id');

        $anggotas = $users->map(function($u) use ($anggotaMap){
            $a = $anggotaMap->get($u->id);
            $namaLengkap = $a?->nama_lengkap ?? $a?->nama ?? $u->username;
            $status = $a?->status ?? 'Aktif';
            $jabatan = ($status === 'Non-Aktif') ? '-' : ($u->jabatan ?? $a?->jabatan ?? 'Anggota');
            return (object)[
                'id'=>$u->id,
                'nama'=>$namaLengkap,
                'nama_lengkap'=>$namaLengkap,
                'angkatan'=>$u->angkatan_masuk,
                'jabatan'=>$jabatan,
                'status'=>$status,
                'user'=>$u
            ];
        });

        return view('developer.anggota.index', compact('kategori','anggotas','listAngkatan','listJabatan','listJabatanAll','filterAngkatan','search'));
    }

    public function anggotaEdit($id)
    {
        return redirect()->route('dev.anggota.edit', $id);
    }

    public function anggotaUpdate(Request $request, $id)
    {
        return redirect()->route('dev.anggota.edit', $id);
    }

    public function anggotaDestroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Anggota dihapus');
    }

    public function promosiAngkatan(Request $request)
    {
        $request->validate([
            'angkatan'=>'required',
            'dari_kategori'=>'required',
            'ke_kategori'=>'required',
        ]);

        if($request->dari_kategori === $request->ke_kategori){
            return back()->with('error', "Gagal: Tidak bisa memindahkan {$request->dari_kategori} ke {$request->ke_kategori} yang sama! Pilih tujuan berbeda.");
        }

        $dariId = $this->map[$request->dari_kategori] ?? 5;
        $keId = $this->map[$request->ke_kategori] ?? 3;

        $cek = User::where('role_id',$dariId)->where('angkatan_masuk',$request->angkatan)->count();
        if($cek == 0){
            return back()->with('error', "Gagal: Tidak ada anggota {$request->dari_kategori} angkatan {$request->angkatan}");
        }

        $ids = User::where('role_id',$dariId)->where('angkatan_masuk',$request->angkatan)->pluck('id');
        User::whereIn('id',$ids)->update(['role_id'=>$keId]);
        Anggota::whereIn('user_id',$ids)->update(['status'=>'Aktif', 'kategori'=>$request->ke_kategori]);

        return back()->with('success', "Berhasil: ".count($ids)." orang angkatan {$request->angkatan} {$request->dari_kategori} -> {$request->ke_kategori}");
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate(['ids'=>'required|array','aksi'=>'required']);
        if($request->aksi == 'luluskan'){
            User::whereIn('id',$request->ids)->update(['role_id'=>4, 'jabatan'=>'Anggota']);
            Anggota::whereIn('user_id',$request->ids)->update(['status'=>'Lulus', 'jabatan'=>'Anggota', 'kategori'=>'ALUMNI']);
            return back()->with('success','Berhasil meluluskan '.count($request->ids).' orang jadi ALUMNI');
        }
        return back()->with('success','Bulk update berhasil '.count($request->ids).' orang');
    }

    public function anggotaStore(Request $r){ return back()->with('success','Ditambah'); }
    public function validasiKas(){ $kasPending=KasPayment::with('user')->where('status','pending')->latest()->get(); return view('developer.validasi-kas',compact('kasPending')); }
    public function accKas(Request $request,$id){ $request->validate(['status'=>'required|in:valid,rejected']); $kas=KasPayment::findOrFail($id); $kas->update(['status'=>$request->status,'validated_by'=>auth()->id()]); return back()->with('success','Berhasil di-'.$request->status); }
    public function logsSaldo(){ $logs=KasPayment::with('user')->where('status','valid')->latest()->get(); return view('developer.logs-saldo',compact('logs')); }
}