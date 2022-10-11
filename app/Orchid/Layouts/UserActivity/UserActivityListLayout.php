<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\UserActivity;

use App\Models\UserActivity;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class UserActivityListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'user_activities';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('user_code', __('Code'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (UserActivity $userActivity) {                    
                    return $userActivity->user_code;
                }),
            
            TD::make('user_role', __('Role'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (UserActivity $userActivity) {                                    
                    return $userActivity->user_role;                
                }),

            TD::make('activity_name', __('Activity'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (UserActivity $userActivity) {
                    return $userActivity->activity_name;
                }),

            TD::make('activity_module', __('Module'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (UserActivity $userActivity) {
                    return $userActivity->activity_module;
                }),

            TD::make('created_at', __('Date'))
                ->sort()
                ->render(function (UserActivity $userActivity) {
                    return $userActivity->created_at->toDateTimeString();
                }),
        ];
    }
}
