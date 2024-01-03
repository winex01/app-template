<?php

namespace App\Orchid\Traits;

use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Maatwebsite\Excel\Facades\Excel;

trait ExportTrait
{
    public function exportButton($screen)
    {
        return DropDown::make('Export')
                ->id('btn-export')
                ->class('bulk-success btn btn-link')
                ->icon('cloud-download')
                ->list([
                    Button::make('XLSX')
                        ->icon('bs.file-spreadsheet')
                        ->method('xlsx', request()->all())
                        ->rawClick(),
                        
                    Button::make('XLS')
                        ->icon('bs.file-excel')
                        ->method('xls', request()->all())
                        ->rawClick(),
                        
                    Button::make('CSV')
                        ->icon('bs.filetype-csv')
                        ->method('csv', request()->all())
                        ->rawClick(),
                    
                    Button::make('PDF')
                        ->icon('bs.filetype-pdf')
                        ->method('pdf', request()->all())
                        ->rawClick(), 
                ])->canSee($this->canExport($screen));
    }

    public function export($fileType)
    {   
        $screen = $this->getScreenFromExportUrl();

        // permission
        if (!$this->canExport($screen)) {
            return $this->toastNotAuthorized('export', $screen);
        }

        $collections = $this->query()[$screen]; // Retrieve the underlying collection

        $bulkIds = request()->ids;

        // if bulk checkbox is checked
        if ($bulkIds) {

            $filteredItems = $collections->filter(function ($item) use ($bulkIds) {
                return in_array($item->id, $bulkIds);
            });
        
            $collections = $filteredItems;
        }

        $fullClassName = 'App\\Exports\\' .Str::studly($screen).'Export';

        return Excel::download(
            new $fullClassName($collections), 
            Str::upper($screen) . '_' . date('Y-m-d_H-i-s') . '.' . $fileType
        );
    }

    public function csv()
    {
        return $this->export('csv');
    }
    
    public function pdf()
    {
        return $this->export('pdf');
    }

    // excel 2007+
    public function xlsx()
    {
        return $this->export('xlsx');
    }

    // old excel
    public function xls()
    {
        return $this->export('xls');
    }


    public function getScreenFromExportUrl()
    {
        // Get the URL
        $url = request()->url();

        // Get path segments of the URL
        $path = parse_url($url, PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));

        // Remove any empty segments
        $segments = array_filter($segments);

        // Get the count of segments
        $count = count($segments);

        // Variable to store the screen value
        $screen = '';

        // Check if the last segment is 'csv', 'pdf', 'xls', or 'xlsx' and there are more than 1 segment
        $allowedExtensions = ['csv', 'pdf', 'xls', 'xlsx'];
        if ($count > 1 && in_array(end($segments), $allowedExtensions)) {
            // Get the second-to-last segment as the screen value
            $screen = $segments[$count - 2];
        } else {
            // Get the last segment as the screen value
            $screen = end($segments);
        }

        return $screen;
    }

}
