<?php

declare(strict_types=1);

namespace Application\Auth\Actions\Web;

use Domain\Auth\Dto\AuthData;
use Domain\Auth\Services\AuthService;

class LoginAction
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function execute(AuthData $data, bool $remember): void
    {
        $this->authService->login(data: $data, remember: $remember);
    }
}
