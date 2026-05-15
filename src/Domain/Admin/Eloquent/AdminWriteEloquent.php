<?php

declare(strict_types=1);

namespace Domain\Admin\Eloquent;

use Domain\Admin\Models\Admin;

class AdminWriteEloquent
{
    public function save(Admin $admin): Admin
    {
        $admin->save();

        return $admin;
    }
}
