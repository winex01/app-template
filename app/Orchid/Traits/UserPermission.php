<?php

namespace App\Orchid\Traits;

trait UserPermission
{
    public function canView($prefix)
    {
        return $prefix.'.list';
    }

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
}
