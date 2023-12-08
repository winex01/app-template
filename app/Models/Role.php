<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Platform\Models\Role as OrchidModel;

class Role extends OrchidModel
{
    use SoftDeletes;
}
