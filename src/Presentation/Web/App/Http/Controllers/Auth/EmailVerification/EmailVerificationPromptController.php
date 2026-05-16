<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Controllers\Auth\EmailVerification;

use Infrastructure\Http\Controllers\BaseController;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Presentation\Web\App\Http\Requests\WebBaseRequest;

class EmailVerificationPromptController extends BaseController
{
    public function __invoke(WebBaseRequest $request): RedirectResponse|Response
    {
        return $request->getAuthAdmin()->hasVerifiedEmail()
            ? redirect()->intended(route('web.dashboard.page'))
            : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}
