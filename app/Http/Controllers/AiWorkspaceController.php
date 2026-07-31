<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class AiWorkspaceController extends Controller
{
    public function index()
    {
        return view('ai-workspace');
    }

    public function chat(Request $request, GeminiService $gemini)
    {
        try {
            $request->validate([
                'prompt' => 'required|string',
            ]);


            $prompt = $request->prompt;
            $prompt .= "\n\n(Instruksi Sistem: Tolong jawab dengan format yang rapi menggunakan tag HTML dasar seperti <br>, <strong>, atau <ul><li>. JANGAN gunakan markdown bintang-bintang (**).)";


            $reply = $gemini->generate($prompt);

            return response()->json(['success' => true, 'hasil_ai' => $reply]);

        } catch (\Throwable $e) {

            return response()->json(['success' => false, 'message' => 'System: ' . $e->getMessage()]);
        }
    }
}
