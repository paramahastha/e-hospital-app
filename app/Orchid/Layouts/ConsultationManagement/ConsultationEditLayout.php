<?php

namespace App\Orchid\Layouts\ConsultationManagement;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Layouts\Rows;

class ConsultationEditLayout extends Rows
{
  
    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {

        return [
            Label::make('consultation.id')                                                            
            ->title(__('ID')),            

            Label::make('consultation.session_start')                                       
                ->title('Session Start'),        
                
            Label::make('consultation.session_end')                                       
                ->title('Session End'),

            Label::make('consultation.status')                                       
                ->title('Status'),        
        ];
    }
}
