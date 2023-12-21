<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BaseExport implements FromCollection, WithHeadings, WithStyles
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

    public function columns()
    {
        return [
            // columns
        ];
    }

    // Method to retrieve date columns
    public function dates()
    {
        return [
            'created_at', 
            'updated_at'
        ];
    }

    public function headings(): array
    {
        return array_map(function ($column) {
            return ucwords(str_replace('_', ' ', $column));
        }, $this->columns());
    }

    public function styles(Worksheet $sheet)
    {
        // Apply bold styling to the first row (headings)
        $sheet->getStyle('1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);
    }


    // Filter the collection to retain specific keys and format dates
    public function filterCollection()
    {
        $timezone = config('app.timezone');
        $dateColumns = $this->dates();

        // Modify the collection to keep only specific keys and format dates
        $this->collection = $this->collection->map(function ($item) use ($timezone, $dateColumns) {
            return collect($item)->map(function ($value, $key) use ($timezone, $dateColumns) {
                // Check if the column is a date field
                if (in_array($key, $dateColumns)) {
                    return Carbon::parse($value)->setTimezone($timezone)->format(config('app.date_format'));
                    // Set the format as 'm/d/Y h:i:s A' for 12-hour format with AM/PM
                }
                return $value;
            })->only($this->columns())->toArray();
        });
    }
}
