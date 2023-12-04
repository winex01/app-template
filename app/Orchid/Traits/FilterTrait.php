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
        // if authenticated has access to trash filter then append it.
        if ($this->canTrashFilter()) {
            // append in the beginning
            array_unshift($filters, TrashFilter::class);
        }

        return $filters;
    }
}
