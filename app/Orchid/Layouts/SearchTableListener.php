<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use Illuminate\Http\Request;
use Orchid\Screen\Repository;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Layouts\Listener;

class SearchTableListener extends Listener
{
    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = [
        'search'
    ];

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    protected function layouts(): iterable
    {
        return [
            Layout::rows([
                Input::make('search')
                    ->placeholder('Search...')
                    ->type('text')
                    ->class('form-control rounded')
                    ->style('width: 25%; float: right;'),
            ]),
        ];
    }

    /**
     * Update state
     *
     * @param \Orchid\Screen\Repository $repository
     * @param \Illuminate\Http\Request  $request
     *
     * @return \Orchid\Screen\Repository
     */
    public function handle(Repository $repository, Request $request): Repository
    {
        debug( $request->all());

        return $repository->set('search', $request->searchInput);
    }
}
