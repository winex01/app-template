<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BaseExport implements FromCollection, WithHeadings, WithStyles
{
    protected $collection;
    protected $columnData;

    public function __construct($collection)
    {
        $this->collection = $collection;

        $this->columnData();


        // TODO:: table slug and use it to get localStorage
        // TODO:: check or search laravel package for web localStorage and dont reinvent if package already exist
        // dd($this->getSlug());
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->filterCollection();
    }

    public function headings(): array
    {
        return array_map(function ($column) {
            return ucwords(str_replace('_', ' ', $column));
        }, array_keys($this->columnData));
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

    /*
    |--------------------------------------------------------------------------
    | My Custom Code not related to Laravel Excel Packages
    |--------------------------------------------------------------------------
    */
    public function columns()
    {
        return array_keys($this->columnData);
    }

    public function excludeColumns()
    {
        return [
            'id',
            'deleted_at',
        ];
    }

    public function filterCollection()
    {
        return $this->collection->map(function ($item) {
            return collect($item)->filter(function ($value, $key) {
                return array_key_exists($key, $this->columnData) && !in_array($key, $this->excludeColumns());
            })->map(function ($value, $key) {
                if ($this->columnData[$key] === 'datetime') {
                    // Convert to date format using Carbon and app timezone
                    return Carbon::parse($value)->timezone(config('app.timezone'))->format(config('app.date_format'));
                }
                return $value;
            })->toArray();
        });
    }

    // TODO:: fix if checkbox is use no paginator instance only models
    // TODO:: check also if no records is showing because of applied filters
    public function columnData()
    {
        // dd($this->collection);

        $items = $this->collection->items();

        // Check if items exist and if the first item is an Eloquent model
        if (!empty($items) && $items[0] instanceof \Illuminate\Database\Eloquent\Model) {
            $firstItem = $items[0];
            $tableName = $firstItem->getTable();

            // Get the columns of the table with data types
            $columnData = collect(Schema::getColumnListing($tableName))
                ->mapWithKeys(function ($column) use ($tableName) {
                    $columnType = Schema::getColumnType($tableName, $column);

                    // Map Laravel column types to PHP types
                    $dataType = match ($columnType) {
                        'bigint' => 'integer',
                        'boolean' => 'boolean',
                        'date', 'datetime', 'timestamp' => 'datetime',
                        'decimal', 'double', 'float' => 'float',
                        default => 'string',
                    };

                    return [$column => $dataType];
                })->toArray();

            // Remove excluded columns from columnData
            $this->columnData = collect($columnData)->except($this->excludeColumns())->toArray();
        }
    }
}
