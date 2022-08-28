<?php

namespace App\Orchid\Layouts\DoctorManagement;

use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class DoctorConsultRuleEditLayout extends Rows
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
        $this->user = $this->query->get('user');        

        $schedulesData = null;
        if (!is_null($this->user) && !is_null($this->user->consultRule)
            && !is_null($this->user->consultRule->schedules)) {
            $schedulesData = $this->user->consultRule->schedules;
        }
        
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        $collections = [
            Input::make('user.consultRule.duration')
                ->required()                
                ->type('number')                  
                ->title(__('Duration'))
                ->placeholder(__('Consultation Duration')), 
        ];        
        
        if (!empty($schedulesData)) {
            foreach ($schedulesData as $schedule) {                        
                $item = Group::make([
                    CheckBox::make('user.consultRule.schedules.day.'.$schedule->day)     
                        ->value($schedule->active)           
                        ->sendTrueOrFalse()                        
                        ->title(ucwords($schedule->day))
                        ->placeholder('Active'),
                            
                    DateTimer::make('user.consultRule.schedules.start_time.'.$schedule->day)
                        ->title('Start Time')
                        ->value(date('H:i', strtotime($schedule->start_time)))
                        ->noCalendar()                                                      
                        ->format('H:i'),     
                
                    DateTimer::make('user.consultRule.schedules.end_time.'.$schedule->day)
                        ->title('End Time')
                        ->value(date('H:i', strtotime($schedule->end_time)))
                        ->noCalendar()                    
                        ->format('H:i'),
                ]);
    
                array_push($collections, $item);
            };
        } else {
            foreach ($days as $day) {                        
                $item = Group::make([
                    CheckBox::make('user.consultRule.schedules.day.'.$day)  
                        ->checked()              
                        ->sendTrueOrFalse()                        
                        ->title(ucwords($day))
                        ->placeholder('Active'),
                            
                    DateTimer::make('user.consultRule.schedules.start_time.'.$day)
                        ->title('Start Time')                        
                        ->value("09:00")
                        ->noCalendar()                                                      
                        ->format('H:i'),     
                
                    DateTimer::make('user.consultRule.schedules.end_time.'.$day)
                        ->title('End Time')      
                        ->value("17:00")                  
                        ->noCalendar()                    
                        ->format('H:i'),
                ]);
    
                array_push($collections, $item);
            };
        }

        return $collections;
        
    }
}
