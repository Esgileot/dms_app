<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Controllers\Auth;

use Infrastructure\Http\Controllers\BaseController;
use Illuminate\Http\RedirectResponse;
use Presentation\Web\App\Http\Requests\Auth\RegisterRequest;
use Application\Admin\Actions\Web\RegisterAction;

class RegisterController extends BaseController
{
    public function __invoke(RegisterRequest $request, RegisterAction $action): RedirectResponse
    {
        $action->execute($request->getData());

        return to_route('web.dashboard.page');
    }
}
