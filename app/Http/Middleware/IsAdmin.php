<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Jika belum login sama sekali, arahkan ke login Admin
        if (!auth()->check()) {
            return redirect()->route('admin.login.form')->with('error', 'Akses Terbatas! Silakan login sebagai Master Admin.');
        }

        // 2. Jika SUDAH login tapi BUKAN ADMIN (Member Biasa)
        if (!auth()->user()->is_admin) {
            // Langsung arahkan ke Dashboard Member, JANGAN ke halaman login admin!
            return redirect()->route('dashboard');
        }

        // 3. Jika dia adalah ADMIN sejati
        if (auth()->user()->is_admin) {
            // Cek apakah PIN Master sudah diisi?
            if (!session('admin_pin_verified')) {
                return redirect()->route('admin.pin.form');
            }
            // Jika PIN beres, silakan masuk ke Panel!
            return $next($request);
        }
    }
}
