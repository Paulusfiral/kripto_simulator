<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
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
=======
use App\Exceptions\ChaCha20Exception;
use App\Services\CaesarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CaesarController extends Controller
{
    public function __construct(
        private readonly CaesarService $caesar
    ) {}

    /**
     * Tampilkan halaman simulator Caesar.
     */
    public function index(): \Illuminate\View\View
    {
        return view('caesar.index');
    }

    /**
     * POST /caesar/encrypt
     */
    public function encrypt(Request $request): JsonResponse
    {
        $request->validate([
            'plaintext'  => ['required', 'string', 'min:1', 'max:10000'],
            'shift'      => ['required', 'integer', 'min:0', 'max:25'],
            'show_steps' => ['nullable', 'boolean'],
        ]);

        try {
            $result = $this->caesar->encrypt(
                $request->input('plaintext'),
                (int) $request->input('shift', 3),
                (bool) $request->input('show_steps', false),
            );
            return response()->json($result);
        } catch (ChaCha20Exception $e) {
            return $this->errorResponse($e);
>>>>>>> main
        }
    }

    /**
<<<<<<< HEAD
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
=======
     * POST /caesar/decrypt
     */
    public function decrypt(Request $request): JsonResponse
    {
        $request->validate([
            'ciphertext' => ['required', 'string', 'min:1', 'max:10000'],
            'shift'      => ['required', 'integer', 'min:0', 'max:25'],
            'show_steps' => ['nullable', 'boolean'],
        ]);

        try {
            $result = $this->caesar->decrypt(
                $request->input('ciphertext'),
                (int) $request->input('shift', 3),
                (bool) $request->input('show_steps', false),
            );
            return response()->json($result);
        } catch (ChaCha20Exception $e) {
            return $this->errorResponse($e);
        }
    }

    /**
     * POST /caesar/brute-force
     */
    public function bruteForce(Request $request): JsonResponse
    {
        $request->validate([
            'ciphertext' => ['required', 'string', 'min:1', 'max:10000'],
        ]);

        try {
            $result = $this->caesar->bruteForce(
                $request->input('ciphertext'),
            );
            return response()->json($result);
        } catch (ChaCha20Exception $e) {
            return $this->errorResponse($e);
        }
    }

    /**
     * GET /caesar/shift-table
     */
    public function shiftTable(Request $request): JsonResponse
    {
        try {
            $result = $this->caesar->shiftTable(
                (int) $request->input('shift', 3),
            );
            return response()->json($result);
        } catch (ChaCha20Exception $e) {
            return $this->errorResponse($e);
        }
    }

    private function errorResponse(ChaCha20Exception $e): JsonResponse
    {
        $status = $e->getCode() ?: 500;
        if ($status < 100 || $status > 599) {
            $status = 500;
        }

        return response()->json([
            'error'   => true,
            'message' => $e->getMessage(),
        ], $status);
    }
>>>>>>> main
}
