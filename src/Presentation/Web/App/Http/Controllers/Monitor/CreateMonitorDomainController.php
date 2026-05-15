<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Controllers\Monitor;

use Application\Monitor\Actions\Web\CreateMonitorDomainAction;
use Illuminate\Http\RedirectResponse;
use Infrastructure\Http\Controllers\BaseController;
use Presentation\Web\App\Http\Requests\Monitor\CreateMonitorDomainRequest;
use Domain\Monitor\Exceptions\MonitorDomainAlreadyExistsException;
use Infrastructure\Exceptions\ValidationException;

class CreateMonitorDomainController extends BaseController
{
    public function __invoke(CreateMonitorDomainRequest $request, CreateMonitorDomainAction $action): RedirectResponse
    {
        try {
            $action->execute(admin: $request->getAuthAdmin(), data: $request->getData());
        } catch (MonitorDomainAlreadyExistsException) {
            throw  ValidationException::withMessages([
                'domain' => 'This domain is already added.',
            ]);
        }

        return to_route('web.dashboard.page')->with('success', 'Domain created.');
    }
}
