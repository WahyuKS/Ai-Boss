<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SavedContent;

class WorkflowController extends Controller
{
    public function index()
    {
        $userId = auth()->id() ?? 1;
        // Ambil data Workflow, maksimal 3 per halaman
        $templates = SavedContent::where('user_id', $userId)
                        ->where('module_name', 'Workflow')
                        ->latest()
                        ->paginate(2);

        return view('workflow', compact('templates'));
    }

    public function generate(Request $request)
    {
        try {
            $request->validate([
                'nama_proses' => 'required|string',
                'deskripsi' => 'required|string',
            ]);

            $apiKey = env('GEMINI_API_SYSTEM');

            // Susun instruksi untuk AI sebagai System Architect
            $prompt = "Bertindaklah sebagai Arsitek Sistem Bisnis & Pakar Otomatisasi (seperti ahli Zapier/Make.com).\n";
            $prompt .= "Tugas Anda adalah merancang alur kerja (workflow) otomatis untuk proses bisnis berikut:\n\n";
            $prompt .= "- Nama Proses: " . $request->nama_proses . "\n";
            $prompt .= "- Kondisi/Deskripsi: " . $request->deskripsi . "\n\n";
            $prompt .= "Syarat Output:\n";
            $prompt .= "1. Tentukan TRIGGER (Pemicu) utama dari alur ini.\n";
            $prompt .= "2. Buat langkah-langkah ACTIONS (Tindakan Sistem) secara berurutan (Kondisi Jika A, maka B).\n";
            $prompt .= "3. Rekomendasikan 2-3 Tools/Software yang cocok digunakan (misal: Mailchimp, WhatsApp API, Google Sheets, dll).\n";
            $prompt .= "4. Format wajib menggunakan tag HTML dasar seperti <br>, <strong>, atau <ul><li> agar rapi dibaca di web. JANGAN gunakan markdown bintang-bintang (**).";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $reply = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Gagal membuat Workflow.';
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
                'nama_proses' => 'required|string',
                'hasil_ai' => 'required|string',
            ]);

            $userId = auth()->id() ?? 1;

            SavedContent::create([
                'user_id' => $userId,
                'module_name' => 'Workflow',
                // Simpan nama proses sebagai judul
                'title' => 'Otomatisasi||' . $request->nama_proses,
                'content' => $request->hasil_ai,
            ]);

            return response()->json(['success' => true, 'message' => 'Workflow berhasil disimpan!']);

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
