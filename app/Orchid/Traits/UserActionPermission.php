<?php

namespace App\Orchid\Traits;

trait UserActionPermission
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
}
