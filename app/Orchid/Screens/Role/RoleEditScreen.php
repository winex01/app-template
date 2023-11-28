<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Role;

use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\Role\RoleEditLayout;
use App\Orchid\Traits\UserActionPermission;
use App\Orchid\Layouts\Role\RolePermissionLayout;

class RoleEditScreen extends Screen
{
    use UserActionPermission;
    
    /**
     * @var Role
     */
    public $role;

    /**
     * Fetch data to be displayed on the screen.
     *
     *
     * @return array
     */
    public function query(Role $role): iterable
    {
        return [
            'role'       => $role,
            'permission' => $role->getStatusPermission(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->role->exists ? __('Edit Role') : __('Create Role');
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        $text = 'the privileges and permissions associated with a specific role.';

        if ($this->role->exists) {
            $text = 'Modify '. $text;
        }else {
            $text = 'Create '. $text;
        }

        return $text;
    }

    public function permission(): ?iterable
    {   
        return [
            'roles.edit',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block([
                RoleEditLayout::class,
            ])
                ->title('Role')
                ->description('A role is a collection of privileges (of possibly different services like the Users service, Moderator, and so on) that grants users with that role the ability to perform certain tasks or operations.'),

            Layout::block([
                RolePermissionLayout::class,
            ])
                ->title('Permission/Privilege')
                ->description('A privilege is necessary to perform certain tasks and operations in an area.'),
        ];
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request, Role $role)
    {
        $request->validate([
            'role.slug' => [
                'required',
                Rule::unique(Role::class, 'slug')->ignore($role),
            ],
        ]);

        $role->fill($request->get('role'));

        $role->permissions = collect($request->get('permissions'))
            ->map(fn ($value, $key) => [base64_decode($key) => $value])
            ->collapse()
            ->toArray();

        $role->save();

        Toast::success(__('Role was saved'));

        return redirect()->route('roles.list');
    }
}
