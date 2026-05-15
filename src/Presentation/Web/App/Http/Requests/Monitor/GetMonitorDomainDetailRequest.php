<?php

declare(strict_types=1);

namespace Presentation\Web\App\Http\Requests\Monitor;

use Presentation\Web\App\Http\Requests\WebBaseRequest;

class GetMonitorDomainDetailRequest extends WebBaseRequest
{
    public function rules(): array
    {
        return [
            'domain_id' => ['bail', 'required', 'integer', 'min:1'],
        ];
    }

    public function getDomainId(): int
    {
        return (int) $this->validated('domain_id');
    }
}
