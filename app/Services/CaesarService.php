<?php

namespace App\Services;

use App\Exceptions\ChaCha20Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * CaesarService
 *
 * Berkomunikasi dengan Python FastAPI microservice untuk Caesar cipher.
 * Menggunakan URL dari config services.caesar.
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
     * Enkripsi plaintext menggunakan Caesar cipher.
     */
    public function encrypt(string $plaintext, int $shift = 3, bool $showSteps = false): array
    {
        return $this->post('/encrypt', [
            'plaintext' => $plaintext,
            'shift'     => $shift,
        ]);
    }

    /**
     * Dekripsi ciphertext menggunakan Caesar cipher.
     */
    public function decrypt(string $ciphertext, int $shift = 3, bool $showSteps = false): array
    {
        return $this->post('/decrypt', [
            'ciphertext' => $ciphertext,
            'shift'      => $shift,
        ]);
    }

    /**
     * Brute force — coba semua 26 shift.
     */
    public function bruteForce(string $ciphertext): array
    {
        $decrypts = [];
        for ($shift = 0; $shift < 26; $shift++) {
            $result = $this->decrypt($ciphertext, $shift, false);
            $decrypts[] = [
                'shift'     => $shift,
                'plaintext' => $result['plaintext'] ?? '',
            ];
        }
        return ['results' => $decrypts];
    }

    /**
     * Ambil tabel alfabet yang sudah di-shift.
     */
    public function shiftTable(int $shift = 3): array
    {
        $shift = $shift % 26;
        $original = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shifted = substr($original, $shift) . substr($original, 0, $shift);
        $mapping = [];
        for ($i = 0; $i < 26; $i++) {
            $mapping[] = [
                'from'     => $original[$i],
                'to'       => $shifted[$i],
                'position' => $i,
            ];
        }
        return [
            'shift'    => $shift,
            'original' => $original,
            'shifted'  => $shifted,
            'mapping'  => $mapping,
        ];
    }

    public function transformText(string $text, string $operation, string $search = '', string $replace = ''): array
    {
        switch ($operation) {
            case 'reverse':
                $transformed = $this->mbStrRev($text);
                break;
            case 'uppercase':
                $transformed = mb_strtoupper($text);
                break;
            case 'lowercase':
                $transformed = mb_strtolower($text);
                break;
            case 'replace':
                if ($search === '') {
                    throw new ChaCha20Exception('Search string tidak boleh kosong untuk operasi replace.', code: 422);
                }
                $transformed = str_replace($search, $replace, $text);
                break;
            default:
                throw new ChaCha20Exception('Operasi transformasi tidak dikenal.', code: 422);
        }

        return [
            'operation'   => $operation,
            'text'        => $text,
            'transformed' => $transformed,
        ];
    }

    public function spellingAlphabet(string $text): array
    {
        $alphabet = [
            'A' => 'Alfa',   'B' => 'Bravo',   'C' => 'Charlie', 'D' => 'Delta',   'E' => 'Echo',
            'F' => 'Foxtrot','G' => 'Golf',    'H' => 'Hotel',   'I' => 'India',   'J' => 'Juliett',
            'K' => 'Kilo',   'L' => 'Lima',    'M' => 'Mike',    'N' => 'November','O' => 'Oscar',
            'P' => 'Papa',   'Q' => 'Quebec',  'R' => 'Romeo',   'S' => 'Sierra',  'T' => 'Tango',
            'U' => 'Uniform','V' => 'Victor',  'W' => 'Whiskey', 'X' => 'X-ray',   'Y' => 'Yankee',
            'Z' => 'Zulu',   '0' => 'Zero',    '1' => 'One',     '2' => 'Two',     '3' => 'Three',
            '4' => 'Four',   '5' => 'Five',    '6' => 'Six',     '7' => 'Seven',   '8' => 'Eight',
            '9' => 'Nine',
        ];

        $mapping = [];
        $characters = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($characters as $character) {
            $upper = mb_strtoupper($character);
            $mapping[] = [
                'char' => $character,
                'word' => $alphabet[$upper] ?? $character,
            ];
        }

        return [
            'text'    => $text,
            'mapping' => $mapping,
        ];
    }

    private function mbStrRev(string $string): string
    {
        $characters = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
        return implode('', array_reverse($characters));
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

    private function post(string $path, array $payload): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->acceptJson()
                ->asJson()
                ->post($this->baseUrl . $path, $payload);

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
