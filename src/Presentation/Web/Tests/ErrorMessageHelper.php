<?php

declare(strict_types=1);

namespace Presentation\Web\Tests;

trait ErrorMessageHelper
{
    protected function getExactServiceUnavailableError(): array
    {
        return $this->getBaseExactError(
            error: 'server_unexpectedError',
            message: 'Something went wrong. Please try again later or contact support'
        );
    }

    protected function getBaseExactError(
        string $error,
        string $message,
        array $additional = [],
        string $errorKey = 'error',
        string $messageKey = 'message'
    ): array {
        return [
            $errorKey => $error,
            $messageKey => $message,
            ...$additional,
        ];
    }
}
