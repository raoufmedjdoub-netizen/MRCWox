<?php

namespace Tobuli\Helpers\BroadcastMessage\UsersFilter;

use Illuminate\Database\Eloquent\Builder;

class DevicesFilter implements FilterInterface
{
    public function apply(Builder $query, array $params): void
    {
        if (empty($params['devices'])) {
            return;
        }

        $deviceIds = $params['devices'];

        $query->whereHas('devices', function ($query) use ($deviceIds) {
            $query->whereIn('devices.id', $deviceIds);
        });
    }

    public function getView(): string
    {
        return 'Admin.BroadcastMessages.Partials.devices';
    }

    public function getViewParameters(): array
    {
        return [];
    }

    public function relevant(): bool
    {
        return true;
    }
}