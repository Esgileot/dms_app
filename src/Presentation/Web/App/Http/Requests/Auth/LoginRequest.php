<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Requests\Auth;

use Domain\Auth\Dto\AuthData;
use Presentation\Web\App\Http\Requests\WebBaseRequest;

class LoginRequest extends WebBaseRequest
{
    public function rules(): array
    {
        return [
            'email' => ['bail', 'required', 'string', 'email', 'max:255'],
            'password' => ['bail', 'required', 'string', 'max:255'],
            'remember' => ['bail', 'required', 'bool'],
        ];
    }

    public function getData(): AuthData
    {
        return AuthData::from($this->validated());
    }

    public function getRemember(): bool
    {
        return $this->validated('remember');
    }
}
