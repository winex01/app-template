<?php

namespace App\Orchid\Traits;

use Orchid\Screen\TD;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
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
    public function addButton($screen)
    {
        return Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->href(route($screen.'.create'))
                ->canSee($this->canCreate($screen));
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
    public function editButton($screen, $id)
    {
        return Link::make(__('Edit'))
                ->icon('bs.pencil')
                ->route($screen.'.edit', $id)
                ->canSee($this->canEdit($screen));
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function deleteButton($screen, $id)
    {
        $model = ucfirst(Str::singular($screen));

        return Button::make(__('Delete'))
                ->icon('bs.trash3')
                ->confirm('After deleting, the '.Str::singular($screen).' will be gone forever.')
                ->method('delete', [
                    'model' => 'App\Models\\'.$model, // you can override this if you chain the deleteButton
                    'id' => $id,
                ])
                ->canSee($this->canDelete($screen));
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
    public function bulkDeleteButton($screen)
    {
        $model = ucfirst(Str::singular($screen));

        return Button::make(__('Bulk Delete'))
                ->icon('bs.trash3')
                ->confirm('After deleting, the '.$screen.' will be gone forever.')
                ->method('deleteBulk', [
                    'model' => 'App\Models\\'.$model,
                    'screen' => $screen,
                ])
                ->canSee($this->canBulkDelete($screen));
    }

    public function deleteBulk($model, $screen, Request $request)
    {
        if (!$request->$screen) {

            Alert::error('Please select the row(s) to be deleted by checking the checkbox.');

        }else {

            $model::whereIn('id', $request->$screen)->delete();
    
            $label = str_replace('App\Models\\', '', $screen);

            Toast::success('You have successfully deleted the selected '.$label.'.');
        }

    }
}
