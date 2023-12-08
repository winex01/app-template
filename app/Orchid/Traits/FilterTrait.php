<?php

namespace App\Orchid\Traits;

use App\Orchid\Filters\TrashFilter;
use App\Orchid\Traits\UserPermissionTrait;

trait FilterTrait
{
    use UserPermissionTrait;
    //
    public function trashFilter()
    {
        return new class extends TrashFilter {
            public function resetLink(): string
            {
                $params = $this->parameters();

                // add the pagination url parameter so it will be remove when removing the filter
                $params[] = 'page'; // page = pagination url parameter

                // Build the query string
                $params = http_build_query($this->request->except($params));

                return url($this->request->url().'?'.$params);
            }
        };
    }

    public function withTrashFilter(array $filters) 
    {
        // if authenticated user have access to trash filter then append it.
        if ($this->canTrashFilter()) {

            // Add the customTrashFilter to the beginning of the $filters array
            // array_unshift($filters, get_class($this->trashFilter()));
            
            $filters[] = $this->trashFilter();
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
