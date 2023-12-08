<?php

namespace App\Orchid\Layouts\Role;

use App\Orchid\Filters\SearchFilter;
use App\Orchid\Traits\ExtendOrchidTrait;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class RoleFiltersLayout extends Selection
{
    use ExtendOrchidTrait;
    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return $this->withTrashFilter([
            new class extends SearchFilter {
                public function searchTableColumns()
                {
                    return [
                        'slug', 
                        'name'
                    ];
                }
            },
        ]);
    }
}
