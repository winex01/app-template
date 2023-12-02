<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Platform\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use SoftDeletes;
}
