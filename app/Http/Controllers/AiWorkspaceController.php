<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiWorkspaceController extends Controller
{
    public function index()
    {
        return view('ai-workspace');
    }

    public function chat(Request $request)
    {
        try {
            $request->validate([
                'prompt' => 'required|string',
            ]);

            // Gunakan API Key kreatif, fallback ke sistem jika kosong
            $apiKey = env('GEMINI_API_CREATIVE') ?? env('GEMINI_API_SYSTEM');

            // Perintah dasar untuk memastikan format rapi
            $prompt = $request->prompt;
            $prompt .= "\n\n(Instruksi Sistem: Tolong jawab dengan format yang rapi menggunakan tag HTML dasar seperti <br>, <strong>, atau <ul><li>. JANGAN gunakan markdown bintang-bintang (**).)";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $reply = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak bisa memproses pertanyaan ini.';
                return response()->json(['success' => true, 'hasil_ai' => $reply]);
            }

            // Tangkap pesan error langsung dari Google jika kena limit
            $errorDetail = $response->json('error.message') ?? 'Terjadi gangguan koneksi API.';
            return response()->json(['success' => false, 'message' => 'API Google: ' . $errorDetail]);

        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'System: ' . $e->getMessage()]);
        }
    }
}
