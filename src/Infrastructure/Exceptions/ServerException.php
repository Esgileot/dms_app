<?php

declare(strict_types=1);

namespace Infrastructure\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class ServerException extends Exception
{
    public function __construct(string $message = '', int $code = 500, ?Throwable $previous = null)
    {
        $this->logTrace($previous);

        parent::__construct($message, $code, $previous);
    }

    protected function logTrace(?Throwable $e): void
    {
        if ($e !== null) {
            Log::error("{$e->getMessage()}. {$e->getTraceAsString()}");
        }
    }
}
