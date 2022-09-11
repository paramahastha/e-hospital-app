<?php

namespace App\Orchid\Screens\DoctorManagement;

use App\Helper\UserActivityHelper;
use App\Models\ConsultRule;
use App\Models\ConsultRuleSchedule;
use App\Models\User;
use App\Models\UserInfo;
use App\Orchid\Layouts\DoctorManagement\DoctorConsultRuleEditLayout;
use App\Orchid\Layouts\DoctorManagement\DoctorEditLayout;
use App\Orchid\Layouts\User\UserCredentialLayout;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
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
                ->description(__('Ensure your account is using a long, random password to stay secure.')),
                // ->commands(
                //     Button::make(__('Save'))
                //         ->type(Color::DEFAULT())
                //         ->icon('check')
                //         ->canSee($this->user->exists)
                //         ->method('save')
                // ),

            Layout::block(DoctorConsultRuleEditLayout::class)
                ->title(__('Consultation Rule & Schedule'))
                ->description('Ensure your consultation rule and rules are set correctly.')
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

        $userInfo =  $userData['userInfo'];        
    
        $dob = date('Y-m-d', strtotime($userInfo['date_of_birth']));          

        $userInfoData = [
            'user_id' => $user->id,                   
            'date_of_birth' => $dob,
            'identity_number' => $userInfo['identity_number'],
            'gender' => $userInfo['gender'],
            'phone_number' => $userInfo['phone_number'],
            'address' => $userInfo['address'],
            'sip_number' => $userInfo['sip_number'],
            'photo' => $userInfo['photo'],
            'position' => $userInfo['position'],
            'description' => $userInfo['description'],
        ];                   
        
        if (is_null($user->userInfo)) {
            UserInfo::create($userInfoData);            
        } else {                        
            $user->userInfo()->update($userInfoData);
        }        

        $consultRule = $userData['consultRule'];

        $consultRuleData = [
            'user_id' => $user->id,            
            'duration' => $consultRule['duration'],
            'price' => $consultRule['price'],
            'active' => $consultRule['active'],
        ];
            
        if (is_null($user->consultRule)) {
            $consultRuleModel = new ConsultRule();
            $consultRuleModel->fill($consultRuleData);
            $consultRuleModel->save();                    
        } else {
            $user->consultRule()->update($consultRuleData);
        }

        $schedulesData = $consultRule['schedules'];              

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];        

        $consultRuleId = is_null($user->consultRule) ? $consultRuleModel->id : $user->consultRule->id;
    
        $schedules = ConsultRuleSchedule::where(['consult_rule_id' => $consultRuleId])->get();    
        
        if ($schedules->count() == 0) {
            foreach ($days as $day) {      
                $consultRuleScheduleData = [
                    'consult_rule_id' => $consultRuleId,
                    'day' => $day,
                    'start_time' => DateTime::createFromFormat("H:i", $schedulesData['start_time'][$day]),
                    'end_time' => DateTime::createFromFormat("H:i", $schedulesData['end_time'][$day]),
                    'active' => $schedulesData['day'][$day],
                ];

                ConsultRuleSchedule::create($consultRuleScheduleData);    

                continue;
            }        
        } else {
            foreach ($schedules as $schedule) {
                $day = $schedule->day;
                $consultRuleScheduleData = [                                        
                    'start_time' => DateTime::createFromFormat("H:i", $schedulesData['start_time'][$day]),
                    'end_time' => DateTime::createFromFormat("H:i", $schedulesData['end_time'][$day]),
                    'active' => $schedulesData['day'][$day],
                ];

                $schedule->update($consultRuleScheduleData);
            }
        }

        UserActivityHelper::record('Create or Edit Doctor', UserActivityHelper::$DOCTOR_MANAGEMENT);
        Toast::info(__('Doctor was saved.'));

        return redirect()->route('platform.doctor.management');
    }

    /**
     * @param User $user
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function remove(User $user)
    {
        $user->delete();

        UserActivityHelper::record('Remove Doctor', UserActivityHelper::$DOCTOR_MANAGEMENT);
            
        Toast::info(__('Doctor was removed'));

        return redirect()->route('platform.doctor.management');
    }
}
