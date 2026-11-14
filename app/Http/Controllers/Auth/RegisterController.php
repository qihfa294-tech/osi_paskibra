<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Anggota;
use App\Models\RegistrationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class RegisterController extends Controller
{
    public function showRequestForm(){
        return view('auth.register-request');
    }

    public function sendLink(Request $request){
        $request->validate([
            'email' => 'required|email|ends_with:gmail.com|unique:users,email'
        ],[
            'email.ends_with' => 'Wajib pakai Gmail!',
            'email.unique' => 'Email ini sudah terdaftar!'
        ]);

        $token = Str::random(64);
        
        RegistrationToken::updateOrCreate(
            ['email' => $request->email],
            ['token' => $token, 'expires_at' => Carbon::now()->addHours(24)]
        );

        $link = url('/register/'.$token);

        try {
            Mail::send('emails.register-link', ['link' => $link], function($m) use ($request){
                $m->to($request->email)->subject('Link Pendaftaran OSI PASKIBRA NESTA');
            });
        } catch (\Exception $e) {
            Log::error('Mail gagal: '.$e->getMessage());
        }

        return back()->with('success','Link pendaftaran telah dikirim ke '.$request->email.' - Cek inbox/spam (24 jam)');
    }

    public function showRegisterForm($token){
        $invite = RegistrationToken::where('token',$token)->where('expires_at','>',now())->first();
        if(!$invite) abort(403, 'Link tidak valid atau sudah kadaluarsa');
        return view('auth.register', ['email' => $invite->email, 'token' => $token]);
    }

    public function register(Request $request, $token){
        $invite = RegistrationToken::where('token',$token)->where('expires_at','>',now())->first();
        if(!$invite) abort(403, 'Link tidak valid atau sudah kadaluarsa');

        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|alpha_dash|unique:users,username|min:4|max:20',
            'angkatan' => 'required|numeric|min:2015|max:2030',
            'password' => 'required|min:6|confirmed',
        ],[
            'username.alpha_dash' => 'Username cuma boleh huruf, angka, dash, underscore (jangan pakai @ atau email)',
            'username.unique' => 'Username sudah dipakai!'
        ]);

        $angkatan = (string)$request->angkatan;
        $username = strtolower(trim($request->username));
        
        // CEK JANGAN SAMPAI USERNAME BERUPA EMAIL
        if(filter_var($username, FILTER_VALIDATE_EMAIL)){
            return back()->withErrors(['username' => 'Username tidak boleh berupa email! Pakai username contoh: faqih, sawal, paskibra01'])->withInput();
        }

        $user = User::create([
            'username' => $username,
            'email' => $invite->email,
            'angkatan_masuk' => $angkatan,
            'password' => Hash::make($request->password),
            'role_id' => 5, // CAPAS
        ]);

        // PASTIKAN ANGGOTA KE-CREATE DENGAN USER_ID
        $anggotaData = [
            'user_id' => $user->id,
            'nama' => trim($request->name),
            'angkatan' => $angkatan,
            'kategori' => 'CAPAS',
            'jabatan' => 'Anggota',
            'status' => 'Aktif',
        ];

        if(Schema::hasColumn('anggotas', 'nama_lengkap')){
            $anggotaData['nama_lengkap'] = trim($request->name);
        }
        if(Schema::hasColumn('anggotas', 'username')){
            $anggotaData['username'] = $username;
        }

        try {
            Anggota::create($anggotaData);
        } catch (\Exception $e) {
            Log::error('Gagal create anggota: '.$e->getMessage());
            // fallback firstOrCreate
            Anggota::firstOrCreate(['user_id' => $user->id], $anggotaData);
        }

        $invite->delete();
        
        return redirect('/login')->with('success','Akun '.$username.' berhasil dibuat! Angkatan '.$angkatan.' - Silahkan login');
    }
}