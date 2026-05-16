<?php

declare(strict_types=1);

namespace Domain\Admin\Dto;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Infrastructure\Casts\PasswordCast;
use Infrastructure\ValueObjects\Password;

class AdminData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        #[WithCast(PasswordCast::class)]
        public Password $password,
    ) {}
}
