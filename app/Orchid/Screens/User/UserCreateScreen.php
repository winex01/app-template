<?php

namespace App\Orchid\Screens\User;

use App\Orchid\Screens\User\UserEditScreen;


class UserCreateScreen extends UserEditScreen
{   
    
    public function permission(): ?iterable
    {   
        return [
            'roles.create',
        ];
    }
}
