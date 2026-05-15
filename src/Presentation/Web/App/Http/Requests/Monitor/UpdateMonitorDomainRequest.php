<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Requests\Monitor;

use Domain\Monitor\Dto\MonitorDomainData;
use Domain\Monitor\Enums\MonitorMethodEnum;
use Presentation\Web\App\Http\Requests\WebBaseRequest;
use Illuminate\Validation\Rule;

class UpdateMonitorDomainRequest extends WebBaseRequest
{
    public function rules(): array
    {
        return [
            'domain_id' => ['bail', 'required', 'integer', 'min:1'],
            'domain'   => ['bail', 'required', 'string', 'max:255', 'regex:/^([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/'],
            'method'   => ['bail', 'required', 'string', Rule::enum(MonitorMethodEnum::class)],
            'interval' => ['bail', 'required', 'integer', 'min:1', 'max:1440'],
            'timeout'  => ['bail', 'required', 'integer', 'min:1', 'max:60'],
        ];
    }

    public function getData(): MonitorDomainData
    {
        return MonitorDomainData::from($this->validated());
    }

    public function getDomainId(): int
    {
        return (int) $this->validated('domain_id');
    }
}
