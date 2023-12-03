<?php

namespace App\Orchid\Layouts\Role;

use App\Orchid\Filters\RoleFilter;
use Orchid\Filters\Filter;
use App\Orchid\Filters\TrashFilter;
use Orchid\Screen\Layouts\Selection;

class RoleFiltersLayout extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        // TODO:: add filter permission here, not on Filter class isDisplay method because url can still be manipulated there
        return [
            TrashFilter::class,
            RoleFilter::class,
        ];
    }
}
