<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Requests\Auth;

use Presentation\Web\App\Http\Requests\WebBaseRequest;
use Illuminate\Validation\Rules\Password;
use Domain\Admin\Dto\AdminData;

class RegisterRequest extends WebBaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['bail', 'required', 'string', 'min:2', 'max:255'],
            'email' => ['bail', 'required', 'string', 'email', 'max:255'],
            'password' => ['bail', 'required', 'confirmed', Password::defaults()],
        ];
    }

    public function getData(): AdminData
    {
        return AdminData::from($this->validated());
    }
}
