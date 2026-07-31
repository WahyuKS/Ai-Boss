<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavedContent;
use App\Services\GeminiService;

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

    public function generate(Request $request, GeminiService $gemini)
    {
        try {
            $request->validate([
                'nama_produk' => 'required|string',
                'target_penonton' => 'required|string',
                'promo' => 'required|string',
            ]);

            // Susun instruksi untuk AI sebagai Host Live TikTok (SAMA PERSIS seperti sebelumnya)
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
