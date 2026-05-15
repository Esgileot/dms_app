<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Controllers\Dashboard;

use Application\Monitor\Actions\Web\GetMonitorDomainListAction;
use Infrastructure\Http\Controllers\BaseController;
use Inertia\Response;
use Inertia\Inertia;
use Presentation\Web\App\Http\Requests\WebBaseRequest;

class DashboardPageController extends BaseController
{
    public function __invoke(WebBaseRequest $request, GetMonitorDomainListAction $action): Response
    {
        $monitorDomains = $action->execute(admin: $request->getAuthAdmin());

        return Inertia::render('Dashboard', [
            'domains' => $monitorDomains
        ]);
    }
}
