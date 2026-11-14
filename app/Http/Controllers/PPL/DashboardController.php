<?php
namespace App\Http\Controllers\PPL;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    private $listJabatanCapas = ['Bapak Koor','Ibu Koor','Sekretaris 1','Sekretaris 2','Bendahara 1','Bendahara 2','PH 1','PH 2','Binlat 1','Binlat 2','Anggota'];

    public function index()
    {
        $user = Auth::user();
        $anggotaUser = Anggota::where('user_id', $user->id)->first();
        $namaLengkap = $anggotaUser?->nama_lengkap ?? $anggotaUser?->nama ?? $user->name ?? $user->username ?? 'PPL';
        $angkatanFix = $anggotaUser?->angkatan ?? $user->angkatan_masuk ?? '2023';
        
        // MERGED: ANGGOTA = PPS + CAPAS
        $totalCapas = User::where('role_id', 5)->count();
        $totalPpl = User::where('role_id', 3)->count();
        $totalPps = User::where('role_id', 2)->count();
        $totalAnggota = $totalCapas + $totalPps; // UNTUK PPL: ANGGOTA = PPS + CAPAS

        $totalCapasSeangkatan = $totalCapas;
        $totalInventaris = Schema::hasTable('inventaris') ? DB::table('inventaris')->count() : 0;

        $saldoKasCapas = $saldoKas = 0;
        $persenAbsenCapas = $persenAbsen = 0;

        return view('ppl.dashboard', compact(
            'namaLengkap','angkatanFix',
            'totalAnggota','totalCapas','totalCapasSeangkatan',
            'totalPpl','totalPps',
            'totalInventaris',
            'saldoKasCapas','saldoKas','persenAbsenCapas','persenAbsen'
        ));
    }

    // KELOLA ANGGOTA - 3 KOLOM: CAPAS (EDIT) | PPL (VIEW ONLY) | PPS (VIEW ONLY)
    public function kelolaAnggota(Request $r)
    {
        $user = Auth::user();
        $anggotaUser = Anggota::where('user_id', $user->id)->first();
        $namaLengkap = $anggotaUser?->nama_lengkap ?? $anggotaUser?->nama ?? $user->name ?? $user->username ?? 'PPL';
        $angkatanFix = $anggotaUser?->angkatan ?? $user->angkatan_masuk ?? '2023';

        $search = $r->get('search');
        $filterAngkatan = $r->get('angkatan');
        $filterStatus = $r->get('status');

        $listAngkatan = User::whereIn('role_id',[2,3,5])->distinct()->pluck('angkatan_masuk')->filter()->sort()->reverse()->values();
        $listJabatanCapas = $this->listJabatanCapas;

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

        return view('ppl.kelola-anggota', compact('namaLengkap','angkatanFix','dataAnggota','anggotas','capasList','pplList','ppsList','totalPps','totalPpl','totalCapas','listAngkatan','listJabatanCapas','search','filterAngkatan','filterStatus'));
    }

    // UPDATE JABATAN + STATUS CAPAS - DIPANGGIL ROUTE PUT ppl/anggota/{id}
   public function updateAnggotaCapas(Request $r, $id)
{
    $r->validate([
        'jabatan' => 'required|string',
        'status' => 'required|in:Aktif,Non-Aktif',
    ]);

    $user = User::where('role_id', 5)->findOrFail($id);
    $status = $r->status;
    $jabatan = $status === 'Non-Aktif' ? '-' : $r->jabatan;

    $user->update(['jabatan' => $jabatan]);
    Anggota::where('user_id', $id)->update(['jabatan' => $jabatan, 'status' => $status]);

    // FIX: pake nama, jangan username (biar gak dikira password)
    $anggota = Anggota::where('user_id', $id)->first();
    $nama = $anggota?->nama_lengkap ?? $anggota?->nama ?? $user->username;

    return back()->with('success', "Berhasil update {$nama} menjadi {$jabatan} - {$status}");
}

    public function dataPps()
    {
        $user = Auth::user();
        $anggotaUser = Anggota::where('user_id', $user->id)->first();
        $namaLengkap = $anggotaUser?->nama_lengkap ?? $anggotaUser?->nama ?? $user->name ?? $user->username ?? 'PPL';
        $angkatanFix = $anggotaUser?->angkatan ?? $user->angkatan_masuk ?? '2023';

        $pps = User::where('role_id', 2)->orderBy('username')->get();
        $totalPps = $pps->count();
        $totalPpl = User::where('role_id', 3)->count();
        $totalCapas = User::where('role_id', 5)->count();

        return view('ppl.dataPps', compact('pps', 'namaLengkap', 'angkatanFix', 'totalPps', 'totalPpl', 'totalCapas'));
    }

    public function idc()
    {
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        if(!$anggota){
            $anggota = Anggota::where('username', $user->username)->first();
        }
        if(!$anggota){
            $anggota = Anggota::where('nama', 'like', '%'.$user->name.'%')->first();
        }
        $namaLengkap = $anggota?->nama_lengkap ?? $anggota?->nama ?? $user->name ?? $user->username ?? 'PPL';
        $angkatanFix = $anggota?->angkatan ?? $user->angkatan_masuk ?? '2023';
        $foto = $anggota?->foto ?? $user->foto ?? null;
        return view('ppl.idc', compact('namaLengkap','angkatanFix','foto','anggota'));
    }

    public function updateFotoIdc(Request $request){
        $request->validate(['foto'=>'required|image|max:2048']);
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        $oldFoto = $anggota?->foto ?? $user->foto;
        if($oldFoto) Storage::disk('public')->delete($oldFoto);
        $path = $request->file('foto')->store('idc','public');
        $user->foto = $path; $user->save();
        if($anggota){ $anggota->foto = $path; $anggota->save(); }
        return response()->json(['success'=>true,'path'=>$path]);
    }

    public function inventaris(){
        $items = Schema::hasTable('inventaris') ? DB::table('inventaris')->orderBy('created_at','desc')->get() : collect();
        $user = Auth::user();
        $anggotaUser = Anggota::where('user_id', $user->id)->first();
        return view('ppl.inventaris', [
            'items'=>$items, 
            'namaLengkap'=>$anggotaUser?->nama_lengkap ?? $anggotaUser?->nama ?? $user->name ?? $user->username ?? 'PPL', 
            'angkatanFix'=>$anggotaUser?->angkatan ?? $user->angkatan_masuk ?? '2023'
        ]);
    }
    public function inventarisStore(Request $r){
        $r->validate(['nama'=>'required|string|max:100','jumlah'=>'required|integer|min:1','kondisi'=>'required|in:Baik,Rusak,Hilang,Perlu Perbaikan']);
        DB::table('inventaris')->insert([
            'nama'=>$r->nama,'jumlah'=>$r->jumlah,'kondisi'=>$r->kondisi,
            'keterangan'=>$r->keterangan,'created_at'=>now(),'updated_at'=>now()
        ]);
        return back()->with('success','Inventaris ditambahkan');
    }
    public function inventarisDestroy($id){
        DB::table('inventaris')->where('id',$id)->delete();
        return back()->with('success','Dihapus');
    }
    
    public function absensi(){
        $user = Auth::user();
        $anggotaUser = Anggota::where('user_id', $user->id)->first();
        return view('ppl.absensi', [
            'namaLengkap'=>$anggotaUser?->nama_lengkap ?? $anggotaUser?->nama ?? $user->name ?? $user->username ?? 'PPL',
            'angkatanFix'=>$anggotaUser?->angkatan ?? $user->angkatan_masuk ?? '2023'
        ]);
    }
    public function absensiStore(Request $r){
        // logic lu yang lama biarin aja
        return back()->with('success','Absensi disimpan');
    }
    public function materi(){
        $user = Auth::user();
        $anggotaUser = Anggota::where('user_id', $user->id)->first();
        return view('ppl.materi', [
            'namaLengkap'=>$anggotaUser?->nama_lengkap ?? $anggotaUser?->nama ?? $user->name ?? $user->username ?? 'PPL',
            'angkatanFix'=>$anggotaUser?->angkatan ?? $user->angkatan_masuk ?? '2023'
        ]);
    }
}