<?php

namespace App\Orchid\Traits;

use Orchid\Screen\TD;
use Illuminate\Support\Str;
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
    public function editButton($prefix, $id)
    {
        return Link::make(__('Edit'))
                ->icon('bs.pencil')
                ->route($prefix.'.edit', $id)
                ->canSee($this->canEdit($prefix));
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function deleteButton($prefix, $id)
    {
        $model = ucfirst(Str::singular($prefix));

        return Button::make(__('Delete'))
                ->icon('bs.trash3')
                ->confirm('After deleting, the '.Str::singular($prefix).' will be gone forever.')
                ->method('delete', [
                    'model' => 'App\Models\\'.$model, // you can override this if you chain the deleteButton
                    'id' => $id,
                ])
                ->canSee($this->canDelete($prefix));
    }

    public function delete($model, $id)
    {
        if ($model::destroy($id)) {

            $label = str_replace('App\Models\\', '', $model);

            Toast::success('You have successfully deleted the '.$label.'.');
            
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

}
