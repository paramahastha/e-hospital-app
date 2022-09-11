<?php

namespace App\Orchid\Layouts\DoctorManagement;

use App\Models\GeneralConfig;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Radio;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class DoctorEditLayout extends Rows
{
    /**
     * @var User|null
     */
    private $user;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        $positions = GeneralConfig::where('config_group', 'doctor_position')
            ->pluck('config_key', 'config_value');

        return [
            Cropper::make('user.userInfo.photo')
                ->title('Photo')                
                ->required()
                ->width(500)
                ->height(500),

            Input::make('user.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Name'))
                ->placeholder(__('Name')),   
                
            Select::make('user.userInfo.position')                            
                ->required()
                ->options($positions)
                ->title('Position')
                ->placeholder(__('Select Position')), 

            DateTimer::make('user.userInfo.date_of_birth')
                ->required()
                ->format('Y-m-d')
                ->title('Date of Birth'),

            Input::make('user.userInfo.identity_number')
                ->required()
                ->type('number')                
                ->title('Identity Number')
                ->placeholder(__('Identity Number')),
            
            Select::make('user.userInfo.gender')
                ->required()
                ->options([
                    'Male'   => 'Male',
                    'Female' => 'Female',
                ])                                
                ->title('Gender')
                ->placeholder(__('Select Gender')),
                
            Input::make('user.userInfo.sip_number')
                ->required()
                ->type('number')                
                ->title('SIP Number')
                ->placeholder(__('SIP Number')),

            Input::make('user.userInfo.phone_number')
                ->type('number')                
                ->required()
                ->title('Phone number')
                ->placeholder(__('Phone number')),

            TextArea::make('user.userInfo.description')                        
                ->rows(5)                
                ->title(__('Profile Description'))
                ->placeholder(__('Profile Description')),

            TextArea::make('user.userInfo.address')                        
                ->rows(5)                
                ->title(__('Address'))
                ->placeholder(__('Address')),
        ];
    }
}
