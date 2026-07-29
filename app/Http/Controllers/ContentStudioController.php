<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SavedContent;

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

    public function generate(Request $request)
    {
        try {
            $request->validate([
                'topik' => 'required|string',
                'platform' => 'required|string',
                'gaya_bahasa' => 'required|string',
            ]);

            $apiKey = env('GEMINI_API_CREATIVE');

            // Susun instruksi untuk AI sebagai Copywriter
            $prompt = "Bertindaklah sebagai Copywriter Social Media & Content Creator senior.\n";
            $prompt .= "Buatkan saya naskah konten yang menarik dan viral berdasarkan detail berikut:\n\n";
            $prompt .= "- Topik/Produk: " . $request->topik . "\n";
            $prompt .= "- Platform: " . $request->platform . "\n";
            $prompt .= "- Gaya Bahasa: " . $request->gaya_bahasa . "\n\n";
            $prompt .= "Syarat Output:\n";
            $prompt .= "1. Jika Instagram/TikTok: Berikan ide visual/hook, caption, dan 5 hashtag relevan.\n";
            $prompt .= "2. Jika YouTube/Artikel: Berikan judul clickbait dan struktur skrip/naskahnya.\n";
            $prompt .= "3. Format menggunakan tag HTML dasar seperti <br> atau <strong> agar rapi dibaca di web, JANGAN gunakan format markdown bintang-bintang (**).";

            // Menggunakan gemini-flash-latest agar cepat dan tidak kena limit
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $reply = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Gagal membuat konten.';

                return response()->json(['success' => true, 'hasil_ai' => $reply]);
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
