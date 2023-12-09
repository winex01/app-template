<?php

namespace App\Orchid\Traits;

use App\Orchid\Filters\TrashFilter;
use App\Orchid\Traits\UserPermissionTrait;

trait FilterTrait
{
    use UserPermissionTrait;
    
    public function checkFilterPermission(array $filterClass)
    {
        $allowedFilters = [];

        foreach ($filterClass as $filter) {
            if ((is_object($filter) && $filter->permission()) || (is_string($filter) && (new $filter())->permission())) {
                $allowedFilters[] = $filter;
            }
        }

        return $allowedFilters;
    }

    public function trashFilterState()
    {
        $state = false;

        if ($this->canTrashFilter() && request()->trash_only) {
            $state = true;            
        }

        return $state;
    }
}
