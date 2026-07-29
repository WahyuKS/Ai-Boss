<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SavedContent;

class BusinessPlaybookController extends Controller
{
    public function index()
    {
        $userId = auth()->id() ?? 1;
        // Ambil data SOP, maksimal 3 per halaman
        $templates = SavedContent::where('user_id', $userId)
                        ->where('module_name', 'Playbook')
                        ->latest()
                        ->paginate(1);

        return view('playbook', compact('templates'));
    }

    public function generate(Request $request)
    {
        try {
            $request->validate([
                'posisi_pekerjaan' => 'required|string',
                'tugas_utama' => 'required|string',
            ]);

            $apiKey = env('GEMINI_API_SYSTEM'); // Menggunakan kunci SYSTEM untuk hal operasional

            // Susun instruksi untuk AI sebagai Manajer Operasional
            $prompt = "Bertindaklah sebagai Manajer Operasional Bisnis senior yang ahli dalam membuat SOP (Standar Operasional Prosedur) yang sangat terstruktur, mudah dipahami karyawan, dan anti-misskomunikasi.\n";
            $prompt .= "Buatkan saya SOP detail untuk detail berikut:\n\n";
            $prompt .= "- Posisi/Jabatan: " . $request->posisi_pekerjaan . "\n";
            $prompt .= "- Tugas Utama yang di-SOP-kan: " . $request->tugas_utama . "\n\n";
            $prompt .= "Syarat Output:\n";
            $prompt .= "1. Berikan Tujuan SOP ini.\n";
            $prompt .= "2. Buat langkah-langkah kerja (Langkah 1, Langkah 2, dst) secara kronologis dan detail.\n";
            $prompt .= "3. Tambahkan indikator keberhasilan (KPI sederhana) untuk tugas ini.\n";
            $prompt .= "4. Format menggunakan tag HTML dasar seperti <br>, <strong>, atau <ul><li> agar rapi dibaca di web. JANGAN gunakan markdown bintang-bintang (**).";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $reply = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Gagal membuat SOP.';
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
                'posisi_pekerjaan' => 'required|string',
                'tugas_utama' => 'required|string',
                'hasil_ai' => 'required|string',
            ]);

            $userId = auth()->id() ?? 1;

            SavedContent::create([
                'user_id' => $userId,
                'module_name' => 'Playbook',
                // Gabungkan Posisi Pekerjaan & Tugas untuk judul database
                'title' => $request->posisi_pekerjaan . '||' . $request->tugas_utama,
                'content' => $request->hasil_ai,
            ]);

            return response()->json(['success' => true, 'message' => 'SOP berhasil disimpan ke Playbook!']);

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
