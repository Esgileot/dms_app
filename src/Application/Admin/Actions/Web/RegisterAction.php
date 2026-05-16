<?php

declare(strict_types=1);

namespace Application\Admin\Actions\Web;

use Illuminate\Support\Facades\Auth;
use Domain\Admin\Dto\AdminData;
use Domain\Admin\Eloquent\AdminWriteEloquent;
use Domain\Admin\Models\Admin;
use Domain\Admin\Services\AdminService;

class RegisterAction
{
    public function __construct(
        private AdminService $adminService,
        private AdminWriteEloquent $adminWriteEloquent,
    ) {}

    public function execute(AdminData $data): Admin
    {

        $admin = $this->adminService->initAdminModel(data: $data);

        $admin = $this->adminWriteEloquent->save($admin);

        Auth::login($admin);

        return $admin;
    }
}
