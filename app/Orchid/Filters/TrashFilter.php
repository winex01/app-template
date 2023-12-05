<?php

namespace App\Orchid\Filters;

use Orchid\Screen\Field;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\CheckBox;
use Illuminate\Database\Eloquent\Builder;

class TrashFilter extends Filter
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
