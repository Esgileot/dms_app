<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Infrastructure\Http\Controllers\BaseController;
use Presentation\Web\App\Http\Requests\WebBaseRequest;
use Application\Auth\Actions\Web\LogoutAction;

class LogoutController extends BaseController
{
    public function __invoke(WebBaseRequest $request, LogoutAction $action): RedirectResponse
    {
        $action->execute();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('web.login.page');
    }
}
