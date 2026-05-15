<?php

declare(strict_types=1);

namespace Domain\Monitor\Eloquent;

use Domain\Admin\Models\Admin;
use Domain\Monitor\Exceptions\MonitorDomainFindException;
use Domain\Monitor\Models\MonitorDomain;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class MonitorDomainReadEloquent
{
    public function __construct(
        private MonitorDomain $model
    ) {}

    public function getByIdAndAdmin(Admin $admin, int $domainId): MonitorDomain
    {
        return $this->model->query()
            ->with('scheduleSetting')
            ->where('id', '=', $domainId)
            ->where('admin_id', '=', $admin->id)
            ->first() ?? throw new MonitorDomainFindException();
    }

    public function tryGetById(int $domainId): MonitorDomain
    {
        return $this->model->query()->with('scheduleSetting')->where('id', '=', $domainId)->first();
    }

    public function getList(Admin $admin): LengthAwarePaginator
    {
        return $this->model->query()
            ->with('scheduleSetting')
            ->where('admin_id', '=', $admin->id)
            ->latest()
            ->paginate(2);
    }

    public function getDetailList(Admin $admin): LengthAwarePaginator
    {
        return $this->model->query()
            ->with('scheduleSetting')
            ->where('admin_id', '=', $admin->id)
            ->latest()
            ->paginate(2);
    }


    public function isExistsByDomain(Admin $admin, string $domain, ?int $exceptId): bool
    {
        return $this->model->query()
            ->when(
                $exceptId,
                fn(Builder $query) => $query->where('id', '<>', $exceptId)
            )
            ->where('domain', '=', $domain)
            ->where('admin_id', '=', $admin->id)
            ->exists();
    }
}
