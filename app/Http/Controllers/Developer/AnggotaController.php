<?php
namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnggotaController extends Controller
{
    private $map = ['CAPAS'=>5,'PPL'=>3,'PPS'=>2,'ALUMNI'=>4,'DEVELOPER'=>1];
    private $reverseMap = [5=>'CAPAS',3=>'PPL',2=>'PPS',4=>'ALUMNI',1=>'DEVELOPER'];

    private function listJabatanAll(){
        return [
            'CAPAS' => ['Bapak Koor','Ibu Koor','Sekretaris 1','Sekretaris 2','Bendahara 1','Bendahara 2','PH 1','PH 2','Binlat 1','Binlat 2'],
            'PPL' => ['Ketua','Wakil Ketua','Sekretaris 1','Sekretaris 2','Bendahara 1','Bendahara 2','Koor Proto','Anggota Proto','Koor Binlat','Anggota Binlat','Koor SDM','Anggota SDM','Koor Humas','Anggota Humas','Koor Kespas & Kerohanian','Anggota Kespas & Kerohanian','Koor Inven','Anggota Inven'],
            'PPS' => ['Koordinator Lapangan','Ketua PPS','Wakil PPS','Sekretaris','Wakil Sekretaris','Bendahara','Wakil Bendahara','Koordinator Proto','Anggota Proto','Koordinator Binlat','Anggota Binlat','Koordinator SDM','Anggota SDM','Koordinator Humas','Anggota Humas','Koordinator Kerohanian','Anggota Kerohanian','Koordinator Inventaris','Anggota Inventaris'],
            'ALUMNI' => ['Anggota','Alumni'],
            'DEVELOPER' => ['Developer']
        ];
    }

    // HELPER: simpen riwayat, dipanggil SEBELUM jabatan ditimpa
    private function simpanRiwayat($userId, $kategoriLama, $jabatanLama, $kategoriBaru, $jabatanBaru, $keterangan = 'Diubah oleh Developer'){
        if($kategoriLama === $kategoriBaru && $jabatanLama === $jabatanBaru) return;
        if($jabatanLama === null || $jabatanLama === '') return;

        DB::table('jabatan_histories')->insert([
            'user_id' => $userId,
            'kategori_lama' => $kategoriLama,
            'jabatan_lama' => $jabatanLama,
            'kategori_baru' => $kategoriBaru,
            'jabatan_baru' => $jabatanBaru,
            'keterangan' => $keterangan,
            'changed_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function index(Request $r)
    {
        $kategori = $r->get('kategori','Semua');
        $filterAngkatan = $r->get('angkatan');
        $search = $r->get('search');

        $listAngkatan = User::distinct()->pluck('angkatan_masuk')->filter()->sort()->reverse()->values();
        $listJabatanAll = $this->listJabatanAll();
        $listJabatan = $listJabatanAll[$kategori] ?? ['Anggota'];

        $query = User::whereIn('role_id',[1,2,3,4,5]);
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

        $users = $query->orderBy('role_id')->orderBy('angkatan_masuk','desc')->orderBy('username')->get();
        $anggotaMap = Anggota::whereIn('user_id', $users->pluck('id'))->get()->keyBy('user_id');
        // Ambil riwayat sekaligus biar gak N+1
        $riwayatMap = DB::table('jabatan_histories')
            ->whereIn('user_id', $users->pluck('id'))
            ->orderBy('created_at','asc')
            ->get()
            ->groupBy('user_id');

        $anggotas = $users->map(function($u) use ($anggotaMap, $riwayatMap){
            $a = $anggotaMap->get($u->id);
            $namaLengkap = $a?->nama_lengkap ?? $a?->nama ?? $u->username;
            $status = $a?->status ?? 'Aktif';
            $jabatan = ($status === 'Non-Aktif') ? '-' : ($u->jabatan ?? $a?->jabatan ?? 'Anggota');
            return (object)[
                'id'=>$u->id,
                'nama'=>$namaLengkap,
                'nama_lengkap'=>$namaLengkap,
                'angkatan'=>$u->angkatan_masuk,
                'kategori'=>$this->reverseMap[$u->role_id] ?? 'CAPAS',
                'jabatan'=>$jabatan,
                'status'=>$status,
                'user'=>$u,
                'riwayat'=> $riwayatMap->get($u->id, collect()),
            ];
        });

        return view('developer.anggota.index', compact('kategori','anggotas','listAngkatan','listJabatan','listJabatanAll','filterAngkatan','search'));
    }

    public function promosiAngkatan(Request $r)
    {
        $r->validate(['angkatan'=>'required','ke_kategori'=>'required']);
        $ke = $this->map[$r->ke_kategori] ?? 3;

        $query = User::where('angkatan_masuk',$r->angkatan);
        $labelDari = 'Semua';
        if(!empty($r->dari_kategori) && isset($this->map[$r->dari_kategori])){
            $dari = $this->map[$r->dari_kategori];
            if($r->dari_kategori === $r->ke_kategori){
                return back()->with('error', "Gagal: Tidak bisa memindahkan {$r->dari_kategori} ke {$r->ke_kategori} yang sama!");
            }
            $query->where('role_id',$dari);
            $labelDari = $r->dari_kategori;
        } else {
            $query->where('role_id','!=',1);
            $query->where('role_id','!=',$ke);
        }

        $usersLama = $query->get();
        if($usersLama->isEmpty()){
            return back()->with('error', "Gagal: Tidak ada anggota {$labelDari} angkatan {$r->angkatan}");
        }

        // Simpen riwayat dulu sebelum bulk update
        foreach($usersLama as $u){
            $this->simpanRiwayat(
                $u->id,
                $this->reverseMap[$u->role_id] ?? 'CAPAS',
                $u->jabatan,
                $r->ke_kategori,
                $u->jabatan, // jabatan tetep, kategori yang pindah
                "Promosi Angkatan {$r->angkatan} {$labelDari} -> {$r->ke_kategori}"
            );
        }

        $ids = $usersLama->pluck('id');
        User::whereIn('id',$ids)->update(['role_id'=>$ke]);
        Anggota::whereIn('user_id',$ids)->update(['status'=>'Aktif', 'kategori'=>$r->ke_kategori]);
        return back()->with('success', $ids->count()." orang angkatan {$r->angkatan} {$labelDari} -> {$r->ke_kategori}");
    }

    public function store(Request $r){
        $r->validate(['nama'=>'required|string','angkatan'=>'required','kategori'=>'required']);
        $nama = trim($r->nama);
        $username = strtolower(str_replace(' ','',$nama));
        $user = User::create([
            'username'=>$username,
            'role_id'=>$this->map[$r->kategori] ?? 5,
            'angkatan_masuk'=>$r->angkatan,
            'jabatan'=>'Anggota',
            'password'=>bcrypt('paskibra123'),
            'email'=> $username.$r->angkatan.'@osi.test'
        ]);
        Anggota::create([
            'user_id'=>$user->id,
            'nama'=>$nama,
            'nama_lengkap'=>$nama,
            'angkatan'=>$r->angkatan,
            'kategori'=>$r->kategori,
            'jabatan'=>'Anggota',
            'status'=>'Aktif',
        ]);
        // Riwayat awal biar gak kosong
        DB::table('jabatan_histories')->insert([
            'user_id'=>$user->id,
            'kategori_lama'=>'-',
            'jabatan_lama'=>'-',
            'kategori_baru'=>$r->kategori,
            'jabatan_baru'=>'Anggota',
            'keterangan'=>'Jabatan awal',
            'changed_by'=>auth()->id(),
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
        return back()->with('success','Berhasil tambah '.$r->kategori.' '.$nama);
    }

    public function bulkUpdate(Request $r){
        if($r->aksi == 'luluskan'){
            $usersLama = User::whereIn('id',$r->ids)->get();
            foreach($usersLama as $u){
                $this->simpanRiwayat(
                    $u->id,
                    $this->reverseMap[$u->role_id] ?? 'CAPAS',
                    $u->jabatan,
                    'ALUMNI',
                    'Anggota',
                    'Diluluskan jadi ALUMNI'
                );
            }
            User::whereIn('id',$r->ids)->update(['role_id'=>4, 'jabatan'=>'Anggota']);
            Anggota::whereIn('user_id',$r->ids)->update(['status'=>'Lulus', 'jabatan'=>'Anggota', 'kategori'=>'ALUMNI']);
            return back()->with('success','Berhasil meluluskan '.count($r->ids).' orang jadi ALUMNI');
        }
        return back()->with('success','Aksi '.$r->aksi.' berhasil');
    }

    public function bulkDelete(Request $r){
        Anggota::whereIn('user_id',$r->ids)->delete();
        User::whereIn('id',$r->ids)->delete();
        return back()->with('success','Berhasil hapus '.count($r->ids).' orang');
    }

    public function edit($id){
        $anggota = User::findOrFail($id);
        $anggotaData = Anggota::where('user_id', $id)->first();

        $listKategori = ['CAPAS','PPL','PPS','ALUMNI','DEVELOPER'];
        $listAngkatan = User::distinct()->pluck('angkatan_masuk')->filter()->sortDesc()->values();
        $listStatus = ['Aktif','Non-Aktif','Lulus'];
        $listJabatanAll = $this->listJabatanAll();

        $kategoriNow = $this->reverseMap[$anggota->role_id] ?? 'CAPAS';
        $listJabatan = $listJabatanAll[$kategoriNow] ?? $listJabatanAll['CAPAS'];

        $anggota->angkatan = $anggota->angkatan_masuk;
        $anggota->nama = $anggotaData?->nama_lengkap ?? $anggotaData?->nama ?? $anggota->username;
        $anggota->status = $anggotaData?->status ?? 'Aktif';
        $anggota->jabatan = ($anggota->status === 'Non-Aktif') ? '-' : ($anggota->jabatan ?? $anggotaData?->jabatan ?? 'Anggota');
        $anggota->kategori = $kategoriNow;

        // Riwayat buat ditampilin di halaman Edit
        $riwayat = DB::table('jabatan_histories')->where('user_id',$id)->orderBy('created_at','asc')->get();

        return view('developer.anggota.edit', compact(
            'anggota','listKategori','listAngkatan','listJabatan','listJabatanAll','listStatus','riwayat'
        ));
    }

    public function update(Request $r, $id){
        $user = User::findOrFail($id);
        $status = $r->status ?? 'Aktif';

        // TANGKEP YANG LAMA SEBELUM DITIMPA
        $kategoriLama = $this->reverseMap[$user->role_id] ?? 'CAPAS';
        $jabatanLama = $user->jabatan ?? 'Anggota';

        // HITUNG YANG BARU
        if($status === 'Non-Aktif'){
            $kategoriBaru = $kategoriLama;
            $jabatanBaru = '-';
        } elseif($status === 'Lulus'){
            $kategoriBaru = 'ALUMNI';
            $jabatanBaru = 'Anggota';
        } else {
            $kategoriBaru = $r->kategori ?? $kategoriLama;
            $jabatanBaru = $r->jabatan ?? $jabatanLama;
        }

        // SIMPEN RIWAYAT - ini yang bikin Binlat 1 gak ilang
        $this->simpanRiwayat($id, $kategoriLama, $jabatanLama, $kategoriBaru, $jabatanBaru, 'Diubah oleh Developer');

        $updateData = ['angkatan_masuk'=>$r->angkatan];
        if($status === 'Non-Aktif'){
            $updateData['jabatan'] = '-';
        } elseif($status === 'Lulus'){
            $updateData['role_id'] = $this->map['ALUMNI'];
            $updateData['jabatan'] = 'Anggota';
        } else {
            if($r->has('jabatan') && $r->jabatan !== '-') $updateData['jabatan'] = $r->jabatan;
            if($r->has('kategori') && isset($this->map[$r->kategori])){
                $updateData['role_id'] = $this->map[$r->kategori];
            }
        }
        $user->update($updateData);

        if($r->has('nama')){
            $anggotaUpdate = [
                'nama' => $r->nama,
                'nama_lengkap' => $r->nama,
                'angkatan' => $r->angkatan,
                'status' => $status,
            ];
            if($status === 'Non-Aktif'){
                $anggotaUpdate['jabatan'] = '-';
            } elseif($status === 'Lulus'){
                $anggotaUpdate['jabatan'] = 'Anggota';
                $anggotaUpdate['kategori'] = 'ALUMNI';
            } else {
                $anggotaUpdate['jabatan'] = $r->jabatan ?? 'Anggota';
                if($r->has('kategori')) $anggotaUpdate['kategori'] = $r->kategori;
            }
            Anggota::where('user_id', $id)->update($anggotaUpdate);
        }

        $pesan = ($status === 'Non-Aktif') ? 'Diupdate jadi Non-Aktif' : 'Diupdate jadi '.$r->jabatan;
        return redirect()->route('dev.anggota')->with('success',$pesan);
    }

    public function destroy(User $anggota){
        Anggota::where('user_id', $anggota->id)->delete();
        $anggota->delete();
        return back()->with('success','Dihapus');
    }
}