<?php

namespace App\Orchid\Traits;

use Orchid\Screen\TD;
use Orchid\Screen\Fields\CheckBox;

trait LayoutColumnTrait
{
    //
    public function columnBulkAction($screen)
    {
        return TD::make()
                ->width('1px')
                ->render(fn($screen) => CheckBox::make('ids[]')
                    ->value($screen->id)
                    ->checked(false)
                )->canSee($this->canBulkDelete($screen));
    }
}
