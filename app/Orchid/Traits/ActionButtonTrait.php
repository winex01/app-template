<?php

namespace App\Orchid\Traits;

use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;

trait ActionButtonTrait
{
    //
    public function actionButtons()
    {   
        return TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px');
    }

    public function actionButtonsDropdown()
    {
        return DropDown::make('Actions')
                ->icon('bs.caret-down-fill');
    }

    public function addButton()
    {
        return Link::make(__('Add'))
                ->icon('bs.plus-circle');
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

    public function saveButton()
    {
        return Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save');
    }
}
