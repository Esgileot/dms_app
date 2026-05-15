<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Controllers\Auth;

use Infrastructure\Http\Controllers\BaseController;
use Inertia\Response;
use Inertia\Inertia;

class LoginPageController extends BaseController
{
    public function __invoke(): Response
    {
        return Inertia::render('Auth/Login', [
            'status' => session('status'),
        ]);
    }
}
