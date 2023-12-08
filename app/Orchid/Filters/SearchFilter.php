<?php

namespace App\Orchid\Filters;

use Orchid\Screen\Field;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Input;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Search';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return [
            'search'
        ];
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
        $searchTerm = $this->request->search;

        return $builder->where(function ($query) use ($searchTerm) {
            foreach ($this->searchTableColumns() as $column) {
                $query->orWhere($column, 'like', "%$searchTerm%");
            }
        });
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            Input::make('search')
                ->title('Search')
                ->placeholder('Search...')
                ->type('search')
                ->value($this->request->search)
        ];
    }

    public function searchTableColumns()
    {
        // Note:: Override this in Filters Layout to use.
        return ['test']; 
    }
}
