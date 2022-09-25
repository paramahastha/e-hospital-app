<?php

namespace App\Orchid\Layouts\TransactionManagement;

use App\Models\Transaction;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\TD;

class TransactionDoctorEditLayout extends Rows
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'transactions';

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {

        return [        
            Label::make('doctor.userInfo.code')                          
                ->title(__('Code')),

            Label::make('doctor.name')                                                            
                ->title(__('Name')),
                            
            Label::make('doctor.userInfo.position')                          
                ->title(__('Position')),
        ];
    }
}
