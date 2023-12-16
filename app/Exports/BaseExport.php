<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class BaseExport implements FromCollection
{
    protected $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
        
        $this->filterCollection();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->collection;
    }

    // remove ID column
    public function filterCollection()
    {
        // Modify the collection to remove the 'id' column
        $this->collection = $this->collection->map(function ($item) {
            // Remove the 'id' key from each item
            unset($item['id']);
            return $item;
        });
    }

}
