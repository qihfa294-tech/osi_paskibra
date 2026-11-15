<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Models\Anggota;

class ProfileController extends Controller
{
    private static function cariAnggota($user): ?Anggota
    {
        if (!$user) {
            return null;
        }

        try {
            if (!Schema::hasTable('anggotas')) {
                return null;
            }

            if (!empty($user->anggota_id)) {
                $anggota = Anggota::find($user->anggota_id);
                if ($anggota) {
                    return $anggota;
                }
            }

            if (!empty($user->anggota)) {
                return $user->anggota;
            }

            return Anggota::where('user_id', $user->id)->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function perluLengkapi($user): bool
    {
        if (!$user) {
            return true;
        }

        $anggota = self::cariAnggota($user);

        $wajib = ['nama_lengkap', 'nama_panggilan', 'jurusan', 'kelas', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'no_hp', 'agama', 'jenis_kelamin', 'foto'];

        foreach ($wajib as $field) {
            $nilaiAnggota = null;

            if ($anggota) {
                if ($field === 'nama_lengkap') {
                    $nilaiAnggota = $anggota->nama_lengkap ?? $anggota->nama ?? null;
                } else {
                    $nilaiAnggota = $anggota->{$field} ?? null;
                }
            }

            if (empty($nilaiAnggota)) {
                return true;
            }
        }

        return false;
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $anggota = self::cariAnggota($user);

        $tingkat = $user->role_id == 2 ? 'XII' : ($user->role_id == 3 ? 'XI' : 'X');

        $validated = $request->validate([
            'nama_lengkap'   => 'required|string|max:100',
            'nama_panggilan' => 'required|string|max:50',
            'jurusan'        => 'required|in:MP,BR,AK,DKV,TKJ,TKL',
            'kelas'          => [
                'required', 'string', 'max:20',
                function ($attribute, $value, $fail) use ($request, $tingkat) {
                    $jurusan = $request->jurusan;
                    $jumlah = $jurusan === 'TKL' ? 1 : 3;
                    $boleh = [];
                    for ($i = 1; $i <= $jumlah; $i++) {
                        $boleh[] = $tingkat . ' ' . $jurusan . ' ' . $i;
                    }
                    if (!in_array($value, $boleh)) {
                        $fail('Kelas tidak sesuai dengan jurusan dan jabatan Anda.');
                    }
                },
            ],
            'alamat'         => 'required|string',
            'tempat_lahir'   => 'required|string|max:100',
            'tanggal_lahir'  => 'required|date',
            'no_hp'          => 'required|string|max:20',
            'agama'          => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'foto'           => ($anggota && !empty($anggota->foto) ? 'nullable' : 'required') . '|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $fotoLama = $anggota?->foto ?? null;
            if ($fotoLama && Storage::disk('public')->exists($fotoLama)) {
                Storage::disk('public')->delete($fotoLama);
            }
            $validated['foto'] = $request->file('foto')->store('foto_profil', 'public');
        } else {
            unset($validated['foto']);
        }

        $dataAnggota = $validated;
        $dataAnggota['nama'] = $validated['nama_lengkap'];
        $dataAnggota['angkatan'] = $anggota?->angkatan ?? $user->angkatan_masuk ?? now()->year;
        $dataAnggota['status'] = $anggota?->status ?? 'Aktif';
        $dataAnggota['biodata_lengkap'] = true;

        if ($anggota) {
            $anggota->forceFill($dataAnggota)->save();
        } else {
            $dataAnggota['user_id'] = $user->id;
            $anggota = Anggota::create($dataAnggota);
        }

        if ($user->anggota_id != $anggota->id) {
            $user->forceFill(['anggota_id' => $anggota->id])->save();
        }

        return redirect()->route('capas.dashboard')->with('success', 'Biodata berhasil disimpan.');
    }
}