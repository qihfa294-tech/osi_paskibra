<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm(){
        return view('auth.login');
    }

    public function login(Request $request){
        // BISA USERNAME ATAU EMAIL - FIX BUG EMAIL qihfa294...
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $loginValue = trim($request->login);
        $loginField = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if(Auth::attempt([$loginField => $loginValue, 'password' => $request->password], $request->filled('remember'))){
            $request->session()->regenerate();
            $user = Auth::user();
            
            // CEK STATUS DI TABEL ANGGOTA, BUKAN USERS
            $anggota = \App\Models\Anggota::where('user_id', $user->id)->first();
            if($anggota && $anggota->status === 'Non-Aktif'){
                Auth::logout();
                // <-- YANG DIGANTI CUMA BARIS INI
                return back()->withErrors(['login' => 'Akun Anda saat ini DiNon-Aktifkan.'])->withInput();
            }

            // REDIRECT SESUAI ROLE - INI YANG BIKIN gak ERROR capas.dashboard
            return match((int)$user->role_id){
                1 => redirect()->route('dev.dashboard'), // DEVELOPER
                2 => redirect()->route('pps.dashboard'), // PPS
                3 => redirect()->route('ppl.dashboard'),  // PPL
                4 => redirect()->route('alumni.dashboard'), // ALUMNI
                5 => redirect()->route('capas.dashboard'), // CAPAS - yang error di screenshot lu
                default => redirect('/'),
            };
        }

        return back()->withErrors(['login' => 'Username / Email atau password salah'])->withInput();
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}