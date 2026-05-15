<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Controllers\Monitor;

use Infrastructure\Http\Controllers\BaseController;
use Presentation\Web\App\Http\Requests\Monitor\UpdateMonitorDomainRequest;
use Application\Monitor\Actions\Web\UpdateMonitorDomainAction;
use Illuminate\Http\RedirectResponse;
use Domain\Monitor\Exceptions\MonitorDomainFindException;

class UpdateMonitorDomainController extends BaseController
{
    /**
     * @throws MonitorDomainFindException
     */
    public function __invoke(UpdateMonitorDomainRequest $request, UpdateMonitorDomainAction $action): RedirectResponse
    {
        $action->execute(
            admin: $request->getAuthAdmin(),
            data: $request->getData(),
            monitorDomainId: $request->getDomainId(),
        );

        return to_route('web.dashboard.page')->with('success', 'Domain updated.');
    }
}
