<?php

declare(strict_types=1);

namespace Infrastructure\Casts;

use Infrastructure\ValueObjects\Password;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class PasswordCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): Password
    {
        return new Password($value);
    }
}