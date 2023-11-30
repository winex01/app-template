<?php

namespace App\Orchid\Traits;

use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\DropDown;

trait ActionButtonTrait
{
    /*
    |--------------------------------------------------------------------------
    | Actions Buttons
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */
    public function addButton($prefix)
    {
        return Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->href(route($prefix.'.create'))
                ->canSee($this->canCreate($prefix));
    }

    public function saveButton()
    {
        return Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save');
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */
    public function editButton()
    {
        return Link::make(__('Edit'))
                ->icon('bs.pencil');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function deleteButton()
    {
        return Button::make(__('Delete'))
                ->icon('bs.trash3');
    }

    public function delete($model, $id)
    {
        $fullPathModel = 'App\Models\\' . $model;

        if ($fullPathModel::destroy($id)) {

            Toast::success('You have successfully deleted the '.$model.'.');
            
        }else {
            
            Toast::error('Something went wrong, please contact administrator.');

        }
    }


    /*
    |--------------------------------------------------------------------------
    | Bulk Delete
    |--------------------------------------------------------------------------
    */
    public function bulkDeleteButton($prefix)
    {
        return Button::make(__('Bulk Delete'))
                ->icon('bs.trash3')
                ->confirm('After deleting, the '.$prefix.' will be gone forever.')
                ->method('deleteBulk')
                ->canSee($this->canBulkDelete($prefix));
    }

    // TODO:: try to refactor and don't use model bindig if its gonna work    
}
