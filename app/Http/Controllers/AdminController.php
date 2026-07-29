<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\SavedContent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // ==========================================
    // 1. LOGIN ADMIN
    // ==========================================
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login
        if (Auth::attempt($credentials)) {
            // Jika berhasil, cek apakah dia admin?
            if (Auth::user()->is_admin) {
                $request->session()->regenerate();
                return redirect()->route('admin.users');
            } else {
                // Kalau bukan admin, paksa logout dan tolak!
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akses ditolak! Akun Anda bukan Master Admin.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // ==========================================
    // 2. PIN KEAMANAN MASTER ADMIN
    // ==========================================
    public function showPinForm()
    {
        // Pastikan yang bisa lihat form PIN ini cuma Admin yang sudah berhasil login
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect()->route('admin.login.form');
        }
        return view('admin.pin');
    }

    public function verifyPin(Request $request)
    {
        $request->validate(['pin' => 'required']);

        // 👑 SETTING PIN MASTER ANDA DI SINI (Contoh: 888888)
        $masterPin = '888888';

        if ($request->pin === $masterPin) {
            // Jika PIN benar, berikan stempel lulus di sesi ini
            session(['admin_pin_verified' => true]);
            return redirect()->route('admin.users');
        }

        return back()->withErrors(['pin' => 'PIN Master salah! Akses ditolak.']);
    }

    // ==========================================
    // 3. FITUR MANAJEMEN PANEL ADMIN
    // ==========================================
    public function index(Request $request)
    {
        $query = User::with('businessProfile')->withCount('savedContents');

        // --- ALGORITMA 1: Pencarian Akun (Nama / Email) ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // --- ALGORITMA 2: Filter Tanggal Daftar ---
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Ambil data (ditambah withQueryString agar saat ganti halaman, hasil search tidak hilang)
        $users = $query->latest()->paginate(10)->withQueryString();

        $totalUsers = User::count();
        $totalAdmins = User::where('is_admin', true)->count();
        $totalBisnis = \App\Models\BusinessProfile::count();

        return view('admin.users', compact('users', 'totalUsers', 'totalAdmins', 'totalBisnis'));
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make('password123')]);
        return back()->with('success', 'Password ' . $user->name . ' direset jadi: password123');
    }

    public function resetPin($id)
    {
        $user = User::findOrFail($id);
        $user->update(['pin' => null]);
        return back()->with('success', 'PIN ' . $user->name . ' berhasil dihapus.');
    }

    public function updateBusiness(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);
        $user = User::findOrFail($id);
        $user->update(['name' => $request->name, 'email' => $request->email]);

        if ($request->filled('nama_bisnis')) {
            \App\Models\BusinessProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_bisnis' => $request->nama_bisnis,
                    'platform_utama' => $request->platform_utama ?? '-',
                    'tipe_bisnis' => $request->tipe_bisnis ?? '-',
                ]
            );
        }
        return back()->with('success', 'Data ' . $user->name . ' diperbarui!');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        Task::where('user_id', $user->id)->delete();
        SavedContent::where('user_id', $user->id)->delete();
        if ($user->businessProfile) $user->businessProfile->delete();
        $user->delete();
        return back()->with('success', 'Akun ' . $user->name . ' musnah permanen.');
    }
}
