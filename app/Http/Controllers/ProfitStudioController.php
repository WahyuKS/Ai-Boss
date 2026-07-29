<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SavedContent;

class ProfitStudioController extends Controller
{
    public function index()
    {
        $userId = auth()->id() ?? 1;
        $templates = SavedContent::where('user_id', $userId)
                        ->where('module_name', 'Profit Studio')
                        ->latest()
                        ->paginate(2);

        return view('profit-studio', compact('templates'));
    }

    public function generate(Request $request)
    {
        try {
            // Validasi dilonggarkan agar tidak crash jika ada form yang dikosongkan
            $request->validate([
                'nama_produk' => 'required|string',
                'target_pasar' => 'required|string',
            ]);

            // Gunakan API Key utama, jika gagal/kosong otomatis pakai API kreatif
            $apiKey = env('GEMINI_API_SYSTEM') ?? env('GEMINI_API_CREATIVE');

            // PENGAMAN ANGKA: Konversi ke angka secara paksa untuk menghindari error tipe data
            $modal = is_numeric($request->modal_awal) ? (float) $request->modal_awal : 0;
            $ongkir = is_numeric($request->ongkir_impor) ? (float) $request->ongkir_impor : 0;
            $admin = is_numeric($request->biaya_admin) ? (float) $request->biaya_admin : 0;

            $prompt = "Bertindaklah sebagai Konsultan Keuangan Bisnis & CFO ahli.\n";
            $prompt .= "Tolong buatkan analisa harga jual dan strategi profit untuk produk berikut:\n\n";
            $prompt .= "- Nama Produk: " . $request->nama_produk . "\n";
            $prompt .= "- Harga Beli Produk (Modal Murni): Rp " . number_format($modal, 0, ',', '.') . "\n";
            $prompt .= "- Biaya Ongkir Impor/Kargo (Per Item): Rp " . number_format($ongkir, 0, ',', '.') . "\n";
            $prompt .= "- Potongan Biaya Admin Marketplace: " . $admin . "%\n";
            $prompt .= "- Target Pasar: " . $request->target_pasar . "\n\n";
            $prompt .= "Syarat Output:\n";
            $prompt .= "1. Hitung total HPP (Harga Pokok Penjualan) sebenarnya (Modal Murni + Ongkir Impor).\n";
            $prompt .= "2. Rekomendasi Harga Jual (berikan 3 opsi: Harga Core, Harga Promo, Harga Bundling) dan pastikan harganya sudah meng-cover potongan admin marketplace " . $admin . "%.\n";
            $prompt .= "3. Rincian Estimasi Margin Keuntungan Bersih (setelah dipotong modal, ongkir impor, dan admin marketplace).\n";
            $prompt .= "4. Strategi Psikologi Harga (Misal: pakai angka 99.000, coret harga, dsb).\n";
            $prompt .= "5. Format wajib menggunakan tag HTML dasar seperti <br>, <strong>, atau <ul><li>. JANGAN gunakan markdown bintang-bintang (**).";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $reply = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Gagal membuat Analisa. Coba kata kunci lain.';
                return response()->json(['success' => true, 'hasil_ai' => $reply]);
            }

            // TANGKAP ERROR GOOGLE: Menampilkan alasan asli kenapa gagal dari server Google
            $errorDetail = $response->json('error.message') ?? 'Terjadi gangguan koneksi ke server AI.';
            return response()->json(['success' => false, 'message' => 'API Google: ' . $errorDetail]);

        } catch (\Throwable $e) {
            // Tangkap semua jenis error sistem (termasuk error typo coding)
            return response()->json(['success' => false, 'message' => 'System: ' . $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_produk' => 'required|string',
                'target_pasar' => 'required|string',
                'hasil_ai' => 'required|string',
            ]);

            $userId = auth()->id() ?? 1;

            SavedContent::create([
                'user_id' => $userId,
                'module_name' => 'Profit Studio',
                'title' => $request->target_pasar . '||' . $request->nama_produk,
                'content' => $request->hasil_ai,
            ]);

            return response()->json(['success' => true, 'message' => 'Analisa Keuangan berhasil disimpan!']);

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
