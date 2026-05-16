<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Controllers\Auth\EmailVerification;

use Infrastructure\Http\Controllers\BaseController;
use Illuminate\Http\RedirectResponse;
use Presentation\Web\App\Http\Requests\WebBaseRequest;
use Application\Auth\Actions\Web\EmailVerification\VerifyEmailAction;

class VerifyEmailController extends BaseController
{
    public function __invoke(WebBaseRequest $request, VerifyEmailAction $action): RedirectResponse
    {
        $action->execute($request->getAuthAdmin());

        return redirect()->intended(route('web.dashboard.page') . '?verified=1');
    }
}
