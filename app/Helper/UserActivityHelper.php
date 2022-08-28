<?php

namespace App\Helper;

use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;

class UserActivityHelper
{
    public static $USER_PROFILE = 'user_profile';

    public static function record($activityName, $activityModule)
    {    
        $currUser = Auth::user();        
        
        UserActivity::create([
            'user_id' => $currUser->id,
            'user_code' => !is_null($currUser->userInfo) ? $currUser->userInfo->code : '-', 
            'user_role' => !empty($currUser->roles) ? $currUser->roles[0]->slug : '-',
            'activity_name' => $activityName,
            'activity_module' => $activityModule
        ]);
    }
}
