<?php

declare(strict_types=1);

namespace Domain\Auth\Dto;

use Spatie\LaravelData\Data;

class AuthData extends Data
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
