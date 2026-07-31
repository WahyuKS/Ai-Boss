<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavedContent;
use App\Services\GeminiService;

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

    public function generate(Request $request, GeminiService $gemini)
    {
        try {
            $request->validate([
                'posisi_pekerjaan' => 'required|string',
                'tugas_utama' => 'required|string',
            ]);

            // Susun instruksi untuk AI sebagai Manajer Operasional (SAMA PERSIS seperti sebelumnya)
            $prompt = "Bertindaklah sebagai Manajer Operasional Bisnis senior yang ahli dalam membuat SOP (Standar Operasional Prosedur) yang sangat terstruktur, mudah dipahami karyawan, dan anti-misskomunikasi.\n";
            $prompt .= "Buatkan saya SOP detail untuk detail berikut:\n\n";
            $prompt .= "- Posisi/Jabatan: " . $request->posisi_pekerjaan . "\n";
            $prompt .= "- Tugas Utama yang di-SOP-kan: " . $request->tugas_utama . "\n\n";
            $prompt .= "Syarat Output:\n";
            $prompt .= "1. Berikan Tujuan SOP ini.\n";
            $prompt .= "2. Buat langkah-langkah kerja (Langkah 1, Langkah 2, dst) secara kronologis dan detail.\n";
            $prompt .= "3. Tambahkan indikator keberhasilan (KPI sederhana) untuk tugas ini.\n";
            $prompt .= "4. Format menggunakan tag HTML dasar seperti <br>, <strong>, atau <ul><li> agar rapi dibaca di web. JANGAN gunakan markdown bintang-bintang (**).";

            // Dulu: env('GEMINI_API_SYSTEM') 1 key langsung lewat Http::.
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
