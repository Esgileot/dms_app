<?php

declare(strict_types=1);

namespace Infrastructure\Middleware;

readonly class TelescopeAuth extends BasicAuth
{
    protected function getConfigPath(): string
    {
        return 'telescope.auth';
    }
}
