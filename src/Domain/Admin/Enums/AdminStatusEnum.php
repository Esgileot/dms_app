<?php

declare(strict_types=1);

namespace Domain\Admin\Enums;

enum AdminStatusEnum: string
{
    case Created = 'created';
    case Active = 'active';
}
