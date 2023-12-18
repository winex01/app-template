<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Role;

use Orchid\Screen\TD;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use App\Orchid\Traits\ExtendOrchidTrait;
use App\View\Components\WinexDateTimeSplit;

class RoleListLayout extends Table
{
    use ExtendOrchidTrait;
    /**
     * @var string
     */
    public $target = 'roles';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            $this->columnBulkAction('roles'),

            TD::make('name', __('Name'))
                ->sort()
                ->filter(Input::make()),
            
            TD::make('slug', __('Slug'))
                ->sort()
                ->filter(Input::make()),

            TD::make('created_at', __('Created'))
                ->usingComponent(WinexDateTimeSplit::class)
                ->defaultHidden()
                ->sort(),

            TD::make('updated_at', __('Last edit'))
                ->usingComponent(WinexDateTimeSplit::class)
                ->sort(),

            // Actions
            $this->actions('roles', [
                'restoreButton',
                'editButton',
                'deleteButton',
            ]), 
        ];
    }
    
}
