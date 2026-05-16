<?php

declare(strict_types=1);

namespace Domain\Admin\Eloquent;

use Domain\Admin\Models\Admin;

class AdminReadEloquent
{
    public function __construct(
        private Admin $model
    ) {}
}
