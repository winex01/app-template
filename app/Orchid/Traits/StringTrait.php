<?php

namespace App\Orchid\Traits;

use Illuminate\Support\Str;

trait StringTrait
{
    /**
     * @param: App/Models/Role
     * @return: roles
     */
    public function screen($string)
    {
        $screen = str_replace('App\Models\\', '', $string);
    
        return $this->plural(strtolower($screen));
    }

    /**
     * @param: roles
     * @return: App/Models/Role
     */
    public function pathModel($string)
    {
        return 'App\Models\\'.ucfirst($this->singular($string));
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
