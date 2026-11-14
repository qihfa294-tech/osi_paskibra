<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AlumniViewOnly
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        
        // kalo belum login, lewatin aja
        if(!$user) return $next($request);

        // kalo alumni tapi coba POST, PUT, DELETE, PATCH -> tolak
        if($user->isAlumni() && !$request->isMethod('get')){
            // kalo request ajax/json
            if($request->expectsJson()){
                return response()->json(['message' => 'Akun Alumni hanya View Only'], 403);
            }
            return back()->with('error', 'Akun Alumni hanya bisa melihat, tidak bisa menambah/menghapus!');
        }

        return $next($request);
    }
}