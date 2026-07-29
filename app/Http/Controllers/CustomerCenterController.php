<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SavedContent; // Wajib dipanggil untuk akses database

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

    public function generate(Request $request)
    {
        try {
            $request->validate([
                'judul' => 'required|string',
                'kategori' => 'required|string',
                'pesan_dasar' => 'required|string',
            ]);

            $apiKey = env('GEMINI_API_CREATIVE');

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

            // Tembak ke API Google menggunakan model flash-latest sesuai kesepakatan
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $reply = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Gagal membuat template balasan.';

                return response()->json([
                    'success' => true,
                    'hasil_ai' => $reply
                ]);
            }

            return response()->json(['success' => false, 'message' => 'API Error']);

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
