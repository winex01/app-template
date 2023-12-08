<?php

namespace App\Orchid\Filters;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Illuminate\Database\Eloquent\Builder;
use App\Orchid\Filters\ExtendedOrchidFilter;

class TrashFilter extends ExtendedOrchidFilter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Trash';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['trash_only'];
    }

    /**
     * Apply to a given Eloquent query builder.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->onlyTrashed();
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            CheckBox::make('trash_only')
                ->title('Trashed Only')
                ->placeholder('Show Deleted Items.')
                ->value($this->request->get('trash_only'))
                ->sendTrueOrFalse()
        ];
    }

    /**
     * Value to be displayed
     */
    public function value(): string
    {
        return $this->name().': Active';
    }
}
