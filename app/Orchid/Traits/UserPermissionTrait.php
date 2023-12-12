<?php

namespace App\Orchid\Traits;

trait UserPermissionTrait
{

    public function canCreate($screen)
    {
        return auth()->user()->hasAccess($screen.'.create');
    }

    public function canEdit($screen)
    {
        return auth()->user()->hasAccess($screen.'.edit');
    }

    public function canDelete($screen)
    {
        return auth()->user()->hasAccess($screen.'.delete');
    }

    public function canBulkDelete($screen)
    {
        return auth()->user()->hasAccess($screen.'.bulk.delete');
    }
    
    public function canDestroy($screen)
    {
        return auth()->user()->hasAccess($screen.'.destroy');
    }

    public function canBulkDestroy($screen)
    {
        return auth()->user()->hasAccess($screen.'.bulk.destroy');
    }

    public function canRestore($screen)
    {
        return auth()->user()->hasAccess($screen.'.restore');
    }

    public function canBulkRestore($screen)
    {
        return auth()->user()->hasAccess($screen.'.bulk.restore');
    }

    public function canAny(string $screen, array $permissions)
    {
        // append dot notation
        $screen = $screen.'.';

        // Append roles to each item in the array
        $actionsWithRoles = array_map(function ($action) use ($screen) {
            return $screen . $action;
        }, $permissions);


        return auth()->user()->hasAnyAccess($actionsWithRoles);
    }

    public function canTrashFilter()
    {
        return auth()->user()->hasAccess('trash.filter');
    }
    
    public function canExport($screen)
    {
        return auth()->user()->hasAccess($screen.'.export');
    }
}
