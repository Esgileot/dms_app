<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Controllers\Monitor;

use Inertia\Response;
use Inertia\Inertia;
use Infrastructure\Http\Controllers\BaseController;

class CreateMonitorDomainPageController extends BaseController
{
    public function __invoke(): Response
    {
        return Inertia::render('Domain/Create');
    }
}
