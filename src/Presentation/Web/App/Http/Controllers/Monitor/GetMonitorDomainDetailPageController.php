<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Controllers\Monitor;

use Application\Monitor\Actions\Web\GetMonitorDomainDetailAction;
use Infrastructure\Http\Controllers\BaseController;
use Inertia\Response;
use Inertia\Inertia;
use Presentation\Web\App\Http\Requests\Monitor\GetMonitorDomainDetailRequest;

class GetMonitorDomainDetailPageController extends BaseController
{
    public function __invoke(GetMonitorDomainDetailRequest $request, GetMonitorDomainDetailAction $action): Response
    {
        $data = $action->execute(monitorDomainId: $request->getDomainId(), admin: $request->getAuthAdmin());

        return Inertia::render('Domain/Detail', $data->toArray());
    }
}
