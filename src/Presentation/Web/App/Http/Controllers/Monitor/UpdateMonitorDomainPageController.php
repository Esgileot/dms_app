<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Controllers\Monitor;

use Inertia\Response;
use Inertia\Inertia;
use Infrastructure\Http\Controllers\BaseController;
use Presentation\Web\App\Http\Requests\Monitor\UpdateMonitorDomainPageRequest;
use Application\Monitor\Actions\Web\GetMonitorDomainAction;
use Domain\Monitor\Exceptions\MonitorDomainFindException;

class UpdateMonitorDomainPageController extends BaseController
{
    /**
     * @throws MonitorDomainFindException
     */
    public function __invoke(UpdateMonitorDomainPageRequest $request, GetMonitorDomainAction $action): Response
    {
        $monitorDomain = $action->execute(domainId: $request->getDomainId(), admin: $request->getAuthAdmin());

        return Inertia::render('Domain/Update', [
            'domain' => $monitorDomain,
        ]);
    }
}
