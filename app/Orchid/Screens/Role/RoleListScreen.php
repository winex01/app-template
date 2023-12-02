<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Role;

use App\Models\Role;
use Orchid\Screen\Action;
use Orchid\Screen\Screen;
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
            'roles' => Role::filters()->defaultSort('name', 'asc')->paginate($this->getEntriesPerPage()),
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
            $this->entriesPerPageButton(),
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

    // TODO:: soft delete and hard delete/destroy
}
