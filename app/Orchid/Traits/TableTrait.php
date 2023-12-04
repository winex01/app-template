<?php

namespace App\Orchid\Traits;

use Illuminate\Support\Facades\Schema;

trait TableTrait
{
    public function tableHasColumn($tableName, $columnName)
    {
        return Schema::hasColumn($tableName, $columnName);
    }
}
