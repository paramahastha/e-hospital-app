<?php

namespace App\Orchid\Screens\DoctorManagement;

use App\Models\User;
use App\Orchid\Layouts\DoctorManagement\DoctorEditLayout;
use App\Orchid\Layouts\DoctorManagement\DoctorListLayout;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class DoctorListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'users' => User::whereHas('roles', function($query) {
                return $query->where('slug','doctor');
            })->filters()
            ->defaultSort('id', 'desc')
            ->paginate(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Doctor Management';
    }

        /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'List of data';
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.doctor.management',
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('plus')
                ->route('platform.doctor.management.create'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            DoctorListLayout::class,
            
            Layout::modal('asyncEditDoctorModal', DoctorEditLayout::class)
                ->async('asyncGetDoctor'),
        ];
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function asyncGetDoctor(User $user): iterable
    {
        return [
            'user' => $user,
        ];
    }

    /**
     * @param Request $request
     * @param User    $user
     */
    public function saveUser(Request $request, User $user): void
    {
        $request->validate([
            'user.email' => [
                'required',
                Rule::unique(User::class, 'slug')->ignore($user),
            ],
        ]);

        $user->fill($request->input('user'))->save();

        Toast::info(__('User was saved.'));
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        User::findOrFail($request->get('id'))->delete();

        Toast::info(__('User was removed'));
    }
    
}
