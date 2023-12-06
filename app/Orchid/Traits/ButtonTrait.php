<?php

namespace App\Orchid\Traits;

use Orchid\Screen\TD;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use App\Orchid\Traits\FilterTrait;
use App\Orchid\Traits\StringTrait;
use Orchid\Screen\Actions\DropDown;
use Illuminate\Support\Facades\Cookie;
use App\Orchid\Traits\ModelObjectTrait;

trait ButtonTrait
{   
    use ModelObjectTrait;
    use FilterTrait;
    use StringTrait;

    // NOTE:: Screen = The permission prefix define in the platform provider, i mostly use table name as my prefix in permissions.

    // Table Entries/Record per page value
    public $recordPerPage; 

    // Entries/Record options, ex: 10, 25, 50 etc..
    public $recordPerPageOptions = [10, 25, 50, 75, 100];
    /*
    |--------------------------------------------------------------------------
    | Actions Dropdown List Buttons
    |--------------------------------------------------------------------------
    */

    /**
     * NOTE:: if you want to create new button(method) please add 'Button' text suffix.
     * example: editButton, deleteButton, restoreButton etc.
     * check example: editButton, deleteButton method.
     */
    public function actions($screen, $buttons = [])
    {
        return TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function ($item) use ($screen, $buttons) {

                    // add destroy button, but will show depending the if it's soft deleted or not
                    $buttons[] = 'destroyButton';
                    


                    $list = [];
                    foreach ($buttons as $button) {

                        $list[] = $this->{$button}($screen, $item->id); 

                    }

                    return  DropDown::make('Actions')
                                ->icon('bs.caret-down-fill')
                                ->list($list);
                })->canSee(
                    $this->canAny(
                        $screen, 
                        // permissionSuffix ex: edit, delete, etc..
                        array_map(function ($button) {
                            return str_replace('Button', '', $button);
                        }, $buttons)
                    )
                );
    }

    /*
    |--------------------------------------------------------------------------
    | Pagination Entries Button
    |--------------------------------------------------------------------------
    */
    public function entriesPerPageButton(array $recordPerPageOptions = [5, 10, 25, 50, 75, 100])
    {
        $options = [];
        foreach ($recordPerPageOptions as $value) {
            $label = $this->recordPerPage == $value ? __($value) . ' - Active' : __($value);
            $css = $this->recordPerPage == $value ? 'btn btn-success' : 'btn btn-link';

            $options[] = Button::make($label)
                            ->class($css)
                            ->method('setEntriesPerPage', ['limit' => $value]);
        }

        // append 'All' option
        $value = 999999;
        $optionAll = [
            'label' => $this->recordPerPage == $value ? __('All') . ' - Active' : __('All'),
            'value' => $value,
            'css'   => $this->recordPerPage == $value ? 'btn btn-success' : 'btn btn-link',
        ];

        $options[] = Button::make($optionAll['label'])
                        ->class($optionAll['css'])
                        ->confirm('Are you sure you want to do this? It may take a while, depending on the size of the records.')
                        ->method('setEntriesPerPage', ['limit' => $optionAll['value']]);

        return DropDown::make('Entries')
                ->icon('bs.list')
                ->list($options);
    }

    public function getEntriesPerPage(int $defaultLimit = 10)
    {
        $this->recordPerPage = $defaultLimit;

        if ( Cookie::has('recordPerPage') ) {

            $this->recordPerPage = request()->cookie('recordPerPage'); 
        
        }

        return $this->recordPerPage;
    }
    
    public function setEntriesPerPage(int $limit)
    {
        Cookie::queue('recordPerPage', $limit, 525600); // 1 year in minutes

        $this->recordPerPage = $limit;
    }

    /*
    |--------------------------------------------------------------------------
    | Create Button
    |--------------------------------------------------------------------------
    */
    public function addButton($screen)
    {
        return Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->class('btn btn-primary')
                ->href(route($screen.'.create'))
                ->canSee($this->canCreate($screen));
    }

    public function saveButton()
    {
        return Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->class('btn btn-success')
                ->method('save');
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Button
    |--------------------------------------------------------------------------
    */
    public function editButton($screen, $id, $tableName = null)
    {
        return Link::make(__('Edit'))
                ->icon('bs.pencil')
                ->class('btn btn-sm btn-warning')
                ->route($screen.'.edit', $id)
                ->canSee(
                    $this->canEdit($screen) && 
                    !$this->softDeleted($tableName ?? $screen, $id) // ! - if item is not soft deleted then show this button
                );
    }    
    
    /*
    |--------------------------------------------------------------------------
    | Delete Button
    |--------------------------------------------------------------------------
    */

    public function deleteButton($screen, $id, $tableName = null)
    {
        return Button::make(__('Delete'))
                ->icon('bs.trash3')
                ->class('btn btn-sm btn-danger')
                ->confirm('After deleting, the '.$this->singular($screen).' will be gone forever.')
                ->method('delete', [
                    'model' => $this->pathModel($screen), // you can override this if you chain the deleteButton
                    'id' => $id,
                ])
                ->canSee(
                    $this->canDelete($screen) &&
                    !$this->softDeleted($tableName ?? $screen, $id) // ! - if item is not softDeleted then show this button
                );
    }

    public function delete($model, $id)
    {
        if ($model::destroy($id)) {

            Toast::success('You have successfully deleted the '.$this->singular($model).'.');
            
        }else {
            
            Toast::error('Something went wrong, please contact administrator.');

        }
    }


    /*
    |--------------------------------------------------------------------------
    | Bulk Delete Button
    |--------------------------------------------------------------------------
    */
    public function bulkDeleteButton($screen)
    {
        return Button::make(__('Delete'))
                ->icon('bs.trash3')
                ->class('btn-delete btn btn-outline-danger')
                ->confirm('After deleting, the selected '.$this->plural($screen).' will be gone forever.')
                ->method('deleteBulk', [
                    'model' => $this->pathModel($screen),
                    'screen' => $screen,
                ])
                ->canSee(
                    $this->canBulkDelete($screen) &&
                    // if trash filter is active hide the normal bulk Delete
                    !$this->trashFilterState()
                );
    }

    public function deleteBulk($model, $screen, Request $request)
    {
        if (!$request->$screen) {

            Alert::error('Please select the row(s) to be deleted by checking the checkbox.');

        }else {
            $model::whereIn('id', $request->$screen)->delete();
    
            Toast::success('You have successfully deleted the selected '.$this->plural($screen).'.');
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Destroy Button
    |--------------------------------------------------------------------------
    */
    public function destroyButton($screen, $id, $tableName = null)
    {
        return Button::make(__('Destroy'))
                ->icon('bs.trash3')
                ->class('btn btn-sm btn-danger')
                ->confirm('Are you sure you want to remove this '.$this->singular($screen).' in the database.')
                ->method('destroy', [
                    'model' => $this->pathModel($screen), // you can override this if you chain the deleteButton
                    'id' => $id,
                ])
                ->canSee(
                    $this->canDestroy($screen) &&
                    $this->softDeleted($tableName ?? $screen, $id) // show only if softDeleted
                );
    }

    public function destroy($model, $id)
    {
        if ($model::withTrashed()->find($id)->forceDelete()) {

            Toast::success('You have successfully remove the '.$this->singular($this->screen($model)).' in the database.');
            
        }else {
            
            Toast::error('Something went wrong, please contact administrator.');

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Bulk Destroy Button
    |--------------------------------------------------------------------------
    */
    public function bulkDestroyButton($screen)
    {
        return Button::make(__('Destroy'))
                ->icon('bs.trash3')
                ->class('btn-delete btn btn-outline-danger')
                ->confirm('Are you sure you want to remove the selected '.$this->plural($screen).' in the database.')
                ->method('destroyBulk', [
                    'model' => $this->pathModel($screen),
                    'screen' => $screen,
                ])
                ->canSee(
                    $this->canBulkDestroy($screen) &&
                    // if trash filter is not active hide the bulk destroy
                    $this->trashFilterState()
                );
    }

    public function destroyBulk($model, $screen, Request $request)
    {
        if (!$request->$screen) {
            Alert::error('Please select the row(s) to be destroyed by checking the checkbox.');
        } else {
            $records = $model::whereIn('id', $request->$screen)->onlyTrashed()->get();

            foreach ($records as $record) {
                $record->forceDelete();
            }

            Toast::success('You have successfully removed the selected '.$this->plural($screen).' from the database.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Restore Button
    |--------------------------------------------------------------------------
    */
    public function restoreButton($screen, $id, $tableName = null)
    {
        return Button::make(__('Restore'))
                ->icon('bs.arrow-counterclockwise')
                ->class('btn btn-sm btn-success')
                ->confirm('Are you sure you want to restore this '.$this->singular($screen).'.')
                ->method('restore', [
                    'model' => $this->pathModel($screen), // you can override this if you chain the deleteButton
                    'id' => $id,
                ])
                ->canSee(
                    $this->canRestore($screen) &&
                    $this->softDeleted($tableName ?? $screen, $id) // show only if softDeleted
                );
    }

    public function restore($model, $id)
    {
        $screen = $this->screen($model);

        if ($this->canRestore($screen)) {
            $item = $model::withTrashed()->find($id);

            if ($item && $item->restore()) {

                Toast::success('You have successfully restored the '.$this->singular($screen).'.');
            
            } else {

                Toast::error('Something went wrong or the item does not exist. Please contact the administrator.');

            }
        } else {

            Toast::error('You do not have permission to restore '.$this->singular($screen).'.');
        
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Bulk Restore Button
    |--------------------------------------------------------------------------
    */

    // TODO:: next!

    



    // TODO:: for every button method add also the permission in if else statement.
}

