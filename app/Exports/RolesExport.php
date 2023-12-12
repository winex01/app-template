<?php

namespace App\Exports;

use App\Models\Role;
use App\Orchid\Layouts\Role\RoleFiltersLayout;
use Maatwebsite\Excel\Concerns\FromCollection;

class RolesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Role::filters(RoleFiltersLayout::class)
                ->defaultSort('name', 'asc')
                ->get();
    }
}
