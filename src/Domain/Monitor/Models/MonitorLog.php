<?php

declare(strict_types=1);

namespace Domain\Monitor\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Domain\Monitor\Models\MonitorDomain;

/**
 * @property int           $id
 * @property int           $domain_id
 * @property bool          $is_up
 * @property ?int          $status_code
 * @property ?int          $response_time
 * @property ?string       $error
 * @property Carbon        $checked_at
 *
 * @property MonitorDomain $domain
 */
class MonitorLog extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_up'        => 'boolean',
            'checked_at'   => 'datetime',
        ];
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(MonitorDomain::class, 'monitor_domain_id');
    }
}
