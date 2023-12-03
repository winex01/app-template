<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        Dashboard::useModel(
            \Orchid\Platform\Models\Role::class,
            \App\Models\Role::class
        );

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Dashboard')
                ->icon('bs.house')
                ->title('Navigation')
                ->route(config('platform.index')),

            Menu::make(__('Users'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access Controls')),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('roles.list')
                ->permission('roles.list')
                ->divider(),


            Menu::make('Sample Screen')
                ->icon('bs.collection')
                ->route('platform.example')
                ->title('Orchid Sample')
                ->badge(fn () => 6),

            Menu::make('Form Elements')
                ->icon('bs.card-list')
                ->route('platform.example.fields')
                ->active('*/examples/form/*'),

            Menu::make('Overview Layouts')
                ->icon('bs.window-sidebar')
                ->route('platform.example.layouts'),

            Menu::make('Grid System')
                ->icon('bs.columns-gap')
                ->route('platform.example.grid'),

            Menu::make('Charts')
                ->icon('bs.bar-chart')
                ->route('platform.example.charts'),

            Menu::make('Cards')
                ->icon('bs.card-text')
                ->route('platform.example.cards')
                ->divider(),

            Menu::make('Documentation')
                ->title('Docs')
                ->icon('bs.box-arrow-up-right')
                ->url('https://orchid.software/en/docs')
                ->target('_blank'),

            Menu::make('Changelog')
                ->icon('bs.box-arrow-up-right')
                ->url('https://github.com/orchidsoftware/platform/blob/master/CHANGELOG.md')
                ->target('_blank')
                ->badge(fn () => Dashboard::version(), Color::DARK),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                // ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),

            ItemPermission::group(__('Roles'))
                ->addPermission('roles.list', __('1. List'))
                ->addPermission('roles.create', __('2. Create'))
                ->addPermission('roles.edit', __('3. Edit'))
                ->addPermission('roles.delete', __('4. Delete'))
                ->addPermission('roles.bulk.delete', __('5. Bulk Delete'))
                ->addPermission('roles.destroy', __('6. Destroy'))
            ,
        ];

        // TODO:: trash only filter permission
    }
}
