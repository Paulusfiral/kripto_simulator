<?php

namespace App\Exceptions;

use Exception;

/**
 * CaesarException — Custom exception untuk Caesar Cipher errors.
 */
class CaesarException extends Exception
{
    public function __construct(
        string $message = 'Caesar Cipher error',
        int $code = 500,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
