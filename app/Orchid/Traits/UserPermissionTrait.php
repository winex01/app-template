<?php

namespace App\Orchid\Traits;

trait UserPermissionTrait
{

    public function canCreate($prefix)
    {
        return auth()->user()->hasAccess($prefix.'.create');
    }

    public function canEdit($prefix)
    {
        return auth()->user()->hasAccess($prefix.'.edit');
    }

    public function canDelete($prefix)
    {
        return auth()->user()->hasAccess($prefix.'.delete');
    }

    public function canAny(string $prefix, array $permissions)
    {
        // append dot notation
        $prefix = $prefix.'.';

        // Append roles to each item in the array
        $actionsWithRoles = array_map(function ($action) use ($prefix) {
            return $prefix . $action;
        }, $permissions);


        return auth()->user()->hasAnyAccess($actionsWithRoles);
    }
}
