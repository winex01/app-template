<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter as BaseFilter;
use Orchid\Screen\Field;

class ExtendedOrchidFilter extends BaseFilter
{

    /**
     * Remove pagination everytime filter is remove.
     * @return string
     */
    public function resetLink(): string
    {
        $params = $this->parameters();

        // add the pagination url parameter so it will be remove when removing the filter
        $params[] = 'page'; // page = pagination url parameter

        // Build the query string
        $params = http_build_query($this->request->except($params));

        return url($this->request->url().'?'.$params);
    }


    // Methods below are abstract class default



    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return '';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return [];
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
        return $builder;
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [];
    }
}
