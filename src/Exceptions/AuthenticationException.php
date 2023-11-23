<?php

namespace Sankhya\Exceptions;

use Exception;

class AuthenticationException extends Exception
{
//    protected $message = 'The provided API key is incorrect. Please try again.';

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
