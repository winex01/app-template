<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Role;

use App\Models\Role;
use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use App\Orchid\Traits\ExtendOrchidTrait;
use App\Orchid\Layouts\Role\RoleListLayout;

class RoleListScreen extends Screen
{
    use ExtendOrchidTrait;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'roles' => Role::filters()->defaultSort('id', 'desc')->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Role Management';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'A comprehensive list of all roles, including their permissions and associated users.';
    }

    public function permission(): ?iterable
    {
        // TODO::
        return [
            'roles.list'
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            $this->bulkDeleteButton('roles'),
            $this->addButton('roles'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            RoleListLayout::class,
        ];
    }
    
    // TODO:: put this on trait.
    public function deleteBulk(Request $request)
    {
        if (!$request->roles) {

            Alert::error('Please select the row(s) to be deleted by checking the checkbox.');

        }else {

            Role::whereIn('id', $request->roles)->delete();
    
            Toast::success('You have successfully deleted the selected roles.');
        }

    }

    // TODO:: soft delete and hard delete/destroy
    
}
