<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Role;

use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\Button;
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
            // TODO:: bulk delete trait
            Button::make(__('Bulk Delete'))
                ->icon('bs.trash3')
                ->confirm('After deleting, the roles will be gone forever.')
                ->method('deleteBulk'),

            
            $this->addButton()
                ->href(route('roles.create'))
                ->canSee($this->canCreate('roles')),
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

    public function delete(Role $role)
    {
        $role->delete();

        Toast::success('You have successfully deleted the role.');
    }
    
    public function deleteBulk(Request $request)
    {
        debug($request->all());
        
        Toast::success('Test successfully.');
    }

    // TODO:: soft delete and hard delete/destroy
    
}
