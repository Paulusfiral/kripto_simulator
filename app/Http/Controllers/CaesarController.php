<?php

namespace App\Http\Controllers;

use App\Services\CaesarService;
use App\Exceptions\CaesarException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * CaesarController
 *
 * Controller untuk Caesar Cipher simulator.
 * Mengikuti pola yang sama dengan ChaCha20Controller.
 *
 * TODO: Tim Caesar, sesuaikan method-method ini sesuai kebutuhan kalian.
 */
class CaesarController extends Controller
{
    public function __construct(
        private CaesarService $caesarService
    ) {}

    /**
     * Halaman simulator Caesar Cipher.
     */
    public function index()
    {
        return view('caesar.index', [
            'apiUrl' => config('services.caesar.url'),
        ]);
    }

    /**
     * Encrypt plaintext.
     */
    public function encrypt(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plaintext' => 'required|string',
            'shift'     => 'integer|min:1|max:25',
        ]);

        try {
            $result = $this->caesarService->encrypt(
                plaintext: $validated['plaintext'],
                shift: $validated['shift'] ?? 3,
            );
            return response()->json($result);
        } catch (CaesarException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    /**
     * Decrypt ciphertext.
     */
    public function decrypt(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ciphertext' => 'required|string',
            'shift'      => 'integer|min:1|max:25',
        ]);

        try {
            $result = $this->caesarService->decrypt(
                ciphertext: $validated['ciphertext'],
                shift: $validated['shift'] ?? 3,
            );
            return response()->json($result);
        } catch (CaesarException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
