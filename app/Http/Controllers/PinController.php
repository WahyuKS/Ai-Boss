<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PinController extends Controller
{
    // Menampilkan halaman buat PIN baru
    public function showSetup()
    {
        // Kalau sudah punya PIN, langsung tendang ke halaman Verify
        if (auth()->user()->pin) {
            return redirect()->route('pin.verify');
        }
        return view('pin.setup');
    }

    // Menyimpan PIN baru ke database
    public function storeSetup(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:6|confirmed'
        ], [
            'pin.confirmed' => 'Kedua PIN tidak cocok!',
            'pin.digits' => 'PIN wajib berupa 6 angka!'
        ]);

        auth()->user()->update([
            'pin' => Hash::make($request->pin)
        ]);

        // Tandai sesi ini sudah terverifikasi PIN-nya
        session(['pin_verified' => true]);

        return redirect()->route('dashboard');
    }

    // Menampilkan halaman masukkan PIN (Lock Screen)
    public function showVerify()
    {
        // Kalau belum punya PIN, tendang ke halaman Setup
        if (!auth()->user()->pin) {
            return redirect()->route('pin.setup');
        }
        return view('pin.verify');
    }

    // Mengecek apakah PIN yang dimasukkan benar
    public function verify(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:6'
        ]);

        if (Hash::check($request->pin, auth()->user()->pin)) {
            session(['pin_verified' => true]);

            // Redirect ke halaman sebelumnya, atau ke dashboard
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['pin' => 'PIN salah! Silakan coba lagi.']);
    }
}
