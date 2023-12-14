<?php

namespace App\Orchid\Traits;

use Orchid\Screen\TD;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
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

    public $recordPerPage; 

    public function buttons($screen)
    {
        return [
            $this->bulkDeleteButton($screen),
            $this->bulkDestroyButton($screen),
            $this->bulkRestoreButton($screen),
            $this->exportButton($screen),
            $this->entriesPerPageButton(),
            $this->addButton($screen),
        ];
    }
   
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
    // TODO:: when changing entries per page reset pagination to 1
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

    public function getEntriesPerPage(int $defaultLimit = 5)
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
                ->confirm($this->confirmMessage('delete', $this->singular($screen)))
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
        $screen = $this->screen($model);

        // check permission
        if (!$this->canRestore($screen)) {
            $this->toastNotAuthorized('destroy', $screen);
            return;
        }

        // validation
        if (!isset($id) || empty($id)) {
            $this->toastError();
            return;
        }

        if ($model::destroy($id)) {
            $this->toastSuccess('deleted', $this->singular($screen));
        }else {
            $this->toastError();
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
                ->class('bulk-danger  btn btn-outline-danger')
                ->confirm($this->confirmMessage('delete', $this->plural($screen)))
                ->method('bulkDelete', [
                    'model' => $this->pathModel($screen),
                    'screen' => $screen,
                ])
                ->canSee(
                    $this->canBulkDelete($screen) &&
                    // if trash filter is active hide the normal bulk Delete
                    !$this->trashFilterState()
                );
    }

    public function bulkDelete($model, $screen, Request $request)
    {
        // check permission
        if (!$this->canRestore($screen)) {
            $this->toastNotAuthorized('delete', $screen);
            return;
        }

        // validation
        if (!request()->ids) {
            $this->bulkValidationError('delete');
            return;
        }

        if ($model::whereIn('id', $request->ids)->delete()) {
            $this->toastSuccess('deleted', $this->plural($screen));
        }else {
            $this->toastError();
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
                ->confirm($this->confirmMessage('destroy', $this->singular($screen)))
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
        $screen = $this->screen($model);

        // check permission
        if (!$this->canRestore($screen)) {
            $this->toastNotAuthorized('destroy', $screen);
            return;
        }

        // validation
        if (!isset($id) || empty($id)) {
            $this->toastError();
            return;
        }

        if ($model::withTrashed()->find($id)->forceDelete()) {
            $this->toastSuccess('destroyed', $this->singular($screen));
        }else {
            $this->toastError();
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
                ->class('bulk-danger btn btn-outline-danger')
                ->confirm($this->confirmMessage('destroy', $this->plural($screen)))
                ->method('bulkDestroy', [
                    'model' => $this->pathModel($screen),
                    'screen' => $screen,
                ])
                ->canSee(
                    $this->canBulkDestroy($screen) &&
                    // if trash filter is not active hide the bulk destroy
                    $this->trashFilterState()
                );
    }

    public function bulkDestroy($model, $screen)
    {
        // check permission
        if (!$this->canRestore($screen)) {
            $this->toastNotAuthorized('destroy', $screen);
            return;
        }

        // validation
        if (!request()->ids) {
            $this->bulkValidationError('destroyed');
            return;
        }

        $records = $model::whereIn('id', request()->ids)->onlyTrashed()->get();

        foreach ($records as $record) {
            $record->forceDelete();
        }

        $this->toastSuccess('destroyed', $this->plural($screen));
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
                ->class('btn btn-sm btn-warning')
                ->confirm($this->confirmMessage('restore', $this->singular($screen)))
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

        // check permission
        if (!$this->canRestore($screen)) {
            $this->toastNotAuthorized('restore', $screen);
            return;
        }

        // validation
        if (!isset($id) || empty($id)) {
            $this->toastError();
            return;
        }

        $item = $model::withTrashed()->find($id);

        if ($item && $item->restore()) {
            $this->toastSuccess('restored', $this->singular($screen));
        } else {
            $this->toastError();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Bulk Restore Button
    |--------------------------------------------------------------------------
    */
    public function bulkRestoreButton($screen)
    {
        return Button::make(__('Restore'))
                ->icon('bs.arrow-counterclockwise')
                ->class('bulk-warning btn btn-outline-warning')
                ->confirm($this->confirmMessage('restore', $this->plural($screen)))
                ->method('bulkRestore', [
                    'model' => $this->pathModel($screen),
                    'screen' => $screen,
                ])
                ->canSee(
                    $this->canBulkRestore($screen) &&
                    // if trash filter is not active hide the bulk destroy
                    $this->trashFilterState()
                );
    }

    public function bulkRestore($model, $screen)
    {
        // check permission
        if (!$this->canRestore($screen)) {
            $this->toastNotAuthorized('restore', $screen);
            return;
        }

        // validation
        if (!request()->ids) {
            $this->bulkValidationError('restored');
            return;
        }
        
        $count = 0;
        foreach (request()->ids as $id) {
            $item = $model::withTrashed()->find($id);
            
            if ($item && $item->restore()) {
                $count++;
            }
        }

        // check if any items is restored
        if ($count > 0) {
            $this->toastSuccess('restored', $this->plural($screen));
        } else {
            $this->toastError();
        }
    }
}

