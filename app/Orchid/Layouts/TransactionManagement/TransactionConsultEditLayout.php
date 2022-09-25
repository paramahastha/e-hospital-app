<?php

namespace App\Orchid\Layouts\TransactionManagement;

use App\Models\GeneralConfig;
use App\Models\Transaction;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\TD;

class TransactionConsultEditLayout extends Rows
{   
    /**
     * @var Transaction|null
     */
    private $transaction;
    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {       
        
        $this->transaction = $this->query->get('transaction');

        $consultation = $this->transaction->consultation;
        
        return [        
            Label::make('consultation.status')                          
                ->title(__('Status')),

            Label::make('doctor.consultRule.duration')                                                            
                ->title(__('Duration')),

            DateTimer::make('consultation.session_start')
                ->title('Session Start')
                ->value(date('H:i', strtotime($consultation->session_start)))
                ->noCalendar()                                                      
                ->format('Y-m-d H:i:S'),
                            
            DateTimer::make('consultation.session_end')
                ->title('Session End')
                ->value(date('H:i', strtotime($consultation->session_start)))
                ->noCalendar()                                                      
                ->format('Y-m-d H:i:S'),            
        ];
    }
}
