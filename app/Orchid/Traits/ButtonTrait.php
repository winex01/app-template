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
use Illuminate\Support\Facades\Schema;

trait ButtonTrait
{   
    // Table Entries/Record per page value
    public $recordPerPage; 

    // Entries/Record options, ex: 10, 25, 50 etc..
    public $recordPerPageOptions = [10, 25, 50, 75, 100];
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
    | Pagination Entries
    |--------------------------------------------------------------------------
    */
    

    public function entriesPerPageButton(array $recordPerPageOptions = [5, 10, 25, 50, 75, 100])
    {
        $options = [];
        foreach ($recordPerPageOptions as $value) {
            $label = $this->recordPerPage == $value ? __($value) . ' - Active' : __($value);

            $options[] = Button::make($label)->method('setEntriesPerPage', ['limit' => $value]);
        }

        // append 'All' option
        $value = 999999;
        $optionAll = [
            'label' => $this->recordPerPage == $value ? __('All') . ' - Active' : __('All'),
            'value' => $value
        ];

        $options[] = Button::make($optionAll['label'])
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
    public function editButton($screen, $id, $tableName = null)
    {
        // begin transfer to another function
        if ($tableName == null) {
            $tableName = $screen;
        }

        $showButton = true;

        // check if role screen table has deleted_at table
        if (Schema::hasColumn($tableName, 'deleted_at')) {
            $model = 'App\Models\\'.ucfirst(Str::singular($screen));

            // check role, if item is already deleted then dont show the edit button
            $item = $model::withTrashed()->find($id);

            if ($item && $item->trashed()) {

                $showButton = false;
            }

        }

        // end transfer to another fucntion

        return Link::make(__('Edit'))
                ->icon('bs.pencil')
                ->route($screen.'.edit', $id)
                ->canSee($this->canEdit($screen) && $showButton);
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
