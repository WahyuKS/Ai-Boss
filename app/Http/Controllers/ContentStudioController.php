<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavedContent;
use App\Services\GeminiService;

class ContentStudioController extends Controller
{
    public function index()
    {
        // Ambil data milik user (jika belum login, anggap ID 1), khusus modul Content Studio
        $userId = auth()->id() ?? 1;
        $templates = SavedContent::where('user_id', $userId)
                        ->where('module_name', 'Content Studio')
                        ->latest()
                        ->paginate(2);

        return view('content-studio', compact('templates'));
    }

    public function generate(Request $request, GeminiService $gemini)
    {
        try {
            $request->validate([
                'topik' => 'required|string',
                'platform' => 'required|string',
                'gaya_bahasa' => 'required|string',
            ]);

            // Susun instruksi untuk AI sebagai Copywriter (SAMA PERSIS seperti sebelumnya)
            $prompt = "Bertindaklah sebagai Copywriter Social Media & Content Creator senior.\n";
            $prompt .= "Buatkan saya naskah konten yang menarik dan viral berdasarkan detail berikut:\n\n";
            $prompt .= "- Topik/Produk: " . $request->topik . "\n";
            $prompt .= "- Platform: " . $request->platform . "\n";
            $prompt .= "- Gaya Bahasa: " . $request->gaya_bahasa . "\n\n";
            $prompt .= "Syarat Output:\n";
            $prompt .= "1. Jika Instagram/TikTok: Berikan ide visual/hook, caption, dan 5 hashtag relevan.\n";
            $prompt .= "2. Jika YouTube/Artikel: Berikan judul clickbait dan struktur skrip/naskahnya.\n";
            $prompt .= "3. Format menggunakan tag HTML dasar seperti <br> atau <strong> agar rapi dibaca di web, JANGAN gunakan format markdown bintang-bintang (**).";

            // Dulu: env('GEMINI_API_CREATIVE') 1 key langsung lewat Http::.
            // Sekarang: lewat GeminiService, otomatis rotasi ke key lain kalau kena limit.
            $reply = $gemini->generate($prompt);

            return response()->json(['success' => true, 'hasil_ai' => $reply]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'topik' => 'required|string',
                'platform' => 'required|string',
                'hasil_ai' => 'required|string',
            ]);

            $userId = auth()->id() ?? 1;

            SavedContent::create([
                'user_id' => $userId,
                'module_name' => 'Content Studio',
                // Trik: Gabungkan Platform (sebagai kategori) & Topik (sebagai judul)
                'title' => $request->platform . '||' . $request->topik,
                'content' => $request->hasil_ai,
            ]);

            return response()->json(['success' => true, 'message' => 'Konten berhasil disimpan!']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal Database: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $template = SavedContent::findOrFail($id);
            $template->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
