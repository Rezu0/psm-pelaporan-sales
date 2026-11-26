<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 'peran'              -> hanya cek akun masih aktif
 * 'peran:admin,owner'  -> akun aktif DAN perannya cocok
 */
class CekPeran
{
    public function handle(Request $request, Closure $next, string ...$peran)
    {
        $user = $request->user();

        // Akun dinonaktifkan saat sedang login -> keluarkan
        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('popup', 'belum_aktif');
        }

        if ($peran) {
            abort_unless(in_array($user->role, $peran), 403);
        }

        return $next($request);
    }
}