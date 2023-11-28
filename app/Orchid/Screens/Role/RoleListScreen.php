<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Role;

use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Platform\Models\Role;
use Orchid\Support\Facades\Toast;
use App\Orchid\Layouts\Role\RoleListLayout;
use App\Orchid\Traits\ExtendOrchidTrait;

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
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
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

    // TODO:: bulk delete
    // TODO:: soft delete and hard delete/destroy

    
}
