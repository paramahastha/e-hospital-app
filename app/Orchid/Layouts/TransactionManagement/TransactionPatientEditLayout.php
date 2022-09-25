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

class TransactionPatientEditLayout extends Rows
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
            Label::make('patient.userInfo.code')                          
                ->title(__('Code')),

            Label::make('patient.name')                                                            
                ->title(__('Name')),
                            
            Label::make('patient.email')                                                            
                ->title(__('Email')),

            Label::make('patient.userInfo.identity_number')                          
                ->title(__('Identity Number')),

            Label::make('patient.userInfo.date_of_birth')                          
                ->title(__('Date of Birth')),

            Label::make('patient.userInfo.gender')                          
                ->title(__('Gender')),

            Label::make('patient.userInfo.phone_number')                          
                ->title(__('Phone Number')),
            
            Label::make('patient.userInfo.address')                          
                ->title(__('Address')),
        ];
    }
}
