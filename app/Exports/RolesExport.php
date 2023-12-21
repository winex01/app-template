<?php

namespace App\Exports;

use App\Exports\BaseExport;
use Maatwebsite\Excel\Concerns\FromCollection;

class RolesExport extends BaseExport implements FromCollection
{
    public function columns()
    {
        return [
            'name', 
            'slug', 
            'created_at', 
            'updated_at', 
        ];
    }
}
