<?php

namespace App\Exception;

use Throwable;

class EmptyBodyException extends \Exception
{
    public function __construct(
        string $message = "The body of the POST method cannot be empty",
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
