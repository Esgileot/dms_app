<?php

declare(strict_types=1);

namespace Application\Auth\Actions\Web\EmailVerification;

use Domain\Admin\Models\Admin;

class VerifyEmailAction
{
    public function execute(Admin $admin): void
    {
        if ($admin->hasVerifiedEmail()) {
            return;
        }

        $admin->markEmailAsVerified();
    }
}
