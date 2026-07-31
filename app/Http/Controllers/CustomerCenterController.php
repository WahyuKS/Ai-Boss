<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavedContent; // Wajib dipanggil untuk akses database
use App\Services\GeminiService;

class CustomerCenterController extends Controller
{
    public function index()
    {
        // 1. Ambil data template khusus milik user yang sedang login & khusus dari modul 'CS Center'
        $templates = SavedContent::where('user_id', auth()->id())
                        ->where('module_name', 'CS Center')
                        ->latest()
                        ->get();

        // 2. Lempar data tersebut ke halaman Blade
        return view('customer-center', compact('templates'));
    }

    public function generate(Request $request, GeminiService $gemini)
    {
        try {
            $request->validate([
                'judul' => 'required|string',
                'kategori' => 'required|string',
                'pesan_dasar' => 'required|string',
            ]);

            $prompt = "Bertindaklah sebagai Customer Service Manager senior yang ahli dalam komunikasi persuasif, sangat ramah, dan berempati tinggi.\n";
            $prompt .= "Tugas Anda adalah merapikan pesan kasar dari owner menjadi template balasan pelanggan yang sangat profesional dan sopan.\n\n";
            $prompt .= "Konteks Pesan:\n";
            $prompt .= "- Judul Template: " . $request->judul . "\n";
            $prompt .= "- Kategori: " . $request->kategori . "\n";
            $prompt .= "- Pesan Kasar/Inti: " . $request->pesan_dasar . "\n\n";
            $prompt .= "Syarat Output:\n";
            $prompt .= "1. Gunakan bahasa Indonesia yang santai tapi sopan (bisa pakai sapaan 'Kak').\n";
            $prompt .= "2. Buat langsung kalimat balasannya saja, jangan bertele-tele.\n";
            $prompt .= "3. Gunakan tag HTML dasar seperti <br> untuk enter agar rapi saat dibaca di layar.";

            // Dulu: env('GEMINI_API_CREATIVE') 1 key langsung lewat Http::.
            // Sekarang: lewat GeminiService, otomatis rotasi ke key lain kalau kena limit.
            $reply = $gemini->generate($prompt);

            return response()->json([
                'success' => true,
                'hasil_ai' => $reply
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'judul' => 'required|string',
                'kategori' => 'required|string', // Pastikan kategori ditangkap
                'hasil_ai' => 'required|string',
            ]);

            // PENGAMAN: Jika belum login, gunakan ID 1
            $userId = auth()->id() ?? 1;

            \App\Models\SavedContent::create([
                'user_id' => $userId,
                'module_name' => 'CS Center',
                // Trik cerdas: Gabungkan Kategori & Judul dengan pemisah "||"
                'title' => $request->kategori . '||' . $request->judul,
                'content' => $request->hasil_ai,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Template berhasil disimpan!'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal Database: ' . $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {
            // Cari data template berdasarkan ID
            $template = \App\Models\SavedContent::findOrFail($id);

            // Hapus dari database
            $template->delete();

            return response()->json([
                'success' => true,
                'message' => 'Template berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ]);
        }
    }
}
