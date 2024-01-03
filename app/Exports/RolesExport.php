<?php

namespace App\Exports;

use App\Exports\BaseExport;
use Maatwebsite\Excel\Concerns\FromCollection;

class RolesExport extends BaseExport implements FromCollection
{
    public function excludeColumns()
    {
        return [
            'id',
            'deleted_at',
            'permissions',
        ];
    }
}
