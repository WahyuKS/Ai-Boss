<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePinVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Jika belum punya PIN, paksa ke halaman Setup
        if (empty($user->pin)) {
            if (!$request->routeIs('pin.setup') && !$request->routeIs('pin.store')) {
                return redirect()->route('pin.setup');
            }
        } else {
            // Jika sudah punya PIN tapi belum verifikasi di sesi ini, paksa verifikasi
            if (!session('pin_verified')) {
                if (!$request->routeIs('pin.verify') && !$request->routeIs('pin.check')) {
                    return redirect()->route('pin.verify');
                }
            }
        }

        return $next($request);
    }
}
