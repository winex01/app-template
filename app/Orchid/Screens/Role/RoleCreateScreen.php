<?php

namespace App\Orchid\Screens\Role;

use App\Orchid\Screens\Role\RoleEditScreen;


class RoleCreateScreen extends RoleEditScreen
{
    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('Create Role');
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Create the privileges and permissions associated with a specific role.';
    }

    public function permission(): ?iterable
    {   
        return [
            'roles.create',
        ];
    }
}
