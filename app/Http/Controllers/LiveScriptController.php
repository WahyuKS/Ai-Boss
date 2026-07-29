<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SavedContent;

class LiveScriptController extends Controller
{
    public function index()
    {
        $userId = auth()->id() ?? 1;
        // Ambil data khusus modul Live Script, batasi 3 per halaman
        $templates = SavedContent::where('user_id', $userId)
                        ->where('module_name', 'Live Script')
                        ->latest()
                        ->paginate(2);

        return view('live-script', compact('templates'));
    }

    public function generate(Request $request)
    {
        try {
            $request->validate([
                'nama_produk' => 'required|string',
                'target_penonton' => 'required|string',
                'promo' => 'required|string',
            ]);

            $apiKey = env('GEMINI_API_CREATIVE');

            // Susun instruksi untuk AI sebagai Host Live TikTok
            $prompt = "Bertindaklah sebagai Host TikTok Live / Shopee Live senior yang sangat enerjik, jago jualan (hard selling terselubung), dan bisa menahan penonton agar tidak scroll.\n";
            $prompt .= "Buatkan saya skrip live streaming yang interaktif berdasarkan detail berikut:\n\n";
            $prompt .= "- Nama Produk: " . $request->nama_produk . "\n";
            $prompt .= "- Target Penonton: " . $request->target_penonton . "\n";
            $prompt .= "- Promo Khusus: " . $request->promo . "\n\n";
            $prompt .= "Syarat Output:\n";
            $prompt .= "1. Berikan Hook (5 detik pertama yang bikin orang berhenti scroll).\n";
            $prompt .= "2. Buat sesi interaksi (contoh: 'Yang mau absen dari kota mana aja nih?').\n";
            $prompt .= "3. Cara jualan produknya (highlight masalah penonton & solusi dari produk).\n";
            $prompt .= "4. Call to Action (ajakan klik keranjang kuning dengan urgency/FOMO).\n";
            $prompt .= "5. Format menggunakan tag HTML dasar seperti <br> atau <strong> agar rapi dibaca di web. JANGAN gunakan markdown (**).";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $reply = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Gagal membuat skrip.';
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
                'nama_produk' => 'required|string',
                'target_penonton' => 'required|string',
                'hasil_ai' => 'required|string',
            ]);

            $userId = auth()->id() ?? 1;

            SavedContent::create([
                'user_id' => $userId,
                'module_name' => 'Live Script',
                // Gabungkan Target Penonton & Nama Produk untuk disimpan
                'title' => $request->target_penonton . '||' . $request->nama_produk,
                'content' => $request->hasil_ai,
            ]);

            return response()->json(['success' => true, 'message' => 'Skrip Live berhasil disimpan!']);

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
