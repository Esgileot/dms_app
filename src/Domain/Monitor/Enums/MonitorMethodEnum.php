<?php

declare(strict_types=1);

namespace Domain\Monitor\Enums;

enum MonitorMethodEnum: string
{
    case GET = 'GET';
    case HEAD = 'HEAD';
}
