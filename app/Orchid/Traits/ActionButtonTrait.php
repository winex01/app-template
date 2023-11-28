<?php

namespace App\Orchid\Traits;

use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;

trait ActionButtonTrait
{
    //
    public function actionButtons()
    {   
        // can be chain and use orchid methods
        return TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px');
    }

    public function editButton()
    {
        return Link::make(__('Edit'))
                    ->icon('bs.pencil');
    }

    public function deleteButton()
    {
        return Button::make(__('Delete'))
                ->icon('bs.trash3');
    }
}
