<?php

namespace Tobuli\Helpers\BroadcastMessage\UsersFilter;

use Illuminate\Database\Eloquent\Builder;

class UsersFilter implements FilterInterface
{
    public function apply(Builder $query, array $params): void
    {
        if (!empty($params['users'])) {
            $query->whereIn('id', $params['users']);
        }
    }

    public function getView(): string
    {
        return 'Admin.BroadcastMessages.Partials.users';
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