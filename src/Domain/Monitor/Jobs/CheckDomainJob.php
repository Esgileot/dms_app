<?php

declare(strict_types=1);

namespace Domain\Monitor\Jobs;

use Domain\Monitor\Eloquent\MonitorDomainReadEloquent;
use Domain\Monitor\Enums\MonitorMethodEnum;
use Domain\Monitor\Models\MonitorDomain;
use Domain\Monitor\Models\MonitorLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class CheckDomainJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(
        public readonly int $domainId
    ) {}

    public function handle(MonitorDomainReadEloquent $monitorDomainReadEloquent): void
    {
        $monitorDomain = $monitorDomainReadEloquent->tryGetById($this->domainId);

        if ($monitorDomain === null) {
            Log::warning("MonitorDomain not found", ['domain_id' => $this->domainId]);

            return;
        }

        $startTime = microtime(true);

        try {
            $response = $this->makeHttpRequest($monitorDomain);
            $responseTime = $this->calculateResponseTime($startTime);

            $isUp = $response->successful();

            $this->logResult(
                monitorDomain: $monitorDomain,
                isUp: $isUp,
                statusCode: $response->status(),
                responseTime: $responseTime
            );
        } catch (Throwable $e) {
            $responseTime = $this->calculateResponseTime($startTime);

            $this->logResult(
                monitorDomain: $monitorDomain,
                isUp: false,
                statusCode: null,
                responseTime: $responseTime,
                error: $e->getMessage()
            );
        }
    }

    private function makeHttpRequest(MonitorDomain $monitorDomain)
    {
        $setting = $monitorDomain->scheduleSetting;

        $http = Http::timeout($setting->timeout ?? 15)
            ->withUserAgent('Domain-Monitor-Bot');

        return match ($setting->method) {
            MonitorMethodEnum::GET => $http->get($monitorDomain->getUrl()),
            default                => $http->head($monitorDomain->getUrl()),
        };
    }

    private function calculateResponseTime(float $startTime): float
    {
        return round((microtime(true) - $startTime) * 1000);
    }

    private function logResult(
        MonitorDomain $monitorDomain,
        bool $isUp,
        ?int $statusCode,
        float $responseTime,
        ?string $error = null
    ): void {
        MonitorLog::create([
            'monitor_domain_id' => $monitorDomain->id,
            'is_up'             => $isUp,
            'status_code'       => $statusCode,
            'response_time'     => $responseTime,
            'error'             => $error,
            'checked_at'        => now(),
        ]);
    }
}
