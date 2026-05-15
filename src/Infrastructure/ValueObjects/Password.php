<?php

declare(strict_types=1);

namespace Infrastructure\ValueObjects;

use Illuminate\Support\Facades\Hash;

final class Password
{
    private readonly string $hash;

    public function __construct(string $plain)
    {
        $this->hash = Hash::make($plain);
    }

    public function getHash(): string
    {
        return $this->hash;
    }
}