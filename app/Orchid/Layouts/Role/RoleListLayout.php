<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Role;

use App\Orchid\Traits\ExtendOrchidTrait;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Components\Cells\DateTimeSplit;

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
            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('slug', __('Slug'))
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('created_at', __('Created'))
                ->usingComponent(DateTimeSplit::class)
                ->defaultHidden()
                ->sort(),

            TD::make('updated_at', __('Last edit'))
                ->usingComponent(DateTimeSplit::class)
                ->sort(),

            // Actions
            // TODO:: add align width, and etc to trait so could avoid update anomally
            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Role $role) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([

                        Link::make(__('Edit'))
                            ->route('roles.edit', $role->id)
                            ->icon('bs.pencil')
                            ->canSee($this->canEdit('roles')),
                        
                        Button::make(__('Delete'))
                                ->icon('bs.trash3')
                                ->confirm('After deleting, the role will be gone forever.')
                                ->method('delete', ['role' => $role->id]),
                                // TODO:: add canSee permission
                        
                    ])),
        ];
    }


    
}
