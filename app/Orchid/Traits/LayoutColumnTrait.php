<?php

namespace App\Orchid\Traits;

use Orchid\Screen\TD;
use Orchid\Screen\Fields\CheckBox;

trait LayoutColumnTrait
{
    //
    public function columnBulkAction($prefix)
    {
        $temp = $prefix.'[]';
        return TD::make()
                ->width('1px')
                ->render(fn($prefix) => CheckBox::make($temp)
                    ->value($prefix->id)
                    ->checked(false)
                )->canSee($this->canBulkDelete($prefix));
    }
}
