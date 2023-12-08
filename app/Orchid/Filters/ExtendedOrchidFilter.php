<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter as BaseFilter;
use Orchid\Screen\Field;

class ExtendedOrchidFilter extends BaseFilter
{

    /**
     * I overrided this resetLink method(please check service provider), so
     * when a filter is active and remove it will always remove the page and reset to 
     * page 1, because there are times when table has a lot of records and 
     * you have an active filter and goes to page 2 or more and when you remove 
     * the filter the page 2 or the page where you at will still be the same
     * and it might confuse the user if he/she see's the no records found, because
     * the pagination goes to page 2 isnstead of page 1.
     * ex: base example for this is trash filter.
     *
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
