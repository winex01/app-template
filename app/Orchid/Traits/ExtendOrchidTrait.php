<?php

namespace App\Orchid\Traits;

use App\Orchid\Traits\ActionButtonTrait;
use App\Orchid\Traits\LayoutColumnTrait;
use App\Orchid\Traits\UserPermissionTrait;

trait ExtendOrchidTrait
{
    use UserPermissionTrait;
    use ActionButtonTrait;
    use LayoutColumnTrait;
}
