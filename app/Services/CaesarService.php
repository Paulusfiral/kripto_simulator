<?php

namespace App\Services;

use App\Exceptions\CaesarException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * CaesarService
 *
 * Berkomunikasi dengan Python FastAPI microservice Caesar Cipher.
 * Mengikuti pola yang sama dengan ChaCha20Service.
 */
class CaesarService
{
    private string $baseUrl;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.caesar.url', 'http://127.0.0.1:8002'), '/');
        $this->timeout = (int) config('services.caesar.timeout', 30);
    }

    /**
     * Enkripsi plaintext menggunakan Caesar Cipher.
     */
    public function encrypt(string $plaintext, int $shift = 3): array
    {
        return $this->post('/encrypt', [
            'plaintext' => $plaintext,
            'shift'     => $shift,
        ]);
    }

    /**
     * Dekripsi ciphertext menggunakan Caesar Cipher.
     */
    public function decrypt(string $ciphertext, int $shift = 3): array
    {
        return $this->post('/decrypt', [
            'ciphertext' => $ciphertext,
            'shift'      => $shift,
        ]);
    }

    private function post(string $path, array $payload): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->acceptJson()
                ->asJson()
                ->post($this->baseUrl . $path, $payload);

            if ($response->successful()) {
                return $response->json();
            }

            $body = $response->json();
            throw new CaesarException(
                $body['detail'] ?? 'Unknown error from Caesar microservice',
                code: $response->status()
            );
        } catch (ConnectionException $e) {
            Log::error("CaesarService: Cannot connect to microservice", [
                'url'   => $this->baseUrl . $path,
                'error' => $e->getMessage(),
            ]);
            throw new CaesarException(
                "Tidak bisa terhubung ke Caesar microservice. Pastikan service Python sedang berjalan.",
                code: 503,
                previous: $e
            );
        }
    }
}
