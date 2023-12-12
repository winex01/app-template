<?php

namespace App\Orchid\Traits;

use App\Exports\BaseExport;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Maatwebsite\Excel\Facades\Excel;

trait ExportTrait
{
    public function exportButton($screen)
    {
        return DropDown::make('Export')
                ->icon('cloud-download')
                ->list([
                    Button::make('CSV')
                        ->icon('bs.filetype-csv')
                        ->method('csv', request()->all())
                        ->rawClick(),
                    
                    Button::make('PDF')
                        ->icon('bs.filetype-pdf')
                        ->method('pdf', request()->all())
                        ->rawClick(),

                    Button::make('XLSX')
                        ->icon('bs.file-spreadsheet')
                        ->method('xlsx', request()->all())
                        ->rawClick(),
                        
                    Button::make('XLS')
                        ->icon('bs.file-excel')
                        ->method('xls', request()->all())
                        ->rawClick(),
                        
                    
                ]);
                // TODO:: canSee permission
    }

    public function export($fileType)
    {   
        $screen = $this->getScreenFromExportUrl();

        // permission
        if (!$this->canExport($screen)) {
            return $this->toastNotAuthorized('export', $screen);
        }

        return Excel::download(
            new BaseExport($this->query()[$screen]), 
            $screen.'.'.$fileType,
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
