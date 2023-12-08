<?php

namespace App\Orchid\Traits;

use App\Orchid\Filters\TrashFilter;
use App\Orchid\Traits\UserPermissionTrait;

trait FilterTrait
{
    use UserPermissionTrait;
    //
    public function withTrashFilter(array $filters) 
    {
        // if authenticated user have access to trash filter then append it.
        if ($this->canTrashFilter()) {

            $filters[] = TrashFilter::class;
        }

        return $filters;
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
