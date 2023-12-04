<?php

namespace App\Orchid\Traits;

use App\Orchid\Traits\ButtonTrait;
use App\Orchid\Traits\FilterTrait;
use App\Orchid\Traits\LayoutColumnTrait;
use App\Orchid\Traits\UserPermissionTrait;

trait ExtendOrchidTrait
{
    use UserPermissionTrait;
    use ButtonTrait;
    use LayoutColumnTrait;
    use FilterTrait;
}
