<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        // Mapping nama role -> id (sesuai yang lu bilang)
        $map = [
            'developer' => 1,
            'alumni' => 2,
            'pps' => 3,
            'ppl' => 4,
            'capas' => 5,
            // biar bisa pake angka juga
            '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5,
        ];

        $userRoleId = (int)($user->role_id ?? 0);

        // ubah semua role yang diizinin jadi id angka
        $allowedIds = [];
        foreach ($roles as $r) {
            $r = strtolower(trim($r));
            if (isset($map[$r])) {
                $allowedIds[] = $map[$r];
            } elseif (is_numeric($r)) {
                $allowedIds[] = (int)$r;
            }
        }

        if (!in_array($userRoleId, $allowedIds)) {
            abort(403, 'Akses ditolak - Role '.$userRoleId.' tidak punya akses ke '.implode(',',$roles));
        }

        return $next($request);
    }
}