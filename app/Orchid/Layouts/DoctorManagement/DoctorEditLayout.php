<?php

namespace App\Orchid\Layouts\DoctorManagement;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Radio;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class DoctorEditLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
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

            DateTimer::make('user.userInfo.date_of_birth')
                ->required()
                ->format('Y-m-d')
                ->title('Date of Birth'),

            Input::make('user.userInfo.identity_number')
                ->required()
                ->type('number')                
                ->title('Identity Number')
                ->placeholder(__('Identity Number')),

            Radio::make('user.userInfo.gender')    
                ->checked()              
                ->value('Male')      
                ->placeholder('Male')            
                ->title('Gender'),   
            
            Radio::make('user.userInfo.gender')                 
                ->placeholder('Female')
                ->value('Female'),      
                
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

            TextArea::make('user.userInfo.address')                        
                ->rows(5)                
                ->title(__('Address'))
                ->placeholder(__('Address')),
        ];
    }
}
