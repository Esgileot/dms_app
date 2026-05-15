<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Controllers\Auth\EmailVerification;

use Infrastructure\Http\Controllers\BaseController;
use Illuminate\Http\RedirectResponse;
use Presentation\Web\App\Http\Requests\WebBaseRequest;

class EmailVerificationNotificationController extends BaseController
{
    public function __invoke(WebBaseRequest $request): RedirectResponse
    {
        $admin = $request->getAuthAdmin();

        if ($admin->hasVerifiedEmail()) {
            return redirect()->intended(route('web.dashboard'));
        }

        $admin->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
