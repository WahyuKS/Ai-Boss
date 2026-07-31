<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Services\GeminiService;

class AIBossController extends Controller
{
    public function index()
    {
        return view('ai-boss');
    }

    public function generateActionPlan(Request $request, GeminiService $gemini)
    {
        $request->validate([
            'intent' => 'required|string',
        ]);

        $profile = Auth::user()->businessProfile;
        $brand = $profile ? $profile->brand_name : "Bisnis UMKM";


        $prompt = "Saya adalah pemilik bisnis bernama {$brand}. Tujuan utama saya hari ini adalah: '{$request->intent}'.\n";
        $prompt .= "Berikan tepat 3 langkah teknis (to-do list) singkat dan sangat praktis untuk mencapai tujuan tersebut.\n";
        $prompt .= "ATURAN KETAT: Pisahkan setiap langkah HANYA dengan karakter '|'. Jangan gunakan nomor, bullet point, atau kalimat pembuka/penutup. Contoh output: Buat video promo diskon 20%|Hubungi 5 pelanggan lama di WhatsApp|Evaluasi HPP produk terlaris";

        try {

            $resultText = $gemini->generate($prompt);

            $resultText = str_replace(["\n", "*", "- "], "", $resultText);

            $tasksArray = explode('|', $resultText);

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
        } catch (\Exception $e) {
            return back()->with('error', "Gagal menghubungi API. Detail Error: " . $e->getMessage());
        }
    }
}
