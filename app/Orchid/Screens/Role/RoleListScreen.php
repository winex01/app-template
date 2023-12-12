<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Role;

use App\Models\Role;
use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use App\Exports\BaseExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Orchid\Traits\ExtendOrchidTrait;
use App\Orchid\Layouts\Role\RoleListLayout;
use App\Orchid\Layouts\Role\RoleFiltersLayout;

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
            'roles' => Role::filters(RoleFiltersLayout::class)
                        ->defaultSort('name', 'asc')
                        ->paginate($this->getEntriesPerPage()),
        ];
    }

    // TODO:: make it resuable and no update anomaly
    public function export()
    {   
        // TODO:: create modal and have options to select from: Excel, Excel2007, PDF, CSV
        // TODO:: create auto generated file name

        $screen = $this->screen();
        if (!$this->canExport($screen)) {
            
            return $this->toastNotAuthorized('export', $screen);
        }

        return Excel::download(
            new BaseExport($this->query()[$screen]), 
            $screen.'.pdf'
        );
    }

    // TODO:: refactor into trait method
    public function screen()
    {
        // Get the URL
        $url = request()->url();

        // Get segments of the URL
        $segments = explode('/', rtrim(parse_url($url, PHP_URL_PATH), '/'));

        // Variable to store the screen value
        $screen = '';

        // Check if the last segment is 'export'
        if (end($segments) === 'export' && count($segments) >= 2) {
            // Get the second-to-last segment as the screen value
            $screen = $segments[count($segments) - 2];
        } else {
            // Get the last segment as the screen value
            $screen = end($segments);
        }

        return $screen;
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
            RoleFiltersLayout::class,
            RoleListLayout::class,
        ];
    }
}
    