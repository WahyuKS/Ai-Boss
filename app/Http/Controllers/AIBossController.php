<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class AIBossController extends Controller
{
    public function index()
    {
        return view('ai-boss');
    }

    public function generateActionPlan(Request $request)
    {
        $request->validate([
            'intent' => 'required|string',
        ]);

        $apiKey = env('GEMINI_API_DASHBOARD');
        if (empty($apiKey)) {
            return back()->with('error', 'API Key belum diisi di file .env!');
        }

        // Mengambil nama model dari .env, fallback ke 'gemini-flash-latest' jika kosong
        $model = env('GEMINI_MODEL', 'gemini-flash-latest');

        $profile = Auth::user()->businessProfile;
        $brand = $profile ? $profile->brand_name : "Bisnis UMKM";

        // Prompt khusus
        $prompt = "Saya adalah pemilik bisnis bernama {$brand}. Tujuan utama saya hari ini adalah: '{$request->intent}'.\n";
        $prompt .= "Berikan tepat 3 langkah teknis (to-do list) singkat dan sangat praktis untuk mencapai tujuan tersebut.\n";
        $prompt .= "ATURAN KETAT: Pisahkan setiap langkah HANYA dengan karakter '|'. Jangan gunakan nomor, bullet point, atau kalimat pembuka/penutup. Contoh output: Buat video promo diskon 20%|Hubungi 5 pelanggan lama di WhatsApp|Evaluasi HPP produk terlaris";

        try {
            // URL sekarang sudah dinamis menggunakan variabel {$model}
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
                'contents' => [['parts' => [['text' => $prompt]]]]
            ]);

            if ($response->successful()) {
                $resultText = $response->json('candidates.0.content.parts.0.text');

                // Membersihkan karakter aneh jika AI membandel
                $resultText = str_replace(["\n", "*", "- "], "", $resultText);

                // Memecah teks berdasarkan karakter "|"
                $tasksArray = explode('|', $resultText);

                // Menyimpan setiap baris menjadi tugas di Dashboard
                foreach ($tasksArray as $taskTitle) {
                    if (trim($taskTitle) !== '') {
                        Task::create([
                            'user_id' => Auth::id(),
                            'title' => trim($taskTitle),
                            'is_completed' => false,
                        ]);
                    }
                }

                return redirect()->route('dashboard')->with('success', 'Action Plan berhasil dirumuskan! Silakan cek tugas Anda hari ini.');
            } else {
                return back()->with('error', "Gagal menghubungi API. Detail Error: " . $response->body());
            }
        } catch (\Exception $e) {
            return back()->with('error', "Koneksi Error: " . $e->getMessage());
        }
    }
}
