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
use Illuminate\Support\Facades\Cookie;
use App\Orchid\Traits\ModelObjectTrait;

trait ButtonTrait
{   
    use ModelObjectTrait;

    // Table Entries/Record per page value
    public $recordPerPage; 

    // Entries/Record options, ex: 10, 25, 50 etc..
    public $recordPerPageOptions = [10, 25, 50, 75, 100];
    /*
    |--------------------------------------------------------------------------
    | Actions Buttons
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
    | SoftDeleted Action Buttons
    |--------------------------------------------------------------------------
    */
    // TODO:: here
    public function destroyButton($screen, $id, $tableName = null)
    {
        $model = ucfirst(Str::singular($screen));

        return Button::make(__('Destroy'))
                ->icon('bs.trash3')
                ->class('btn btn-sm btn-danger')
                ->confirm('Are you sure you want to remove this '.Str::singular($screen).' in the database.')
                ->method('destroy', [
                    'model' => 'App\Models\\'.$model, // you can override this if you chain the deleteButton
                    'id' => $id,
                ])
                ->canSee(
                    $this->canDestroy($screen) &&
                    $this->modelObjectSoftDeleted($tableName ?? $screen, $id) // show only if softDeleted
                );
    }

    // TODO:: TBD bulk destroy button, and if filter is active hide the normal bulk Delete,
    
    public function destroy($model, $id)
    {
        if ($model::withTrashed()->find($id)->forceDelete()) {

            $label = str_replace('App\Models\\', '', $model);

            Toast::success('You have successfully remove the '.$label.' in the database.');
            
        }else {
            
            Toast::error('Something went wrong, please contact administrator.');

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Pagination Entries
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
    | Create
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
    | Edit
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
                    !$this->modelObjectSoftDeleted($tableName ?? $screen, $id) // ! - if item is not soft deleted then show this button
                );
    }    
    
    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function deleteButton($screen, $id, $tableName = null)
    {
        $model = ucfirst(Str::singular($screen));

        return Button::make(__('Delete'))
                ->icon('bs.trash3')
                ->class('btn btn-sm btn-danger')
                ->confirm('After deleting, the '.Str::singular($screen).' will be gone forever.')
                ->method('delete', [
                    'model' => 'App\Models\\'.$model, // you can override this if you chain the deleteButton
                    'id' => $id,
                ])
                ->canSee(
                    $this->canDelete($screen) &&
                    !$this->modelObjectSoftDeleted($tableName ?? $screen, $id) // ! - if item is not softDeleted then show this button
                );
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

        return Button::make(__('Delete'))
                ->icon('bs.trash3')
                ->class('btn btn-outline-danger')
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
