<?php

declare(strict_types=1);

namespace Application\Auth\Actions\Web;

use Domain\Auth\Services\AuthService;

class LogoutAction
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function execute(): void
    {
        $this->authService->logout();
    }
}
