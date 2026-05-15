<?php

declare(strict_types=1);

namespace Domain\Auth\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Domain\Auth\Dto\AuthData;

class AuthService
{
    public function login(AuthData $data, bool $remember): void
    {
        if (Auth::attempt($data->toArray(), $remember) === false) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
    }
}
