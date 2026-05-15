<?php

declare(strict_types=1);

namespace Domain\Admin\Services;

use Domain\Admin\Models\Admin;
use Domain\Admin\Dto\AdminData;
use Domain\Admin\Enums\AdminStatusEnum;

class AdminService
{
    public function initAdminModel(AdminData $data): Admin
    {
        $admin = new Admin();

        $admin->name = $data->name;
        $admin->email = $data->email;
        $admin->status = AdminStatusEnum::Created;
        $admin->password = $data->password->getHash();

        return $admin;
    }
}
