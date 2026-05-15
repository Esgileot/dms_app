<?php

declare(strict_types=1);

namespace Domain\Monitor\Models;

use Carbon\Carbon;
use Domain\Monitor\Enums\MonitorMethodEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Domain\Monitor\Models\MonitorDomain;

/**
 * @property int             $id
 * @property int             $monitor_domain_id
 * @property int             $interval
 * @property int             $timeout
 * @property MonitorMethodEnum $method
 * @property ?Carbon         $created_at
 * @property ?Carbon         $updated_at
 *
 * @property MonitorDomain   $domain
 */
class ScheduleSetting extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'method' => MonitorMethodEnum::class,
        ];
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(MonitorDomain::class, 'monitor_domain_id');
    }
}
