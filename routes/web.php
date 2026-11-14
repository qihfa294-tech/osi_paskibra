<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\Developer\AnggotaController;
use App\Http\Controllers\CapasController;
use App\Http\Controllers\PPL\DashboardController as PPLDashboard;
use App\Http\Controllers\PPL\CapasManageController;
use App\Http\Controllers\PPL\KasController as PPLKas;
use App\Http\Controllers\PPS\DashboardController as PPSDashboard;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', function(Request $request){
    Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::get('/register', [RegisterController::class, 'showRequestForm'])->name('register');
Route::post('/register', [RegisterController::class, 'sendLink'])->name('register.request.link');
Route::get('/register/{token}', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register/{token}', [RegisterController::class, 'register'])->name('register.submit');

Route::middleware('auth')->group(function(){
    Route::get('/dashboard', function(){
        $role = (int)auth()->user()->role_id;
        return match($role){
            1 => redirect()->route('dev.dashboard'),
            2 => redirect()->route('pps.dashboard'),
            3 => redirect()->route('ppl.dashboard'),
            4 => redirect()->route('alumni.dashboard'),
            5 => redirect()->route('capas.dashboard'),
            default => redirect()->route('dev.dashboard')
        };
    })->name('dashboard');
    Route::get('/', fn() => redirect('/dashboard'));

    Route::middleware('role:1')->prefix('dev')->name('dev.')->group(function(){
        Route::get('/dashboard', [DeveloperController::class,'dashboard'])->name('dashboard');
        Route::post('/features/toggle', [DeveloperController::class,'toggleFeature'])->name('features.toggle');
        Route::get('/anggota', [AnggotaController::class,'index'])->name('anggota');
        Route::post('/anggota', [AnggotaController::class,'store'])->name('anggota.store');
        Route::post('/anggota/bulk-update', [AnggotaController::class,'bulkUpdate'])->name('anggota.bulkUpdate');
        Route::post('/anggota/bulk-delete', [AnggotaController::class,'bulkDelete'])->name('anggota.bulkDelete');
        Route::post('/anggota/promosi-angkatan', [AnggotaController::class,'promosiAngkatan'])->name('anggota.promosiAngkatan');
        Route::get('/anggota/{anggota}/edit', [AnggotaController::class,'edit'])->name('anggota.edit');
        Route::put('/anggota/{anggota}', [AnggotaController::class,'update'])->name('anggota.update');
        Route::delete('/anggota/{anggota}', [AnggotaController::class,'destroy'])->name('anggota.destroy');
        Route::get('/validasi-kas', [DeveloperController::class,'validasiKas'])->name('validasiKas');
        Route::post('/validasi-kas/{id}', [DeveloperController::class,'accKas'])->name('accKas');
        Route::get('/logs-saldo', [DeveloperController::class,'logsSaldo'])->name('logsSaldo');
    });

    // PPS (2)
    Route::middleware('role:2')->prefix('pps')->name('pps.')->group(function(){
        Route::get('/dashboard', [PPSDashboard::class,'index'])->name('dashboard');
        Route::get('/idc', [PPSDashboard::class,'idc'])->name('idc')->middleware('feature:idc');
        Route::post('/idc/foto', [PPSDashboard::class,'updateFotoIdc'])->name('idc.foto')->middleware('feature:idc');
        Route::get('/anggota', [PPSDashboard::class,'kelolaAnggota'])->name('anggota')->middleware('feature:kelola_anggota');
        Route::put('/anggota/{id}', [PPSDashboard::class,'updateAnggotaPpl'])->name('anggota.update')->middleware('feature:kelola_anggota');
        Route::get('/ppl', fn() => redirect()->route('pps.anggota'))->name('ppl');
        Route::get('/kelola-ppl', fn() => redirect()->route('pps.anggota'))->name('kelolaPpl');
        Route::get('/capas', fn() => redirect()->route('pps.anggota'))->name('capas');

        Route::middleware('feature:e_kas')->group(function(){
            Route::get('/e-kas', [PPLKas::class,'index'])->name('ekas');
            Route::get('/e-kas/{tipe}', [PPLKas::class,'tab'])->name('ekas.tab');
            Route::post('/e-kas/setting', [PPLKas::class,'settingStore'])->name('ekas.setting');
            Route::post('/e-kas/store', [PPLKas::class,'store'])->name('ekas.store');
        });

        Route::get('/absensi', [PPSDashboard::class,'absensi'])->name('absensi')->middleware('feature:absensi');
        Route::post('/absensi', [PPLDashboard::class,'absensiStore'])->name('absensi.store')->middleware('feature:absensi');
        Route::get('/inventaris', [PPLDashboard::class,'inventaris'])->name('inventaris')->middleware('feature:e_inventaris');
        Route::get('/materi', [PPLDashboard::class,'materi'])->name('materi')->middleware('feature:materi');
    });

    // PPL (3)
    Route::middleware('role:3')->prefix('ppl')->name('ppl.')->group(function(){
        Route::get('/dashboard', [PPLDashboard::class,'index'])->name('dashboard');
        Route::get('/idc', [PPLDashboard::class,'idc'])->name('idc')->middleware('feature:idc');
        Route::post('/idc/foto', [PPLDashboard::class,'updateFotoIdc'])->name('idc.foto')->middleware('feature:idc');
        
        Route::middleware('feature:kelola_anggota')->group(function(){
            Route::get('/anggota', [PPLDashboard::class,'kelolaAnggota'])->name('anggota');
            Route::put('/anggota/{id}', [PPLDashboard::class,'updateAnggotaCapas'])->name('anggota.update');
            Route::get('/capas', fn() => redirect()->route('ppl.anggota'))->name('capas');
            Route::get('/capas/{id}', [CapasManageController::class,'show'])->name('capas.show');
            Route::put('/capas/{id}/jabatan', [CapasManageController::class,'updateJabatan'])->name('capas.jabatan.update');
            Route::post('/capas/absensi', [CapasManageController::class,'absensiStore'])->name('capas.absensi');
            Route::post('/capas/kas', [CapasManageController::class,'kasStore'])->name('capas.kas');
            Route::get('/data-pps', [PPLDashboard::class,'dataPps'])->name('dataPps');
            Route::get('/pps', [PPLDashboard::class,'dataPps'])->name('pps');
        });

        Route::get('/inventaris', [PPLDashboard::class,'inventaris'])->name('inventaris')->middleware('feature:e_inventaris');
        Route::post('/inventaris', [PPLDashboard::class,'inventarisStore'])->name('inventaris.store')->middleware('feature:e_inventaris');
        Route::delete('/inventaris/{id}', [PPLDashboard::class,'inventarisDestroy'])->name('inventaris.destroy')->middleware('feature:e_inventaris');
        
        Route::get('/absensi', [PPLDashboard::class,'absensi'])->name('absensi')->middleware('feature:absensi');
        Route::post('/absensi', [PPLDashboard::class,'absensiStore'])->name('absensi.store')->middleware('feature:absensi');
        Route::get('/materi', [PPLDashboard::class,'materi'])->name('materi')->middleware('feature:materi');

        Route::middleware('feature:e_kas')->group(function(){
            Route::get('/e-kas', [PPLKas::class,'index'])->name('ekas');
            Route::get('/e-kas/{tipe}', [PPLKas::class,'tab'])->name('ekas.tab');
            Route::post('/e-kas/setting', [PPLKas::class,'settingStore'])->name('ekas.setting');
            Route::post('/e-kas/store', [PPLKas::class,'store'])->name('ekas.store');
        });
    });

    // ALUMNI (4) - VIEW ONLY, CUMA GET DOANG
    Route::middleware('role:4')->prefix('alumni')->name('alumni.')->group(function(){
        Route::get('/dashboard', function(){
            $u = auth()->user();
            $anggota = \App\Models\Anggota::where('user_id', $u->id)->first();
            return view('alumni.dashboard', [
                'namaLengkap' => $anggota?->nama_lengkap ?? $anggota?->nama ?? $u->username,
                'angkatanFix' => $u->angkatan_masuk,
                'totalPps' => User::where('role_id',2)->count(),
                'totalPpl' => User::where('role_id',3)->count(),
                'totalCapas' => User::where('role_id',5)->count(),
                'totalAlumni' => User::where('role_id',4)->count(),
                'totalInventaris' => 0,
            ]);
        })->name('dashboard');

        Route::get('/anggota', function(Request $r){
            $u = auth()->user();
            $anggotaLogin = \App\Models\Anggota::where('user_id', $u->id)->first();
            $search = $r->get('search');
            $filterAngkatan = $r->get('angkatan');
            $filterStatus = $r->get('status');
            $query = User::whereIn('role_id', [2,3,4,5]);
            if($filterAngkatan) $query->where('angkatan_masuk', $filterAngkatan);
            if($search){
                $ids = \App\Models\Anggota::where('nama','like','%'.$search.'%')->orWhere('nama_lengkap','like','%'.$search.'%')->pluck('user_id');
                $query->where(function($q) use ($search, $ids){
                    $q->where('username','like','%'.$search.'%')
                      ->orWhere('jabatan','like','%'.$search.'%')
                      ->orWhere('angkatan_masuk','like','%'.$search.'%')
                      ->orWhereIn('id', $ids);
                });
            }
            $users = $query->orderBy('role_id')->orderBy('angkatan_masuk','desc')->orderBy('username')->get();
            $map = \App\Models\Anggota::whereIn('user_id', $users->pluck('id'))->get()->keyBy('user_id');
            $data = $users->map(function($u) use ($map){
                $a = $map->get($u->id);
                $status = $a?->status ?? 'Aktif';
                return (object)[
                    'id' => $u->id,
                    'nama' => $a?->nama_lengkap ?? $a?->nama ?? $u->username,
                    'nama_lengkap' => $a?->nama_lengkap ?? $a?->nama ?? $u->username,
                    'username' => $u->username,
                    'angkatan' => $u->angkatan_masuk,
                    'kategori' => $u->role_id == 5 ? 'CAPAS' : ($u->role_id == 3 ? 'PPL' : ($u->role_id == 2 ? 'PPS' : 'ALUMNI')),
                    'jabatan' => $status === 'Non-Aktif' ? '-' : ($u->jabatan ?? $a?->jabatan ?? 'Anggota'),
                    'status' => $status,
                    'foto' => $a?->foto ?? $u->foto,
                ];
            });
            if($filterStatus) $data = $data->where('status', $filterStatus)->values();
            $listAngkatan = User::where('role_id','!=',1)->distinct()->pluck('angkatan_masuk')->filter()->sort()->reverse()->values();
            return view('alumni.anggota', [
                'namaLengkap' => $anggotaLogin?->nama_lengkap ?? $anggotaLogin?->nama ?? $u->username,
                'angkatanFix' => $u->angkatan_masuk,
                'capasList' => $data->where('kategori','CAPAS')->values(),
                'pplList' => $data->where('kategori','PPL')->values(),
                'ppsList' => $data->where('kategori','PPS')->values(),
                'alumniList' => $data->where('kategori','ALUMNI')->values(),
                'anggotas' => $data,
                'listAngkatan' => $listAngkatan,
                'search' => $search,
                'filterAngkatan' => $filterAngkatan,
                'filterStatus' => $filterStatus,
            ]);
        })->name('anggota')->middleware('feature:kelola_anggota');

        // E-KAS ALUMNI - VIEW ONLY, CUMA GET DOANG
        Route::middleware('feature:e_kas')->group(function(){
            Route::get('/e-kas', [PPLKas::class,'index'])->name('ekas');
            Route::get('/e-kas/{tipe}', [PPLKas::class,'tab'])->name('ekas.tab');
            // SENGAJA GAK ADA POST setting / store, BIAR ALUMNI GAK BISA NGUBAH KAS
        });
    });

    // CAPAS (5) - BISA SETOR KAS SENDIRI + LIAT TRACK RECORD SEMUA ANGKATAN
    Route::middleware('role:5')->prefix('capas')->name('capas.')->group(function(){
        Route::get('/dashboard', [CapasController::class, 'dashboard'])->name('dashboard');
        Route::get('/anggota', [CapasController::class, 'anggota'])->name('anggota')->middleware('feature:kelola_anggota');
        Route::get('/absensi', [CapasController::class, 'absensi'])->name('absensi')->middleware('feature:absensi');
        Route::get('/materi', [CapasController::class, 'materi'])->name('materi')->middleware('feature:materi');
        Route::get('/idc', [CapasController::class, 'idc'])->name('idc')->middleware('feature:idc');
        Route::post('/idc/foto', [CapasController::class, 'updateFotoIdc'])->name('idc.foto')->middleware('feature:idc');
        Route::middleware('feature:e_kas')->group(function(){
            Route::get('/kas', [CapasController::class, 'kas'])->name('kas');
            Route::post('/kas', [CapasController::class, 'kasStore'])->name('kas.store');
            // TES TRANSISI KARTU - HAPUS NANTI KALAU UDAH SELESAI TES
            // Cara pakai: /capas/tes-upgrade/silver/gold  atau  /capas/tes-upgrade/gold/black
            Route::get('/tes-upgrade/{dari}/{ke}', function($dari, $ke){
                $dari = strtoupper($dari);
                $ke = strtoupper($ke);
                $valid = ['BRONZE','SILVER','GOLD','BLACK'];
                if(!in_array($dari, $valid) || !in_array($ke, $valid)){
                    return redirect()->route('capas.kas')->with('success', 'Tier tidak valid. Pakai BRONZE / SILVER / GOLD / BLACK.');
                }
                // Simpan juga last_tier biar controller nggak nimpa flash ini
                session(['last_tier_user_'.auth()->id() => $ke]);
                return redirect()->route('capas.kas')->with('naik_tier', $ke)->with('tier_lama', $dari);
            })->name('tes-upgrade');
            // SENGAJA GAK ADA PUT / DELETE, BIAR CAPAS GAK BISA EDIT / HAPUS KAS
        });
    });
});