<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Controllers\Monitor;

use Infrastructure\Http\Controllers\BaseController;
use Presentation\Web\App\Http\Requests\Monitor\DeleteMonitorDomainRequest;
use Application\Monitor\Actions\Web\DeleteMonitorDomainAction;
use Domain\Monitor\Exceptions\MonitorDomainFindException;
use Illuminate\Http\RedirectResponse;

class DeleteMonitorDomainController extends BaseController
{
    /**
     * @throws MonitorDomainFindException
     */
    public function __invoke(DeleteMonitorDomainRequest $request, DeleteMonitorDomainAction $action): RedirectResponse
    {
        $action->execute(admin: $request->getAuthAdmin(), monitorDomainId: $request->getDomainId());

        return to_route('web.dashboard.page')->with('success', 'Domain deleted.');
    }
}
