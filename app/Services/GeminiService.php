<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * GeminiService
 * --------------------------------------------------
 * Rotasi beberapa API key Gemini + retry/fallback otomatis.
 *
 * PENTING (beda dari Node.js): di Laravel/PHP, setiap request
 * biasanya dijalankan di proses baru (lewat Apache/XAMPP), jadi
 * variabel PHP biasa TIDAK bisa dipakai untuk "mengingat" key mana
 * yang sedang cooldown antar-request. Makanya status cooldown
 * di sini disimpan pakai Cache (file cache bawaan Laravel, sudah
 * otomatis aktif tanpa setup tambahan di XAMPP).
 */
class GeminiService
{
    /** @var array<int, string> */
    protected array $keys;

    protected string $model;

    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    // Berapa lama (detik) sebuah key "diistirahatkan" setelah kena rate limit
    protected int $defaultCooldownSeconds = 60;

    public function __construct()
    {
        $this->keys = config('services.gemini.keys', []);
        $this->model = config('services.gemini.model', 'gemini-2.5-flash');

        if (empty($this->keys)) {
            throw new Exception(
                'Tidak ada API key Gemini. Cek GEMINI_API_KEYS di file .env'
            );
        }
    }

    /**
     * Cek apakah sebuah key sedang cooldown (baru saja kena limit).
     */
    protected function isCoolingDown(string $key): bool
    {
        return Cache::has($this->cooldownCacheKey($key));
    }

    /**
     * Tandai sebuah key sedang cooldown selama $seconds.
     */
    protected function markCooldown(string $key, int $seconds): void
    {
        Cache::put($this->cooldownCacheKey($key), true, now()->addSeconds($seconds));
    }

    protected function cooldownCacheKey(string $key): string
    {
        // Pakai potongan terakhir key saja di nama cache (bukan key penuh), lebih aman.
        return 'gemini_cooldown_' . substr($key, -6);
    }

    /**
     * Ambil daftar key yang sedang TIDAK cooldown.
     *
     * @return array<int, string>
     */
    protected function getAvailableKeys(): array
    {
        return array_values(array_filter(
            $this->keys,
            fn (string $key) => ! $this->isCoolingDown($key)
        ));
    }

    /**
     * Kirim prompt ke Gemini, otomatis pindah key kalau kena rate limit.
     *
     * @throws Exception kalau semua key gagal
     */
    public function generate(string $prompt): string
    {
        $maxAttempts = count($this->keys) * 2; // tiap key dicoba maksimal 2x muter
        $lastError = null;
        $anyRealRateLimit = false; // true hanya kalau benar-benar ada 429 dari Google

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $available = $this->getAvailableKeys();

            if (empty($available)) {
                // Semua key sedang cooldown. Kalau penyebabnya BUKAN rate limit asli
                // (misal key ditolak/invalid), tampilkan pesan error yang sebenarnya,
                // jangan disamaratakan jadi "rate-limited".
                if ($anyRealRateLimit) {
                    throw new Exception(
                        'Semua API key Gemini sedang rate-limited. Coba lagi sebentar lagi.'
                    );
                }
                throw new Exception($lastError ?? 'Semua API key gagal dipakai, penyebab tidak diketahui.');
            }

            $key = $available[0];

            try {
                $response = Http::timeout(30)
                    ->post("{$this->baseUrl}/{$this->model}:generateContent?key={$key}", [
                        'contents' => [
                            ['parts' => [['text' => $prompt]]],
                        ],
                    ]);

                if ($response->status() === 429) {
                    // Key ini BENAR-BENAR kena rate limit dari Google.
                    $anyRealRateLimit = true;
                    $retryAfter = $response->header('Retry-After');
                    $cooldown = $retryAfter ? (int) $retryAfter : $this->defaultCooldownSeconds;

                    $this->markCooldown($key, $cooldown);
                    $lastError = "Key ...{$this->shortKey($key)} kena 429 (rate limit asli)";
                    Log::warning("Gemini rate limit: {$lastError}");
                    continue;
                }

                if ($response->serverError()) {
                    // Error di sisi Google, cooldown singkat saja.
                    $this->markCooldown($key, 5);
                    $lastError = "Server error {$response->status()}: {$response->body()}";
                    Log::warning("Gemini server error: {$lastError}");
                    continue;
                }

                if ($response->failed()) {
                    // Error lain (400/401/403/dst) — BUKAN rate limit, biasanya
                    // key invalid/ditolak/salah project. Tetap dicoba key lain
                    // (siapa tahu cuma 1 key yang bermasalah), tapi pesan error
                    // ASLI dari Google disimpan supaya kelihatan jelas.
                    $this->markCooldown($key, 5);
                    $lastError = "Key ...{$this->shortKey($key)} ditolak, status {$response->status()}: {$response->body()}";
                    Log::error("Gemini key rejected: {$lastError}");
                    continue;
                }

                $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

                if (! $text) {
                    throw new Exception('Respons kosong / format tidak sesuai dugaan.');
                }

                return $text;
            } catch (Exception $e) {
                $lastError = $e->getMessage();
                $this->markCooldown($key, 5);
                Log::error("Gemini call gagal: {$lastError}");
            }
        }

        throw new Exception($lastError ?? 'Gagal memanggil Gemini setelah beberapa percobaan.');
    }

    protected function shortKey(string $key): string
    {
        return substr($key, -4);
    }
}
