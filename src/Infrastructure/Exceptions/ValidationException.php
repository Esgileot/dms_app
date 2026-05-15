<?php

declare(strict_types=1);

namespace Infrastructure\Exceptions;

use Illuminate\Validation\ValidationException as BaseValidationException;

class ValidationException extends BaseValidationException
{
    public static function withKey(string $attribute, ?string $rule = 'exists'): self
    {
        return self::withMessages([
            $attribute => [
                __("validation.{$rule}", [
                    'attribute' => $attribute,
                ])],
        ]);
    }
}
