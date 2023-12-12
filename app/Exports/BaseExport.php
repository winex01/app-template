<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class BaseExport implements FromCollection
{
    protected $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->collection;
    }
}
