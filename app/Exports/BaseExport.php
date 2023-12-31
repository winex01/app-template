<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Database\Eloquent\Collection;
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
        return [];
    }

    // Screen dropdown: Configure Columns at button left on screen.
    private function configureColumnsUnchecked()
    {
        $excludeColumns = [];

        // i fetch it using vanilla PHP like this, because this cookie was created in client side, check: export.js
        if(isset($_COOKIE['excludeColumns'])) {
            $excludeColumns = collect(json_decode($_COOKIE['excludeColumns']));
            
            // Map through the collection and convert values to snake case
            $excludeColumns = $excludeColumns->map(function ($item) {

                return Str::snake(str_replace('-', '_', $item));
            })->all();
        }

        return $excludeColumns;
    }

    private function defaultExcludeColumns()
    {
        return [
            'id',
            'deleted_at',
            ...$this->excludeColumns(),
            ...$this->configureColumnsUnchecked()
        ];
    }

    public function filterCollection()
    {
        return $this->collection->map(function ($item) {
            return collect($item)->filter(function ($value, $key) {
                return array_key_exists($key, $this->columnData) && !in_array($key, $this->defaultExcludeColumns());
            })->map(function ($value, $key) {
                if ($this->columnData[$key] === 'datetime') {
                    // Convert to date format using Carbon and app timezone
                    return Carbon::parse($value)->timezone(config('app.timezone'))->format(config('app.date_format'));
                }
                return $value;
            })->toArray();
        });
    }

    public function columnData()
    {
        $items = null;
        $tableName = null;        

        if ($this->collection instanceof Collection) {
            $items = $this->collection;
            $tableName = $this->collection->first()->getTable();

        } else {
            $items = $this->collection->items();

            if (!empty($items)) {
                $tableName = collect($items)->first()->getTable();
            }
        }
        
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
        $this->columnData = collect($columnData)->except($this->defaultExcludeColumns())->toArray();
    }
}
