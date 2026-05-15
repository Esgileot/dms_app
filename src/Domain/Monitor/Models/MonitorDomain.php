<?php

declare(strict_types=1);

namespace Domain\Monitor\Models;

use Carbon\Carbon;
use Domain\Admin\Models\Admin;
use Domain\Monitor\Models\{MonitorLog, ScheduleSetting};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasOne, HasMany};

/**
 * @property int             $id
 * @property int             $admin_id
 * @property string          $domain
 * @property Carbon          $next_check_at
 * @property ?Carbon         $created_at
 * @property ?Carbon         $updated_at
 *
 * @property Admin           $admin
 * @property ScheduleSetting $scheduleSetting
 * @property MonitorLog[]    $monitorLogs
 */
class MonitorDomain extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'next_check_at' => 'datetime',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function scheduleSetting(): HasOne
    {
        return $this->hasOne(ScheduleSetting::class, 'monitor_domain_id');
    }

    public function monitorLogs(): HasMany
    {
        return $this->hasMany(MonitorLog::class, 'monitor_domain_id');
    }

    public function getUrl(): string
    {
        return "https://{$this->domain}";
    }
}
