<?php

namespace App\Orchid\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

trait ModelObjectTrait
{
    public function modelObjectSoftDeleted($tableName, $id)
    {
        $softDeleted = false;

        // check if role screen table has deleted_at table / if soft deletes is enabled
        if (Schema::hasColumn($tableName, 'deleted_at')) {
            $model = 'App\Models\\'.ucfirst(Str::singular($tableName));

            // check if item is already deleted then dont show the edit button
            $item = $model::withTrashed()->find($id);

            if ($item && !$item->trashed()) {

                $softDeleted = true;
            }

        }

        return $softDeleted;
    }
}
