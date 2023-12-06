<?php

namespace App\Orchid\Traits;

use Illuminate\Support\Str;

trait StringTrait
{
    //
    public function pathModelToScreen($pathModel)
    {
        // params ex: App/Models/Role

        $screen = str_replace('App\Models\\', '', $pathModel);
    
        $screen = Str::plural(strtolower($screen));

        return $screen;
    }

    public function plural($string)
    {
        return Str::plural($string);
    }

    public function singular($string)
    {
        return Str::singular($string);
    }
}
