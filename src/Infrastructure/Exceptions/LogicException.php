<?php

declare(strict_types=1);

namespace Infrastructure\Exceptions;

use Illuminate\Support\Str;

abstract class LogicException extends ServerException
{
    protected string $field = '';
    protected string $entityName = '';

    public function getEntityField(): string
    {
        return $this->field;
    }

    public function setEntityField(string $field): void
    {
        $this->field = $field;
    }

    public function getEntityName(): string
    {
        return Str::lower(Str::headline($this->entityName));
    }

    public function setEntityName(string $entityName): void
    {
        $this->entityName = $entityName;
    }
}
