<?php

namespace App\Exports;

use Carbon\Carbon;
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

    public function columnDates()
    {
        return [
            'created_at',
            'updated_at',
        ];
    }

    public function filterCollection()
    {
        $this->collection = $this->collection->map(function ($item) {
            return collect($item)->filter(function ($value, $key) {
                return in_array($key, $this->columns());
            })->map(function ($value, $key) {
                if (in_array($key, $this->columnDates())) {
                    // Convert to date format using Carbon and app timezone
                    return Carbon::parse($value)->timezone(config('app.timezone'))->format(config('app.date_format'));
                }
                return $value;
            })->toArray();
        });
    }

}
