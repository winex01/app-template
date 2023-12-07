<?php

namespace App\Orchid\Traits;

use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use App\Orchid\Traits\StringTrait;

trait FlashMessageTrait
{
    use StringTrait;
    
    /**
     *
     * @param: $type = deleted, restored, etc..
     * @param: $string = roles
     * @return: toast
     */
    public function toastSuccess($type, $string) 
    {
        Toast::success('You have successfully '.$type.' the '.$string.'.');
    }

    public function toastNotAuthorized($type, $string)
    {
        Toast::error('You do not have permission to '.$type.' '.$string.'.');
    }

    public function confirmMessage($type, $string)
    {
        return 'Are you sure you want to '.$type.' this '.$string.'?';
    }

    public function bulkValidationError($string)
    {
        Alert::error('Please select the row(s) to be '.$string.' by checking the checkbox.');
    }

    public function toastError()
    {
        Toast::error('Whoops, Something went wrong. Please contact the administrator.');
    }
}
