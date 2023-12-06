<?php

namespace App\Orchid\Traits;

use Illuminate\Support\Str;
use App\Orchid\Traits\TableTrait;

trait ModelObjectTrait
{
    use TableTrait;

    public function softDeleted($tableName, $id)
    {
        // Check if the table has the 'deleted_at' column for soft deletes
        if ($this->tableHasColumn($tableName, 'deleted_at')) {
            $modelClass = 'App\Models\\' . ucfirst(Str::singular($tableName));

            // Retrieve the item including soft deleted items
            $item = $modelClass::withTrashed()->find($id);

            // Check if the item exists and is soft deleted
            if ($item && $item->trashed()) {
                return true; // Soft deleted
            }
        }

        return false; // Not soft deleted or soft delete functionality is not enabled
    }

}
