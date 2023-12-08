<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Role;

use App\Models\Role;
use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use App\Orchid\Traits\ExtendOrchidTrait;
use App\Orchid\Layouts\Role\RoleListLayout;
use App\Orchid\Layouts\Role\RoleFiltersLayout;
use App\Orchid\Layouts\SearchTableListener;

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
        // TODO:: find a way to capture the request->search
        // TODO:: apply url query string filter

        return [
            'roles' => Role::filters(RoleFiltersLayout::class)
                            ->defaultSort('name', 'asc')
                            ->paginate($this->getEntriesPerPage()),
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
        return $this->buttons('roles');
    }

    /**
     * The screen's layout elements.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            SearchTableListener::class,
            RoleFiltersLayout::class,
            RoleListLayout::class,
        ];
    }
}
