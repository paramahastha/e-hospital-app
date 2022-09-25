<?php

namespace App\Orchid\Layouts\TransactionManagement;

use App\Models\GeneralConfig;
use App\Models\Transaction;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\TD;

class TransactionEditLayout extends Rows
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
            Label::make('transaction.code')                                                            
                ->title(__('Transaction Number')),            

            Label::make('transaction.status')                                       
                ->title('Status'),                
            
            Label::make('transaction.payment_status')                                                                                         
                ->title(__('Payment Status')),

            Label::make('transaction.payment_reject_reason')
                ->title(__('Payment Reject Reason')),
                
            Label::make('transaction.created_at')                          
                ->title(__('Date')),
        ];
    }
}
