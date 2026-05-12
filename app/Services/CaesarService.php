<?php

namespace App\Services;

<<<<<<< HEAD
use App\Exceptions\CaesarException;
=======
use App\Exceptions\ChaCha20Exception;
>>>>>>> main
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * CaesarService
 *
<<<<<<< HEAD
 * Berkomunikasi dengan Python FastAPI microservice Caesar Cipher.
 * Mengikuti pola yang sama dengan ChaCha20Service.
=======
 * Berkomunikasi dengan Python FastAPI microservice untuk Caesar cipher.
 * Menggunakan URL yang sama dengan ChaCha20 (CHACHA20_SERVICE_URL).
>>>>>>> main
 */
class CaesarService
{
    private string $baseUrl;
    private int $timeout;

    public function __construct()
    {
<<<<<<< HEAD
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
=======
        $this->baseUrl = rtrim(config('services.chacha20.url', 'http://python:8001'), '/');
        $this->timeout = (int) config('services.chacha20.timeout', 30);
    }

    /**
     * Enkripsi plaintext menggunakan Caesar cipher.
     */
    public function encrypt(string $plaintext, int $shift = 3, bool $showSteps = false): array
    {
        return $this->post('/caesar/encrypt', [
            'plaintext'  => $plaintext,
            'shift'      => $shift,
            'show_steps' => $showSteps,
>>>>>>> main
        ]);
    }

    /**
<<<<<<< HEAD
     * Dekripsi ciphertext menggunakan Caesar Cipher.
     */
    public function decrypt(string $ciphertext, int $shift = 3): array
    {
        return $this->post('/decrypt', [
            'ciphertext' => $ciphertext,
            'shift'      => $shift,
        ]);
    }

=======
     * Dekripsi ciphertext menggunakan Caesar cipher.
     */
    public function decrypt(string $ciphertext, int $shift = 3, bool $showSteps = false): array
    {
        return $this->post('/caesar/decrypt', [
            'ciphertext' => $ciphertext,
            'shift'      => $shift,
            'show_steps' => $showSteps,
        ]);
    }

    /**
     * Brute force — coba semua 26 shift.
     */
    public function bruteForce(string $ciphertext): array
    {
        return $this->post('/caesar/brute-force', [
            'ciphertext' => $ciphertext,
        ]);
    }

    /**
     * Ambil tabel alfabet yang sudah di-shift.
     */
    public function shiftTable(int $shift = 3): array
    {
        return $this->get("/caesar/shift-table?shift={$shift}");
    }

    // ─────────────────────────────────────────────
    //  HTTP Helpers
    // ─────────────────────────────────────────────

    private function get(string $path): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->acceptJson()
                ->get($this->baseUrl . $path);

            return $this->handleResponse($response, $path);
        } catch (ConnectionException $e) {
            Log::error("CaesarService: Cannot connect to microservice", [
                'url'   => $this->baseUrl . $path,
                'error' => $e->getMessage(),
            ]);
            throw new ChaCha20Exception(
                "Tidak bisa terhubung ke microservice. Pastikan service Python sedang berjalan.",
                code: 503,
                previous: $e
            );
        }
    }

>>>>>>> main
    private function post(string $path, array $payload): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->acceptJson()
                ->asJson()
                ->post($this->baseUrl . $path, $payload);

<<<<<<< HEAD
            if ($response->successful()) {
                return $response->json();
            }

            $body = $response->json();
            throw new CaesarException(
                $body['detail'] ?? 'Unknown error from Caesar microservice',
                code: $response->status()
            );
=======
            return $this->handleResponse($response, $path);
>>>>>>> main
        } catch (ConnectionException $e) {
            Log::error("CaesarService: Cannot connect to microservice", [
                'url'   => $this->baseUrl . $path,
                'error' => $e->getMessage(),
            ]);
<<<<<<< HEAD
            throw new CaesarException(
                "Tidak bisa terhubung ke Caesar microservice. Pastikan service Python sedang berjalan.",
=======
            throw new ChaCha20Exception(
                "Tidak bisa terhubung ke microservice. Pastikan service Python sedang berjalan.",
>>>>>>> main
                code: 503,
                previous: $e
            );
        }
    }
<<<<<<< HEAD
}

=======

    private function handleResponse(\Illuminate\Http\Client\Response $response, string $path): array
    {
        if ($response->successful()) {
            return $response->json();
        }

        $body = $response->json();
        $detail = $body['detail'] ?? 'Unknown error from microservice';

        Log::warning("CaesarService: API error", [
            'path'   => $path,
            'status' => $response->status(),
            'body'   => $body,
        ]);

        throw new ChaCha20Exception(
            is_string($detail) ? $detail : json_encode($detail),
            apiError: $body,
            code: $response->status()
        );
    }
}
>>>>>>> main
