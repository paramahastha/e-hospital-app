<?php

namespace App\Orchid\Layouts\ConsultationManagement;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class ConsultationMedicalRecordLayout extends Rows
{
  
    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Quill::make('consultation.medical_record')                
                ->required()
                ->title(__('Medical Record')),    
        ];
    }
}
