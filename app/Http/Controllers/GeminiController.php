<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GeminiController extends Controller
{
    public function ringkasFile(Request $request, GeminiService $gemini): JsonResponse
    {
        $request->validate([
            'text' => 'required|string',
        ]);

        try {
            $hasil = $gemini->generate(
                "Ringkas teks berikut dalam 3 kalimat:\n\n{$request->input('text')}"
            );

            return response()->json(['ringkasan' => $hasil]);
        } catch (\Exception $e) {
            // Fallback yang ramah ke user, bukan error mentah.
            report($e);

            return response()->json([
                'error' => 'Layanan sedang sibuk, coba beberapa saat lagi.',
            ], 503);
        }
    }
}
