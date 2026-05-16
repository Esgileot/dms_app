<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Infrastructure\Http\Controllers\BaseController;
use Presentation\Web\App\Http\Requests\Auth\LoginRequest;
use Application\Auth\Actions\Web\LoginAction;

class LoginController extends BaseController
{
    public function __invoke(LoginRequest $request, LoginAction $action): RedirectResponse
    {
        $action->execute(data: $request->getData(), remember: $request->getRemember());

        $request->session()->regenerate();

        return to_route('web.dashboard.page');
    }
}
