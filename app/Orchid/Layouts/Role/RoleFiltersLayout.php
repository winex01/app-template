<?php

namespace App\Orchid\Layouts\Role;

use App\Orchid\Filters\SearchFilter;
use App\Orchid\Filters\TrashFilter;
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
        // TODO:: make this reusable and avoid update anomaly 
                  // perhaps create a method trait that can be chain?

        $test = [
            'name',
            'slug',
        ];


        $filters = [
            new SearchFilter($test)
        ];

        if ($this->canTrashFilter()) {
            $filters[] = TrashFilter::class;
        }

        return $filters;
    }
    
}
