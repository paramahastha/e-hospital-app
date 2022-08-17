<?php

namespace App\Orchid\Screens\DoctorManagement;

use App\Models\User;
use App\Models\UserInfo;
use App\Orchid\Layouts\DoctorManagement\DoctorEditLayout;
use App\Orchid\Layouts\User\UserCredentialLayout;
use App\Orchid\Layouts\User\UserPasswordLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class DoctorEditScreen extends Screen
{
    /**
     * @var User
     */
    public $user;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(User $user): iterable
    {
        $user->load(['roles']);

        return [
            'user'       => $user,
            'permission' => $user->getStatusPermission(),
        ];
    }

     /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->user->exists ? 'Edit Doctor' : 'Create Doctor';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
                ->method('remove')
                ->canSee($this->user->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
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
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block(DoctorEditLayout::class)
                ->title(__("Profile Information"))
                ->description(__("Fill doctor's profile informations")),
                // ->commands(
                //     Button::make(__('Save'))
                //         ->type(Color::DEFAULT())
                //         ->icon('check')
                //         ->canSee($this->user->exists)
                //         ->method('save')
                // ),

            Layout::block(UserCredentialLayout::class)
                ->title(__('Credential'))
                ->description(__('Ensure your account is using a long, random password to stay secure.'))
                // ->commands(
                //     Button::make(__('Save'))
                //         ->type(Color::DEFAULT())
                //         ->icon('check')
                //         ->canSee($this->user->exists)
                //         ->method('save')
                // ),
        ];
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(User $user, Request $request)
    {
        $request->validate([
            'user.email' => [
                'required',
                Rule::unique(User::class, 'email')->ignore($user),
            ],
            'user.userInfo.date_of_birth' => 'before:today',
            'user.userInfo.identity_number' => [
                'required',
                Rule::unique(UserInfo::class, 'identity_number')->ignore($user->userInfo),
            ],
            'user.userInfo.sip_number' => [
                'required',
                Rule::unique(UserInfo::class, 'sip_number')->ignore($user->userInfo),
            ]
        ]);
        

        $userData = $request->get('user');
        if ($user->exists && (string) $userData['password'] === '') {
            // When updating existing user null password means "do not change current password"
            unset($userData['password']);
        } else {
            $userData['password'] = Hash::make($userData['password']);
        }

        $user
            ->fill($userData)            
            ->save();

        $roleId = Role::where('slug', 'doctor')->first()->id;    
        $user->replaceRoles([$roleId]);

        $userInfo =  $userData["userInfo"];        
    
        $dob = date('Y-m-d', strtotime($userInfo["date_of_birth"]));        

        $userInfoData = [
            'user_id' => $user->id,                   
            'date_of_birth' => $dob,
            'identity_number' => $userInfo["identity_number"],
            'gender' => $userInfo["gender"],
            'phone_number' => $userInfo["phone_number"],
            'address' => $userInfo["address"],
            'sip_number' => $userInfo["sip_number"],
            'photo' => $userInfo["photo"]
        ];        
        
        if ($user->userInfo == null) {                            
            UserInfo::create($userInfoData);    
        } else {                        
            $user->userInfo()->update($userInfoData);
        }

        Toast::info(__('User was saved.'));

        return redirect()->route('platform.doctor.management');
    }
}
