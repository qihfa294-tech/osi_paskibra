<?php
namespace App\Http\Controllers\PPS;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Anggota;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $listJabatanPpl = ['Ketua','Wakil Ketua','Sekretaris 1','Sekretaris 2','Bendahara 1','Bendahara 2','Koor Proto','Anggota Proto','Koor Binlat','Anggota Binlat','Koor SDM','Anggota SDM','Koor Humas','Anggota Humas','Koor Kespas & Kerohanian','Anggota Kespas & Kerohanian','Koor Inven','Anggota Inven'];

    public function index()
    {
        $user = Auth::user();
        $anggotaUser = Anggota::where('user_id', $user->id)->first();
        $namaLengkap = $anggotaUser->nama ?? $user->name ?? $user->username ?? 'PPS';
        $angkatanFix = $anggotaUser->angkatan ?? $user->angkatan_masuk ?? '2023';

        // MERGE JADI ANGGOTA (PPL + CAPAS)
        $totalAnggota = User::whereIn('role_id', [3,5])->count();
        $totalPpl   = User::where('role_id', 3)->count();
        $totalCapas = User::where('role_id', 5)->count();
        $totalPps   = User::where('role_id', 2)->count();
        
        $totalCapasSeangkatan = $totalCapas; // biar gak error di view lama
        $totalInventaris = Schema::hasTable('inventaris') ? DB::table('inventaris')->count() : 0;

        return view('pps.dashboard', compact(
            'namaLengkap','angkatanFix',
            'totalAnggota','totalCapas','totalCapasSeangkatan',
            'totalPpl','totalPps','totalInventaris'
        ));
    }

    // KELOLA ANGGOTA - 3 KOLOM: CAPAS (VIEW) | PPL (EDIT) | PPS (VIEW)
    public function kelolaAnggota(Request $r)
    {
        $user = Auth::user();
        $anggotaUser = Anggota::where('user_id', $user->id)->first();
        $namaLengkap = $anggotaUser?->nama_lengkap ?? $anggotaUser?->nama ?? $user->name ?? $user->username ?? 'PPS';
        $angkatanFix = $anggotaUser?->angkatan ?? $user->angkatan_masuk ?? '2023';

        $search = $r->get('search');
        $filterAngkatan = $r->get('angkatan');
        $filterStatus = $r->get('status');

        $listAngkatan = User::whereIn('role_id',[2,3,5])->distinct()->pluck('angkatan_masuk')->filter()->sort()->reverse()->values();
        $listJabatanPpl = $this->listJabatanPpl;

        $query = User::whereIn('role_id', [2,3,5]);
        if($filterAngkatan) $query->where('angkatan_masuk', $filterAngkatan);
        if($search){
            $idsDariAnggota = Anggota::where('nama','like','%'.$search.'%')->orWhere('nama_lengkap','like','%'.$search.'%')->pluck('user_id');
            $query->where(function($q) use ($search, $idsDariAnggota){
                $q->where('username','like','%'.$search.'%')
                  ->orWhere('jabatan','like','%'.$search.'%')
                  ->orWhere('angkatan_masuk','like','%'.$search.'%')
                  ->orWhereIn('id', $idsDariAnggota);
            });
        }

        $users = $query->orderBy('role_id')->orderBy('angkatan_masuk','desc')->orderBy('username')->get();
        $map = Anggota::whereIn('user_id', $users->pluck('id'))->get()->keyBy('user_id');

        $dataAnggota = $users->map(function($u) use ($map){
            $a = $map->get($u->id);
            $status = $a?->status ?? 'Aktif';
            $kategori = $u->role_id == 5 ? 'CAPAS' : ($u->role_id == 3 ? 'PPL' : 'PPS');
            return (object)[
                'id' => $u->id,
                'user_id' => $u->id,
                'nama' => $a?->nama_lengkap ?? $a?->nama ?? $u->username,
                'nama_lengkap' => $a?->nama_lengkap ?? $a?->nama ?? $u->username,
                'username' => $u->username,
                'angkatan' => $u->angkatan_masuk,
                'angkatan_masuk' => $u->angkatan_masuk,
                'role_id' => $u->role_id,
                'kategori' => $kategori,
                'jabatan' => $status === 'Non-Aktif' ? '-' : ($u->jabatan ?? $a?->jabatan ?? 'Anggota'),
                'status' => $status,
                'foto' => $a?->foto ?? $u->foto,
                'user' => $u,
            ];
        });

        if($filterStatus) $dataAnggota = $dataAnggota->where('status', $filterStatus)->values();

        // Pisah 3 kolom buat view
        $capasList = $dataAnggota->where('kategori', 'CAPAS')->values();
        $pplList = $dataAnggota->where('kategori', 'PPL')->values();
        $ppsList = $dataAnggota->where('kategori', 'PPS')->values();

        // Buat view lama yang pake $anggotas juga tetep jalan
        $anggotas = $dataAnggota;

        $totalPps = User::where('role_id', 2)->count();
        $totalPpl = User::where('role_id', 3)->count();
        $totalCapas = User::where('role_id', 5)->count();

        return view('pps.kelola-anggota', compact('namaLengkap','angkatanFix','dataAnggota','anggotas','capasList','pplList','ppsList','totalPps','totalPpl','totalCapas','listAngkatan','listJabatanPpl','search','filterAngkatan','filterStatus'));
    }

    // PPS UPDATE PPL - DIPANGGIL ROUTE PUT pps/anggota/{id}
    public function updateAnggotaPpl(Request $r, $id)
    {
        $r->validate([
            'jabatan' => 'required|string',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        // KUNCI: cuma PPL (role_id 3) yang bisa diupdate, CAPAS / PPS bakal 404
        $user = User::where('role_id', 3)->findOrFail($id);
        $status = $r->status;
        $jabatan = $status === 'Non-Aktif' ? '-' : $r->jabatan;

        $user->update(['jabatan' => $jabatan]);
        Anggota::where('user_id', $id)->update(['jabatan' => $jabatan, 'status' => $status]);

        // Pake nama, jangan username (biar gak dikira password)
        $anggota = Anggota::where('user_id', $id)->first();
        $nama = $anggota?->nama_lengkap ?? $anggota?->nama ?? $user->username;

        return back()->with('success', "Berhasil update {$nama} menjadi {$jabatan} - {$status}");
    }

    // ALIAS BIAR ROUTE LAMA GAK 404
    public function kelolaPpl(){ return $this->kelolaAnggota(request()); }
    public function ppl(){ return $this->kelolaAnggota(request()); }
    public function capas(){ return $this->kelolaAnggota(request()); }

    public function ekas(Request $request)
    {
        return app(\App\Http\Controllers\PPL\KasController::class)->index($request);
    }

    public function absensi()
    {
        $user = Auth::user();
        $anggotaUser = Anggota::where('user_id', $user->id)->first();
        $namaLengkap = $anggotaUser->nama ?? $user->name ?? $user->username ?? 'PPS';
        $angkatanFix = $anggotaUser->angkatan ?? '2023';
        return view('ppl.absensi', compact('namaLengkap','angkatanFix'));
    }

    public function idc(){
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        if(!$anggota){
            $anggota = (object)[
                'nama' => $user->name ?? $user->username,
                'angkatan' => $user->angkatan_masuk ?? '2023',
                'foto' => null
            ];
        }
        return view('pps.idc', [
            'namaLengkap' => $anggota->nama ?? $user->name ?? $user->username,
            'angkatanFix' => $anggota->angkatan ?? $user->angkatan_masuk ?? '2023',
            'foto' => $anggota->foto ?? null,
            'anggota' => $anggota
        ]);
    }

    public function updateFotoIdc(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|max:2048|mimes:jpg,jpeg,png,webp'
        ]);
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->firstOrFail();
        if ($anggota->foto) {
            Storage::disk('public')->delete($anggota->foto);
        }
        $path = $request->file('foto')->store('foto_idc', 'public');
        $anggota->update(['foto' => $path]);
        return response()->json(['success' => true, 'foto' => $path]);
    }

    public function inventaris()
    {
        return app(\App\Http\Controllers\PPL\DashboardController::class)->inventaris();
    }
    public function materi()
    {
        return app(\App\Http\Controllers\PPL\DashboardController::class)->materi();
    }
}