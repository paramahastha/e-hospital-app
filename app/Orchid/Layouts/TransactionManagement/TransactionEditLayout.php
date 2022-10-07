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
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\TD;

class TransactionEditLayout extends Rows
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
        
        return [        
            Label::make('transaction.code')                                                            
                ->title(__('Transaction Number')),            

            Label::make('transaction.status')                                       
                ->title('Status'),        
                
            Label::make('transaction.created_at')                          
                ->title(__('Date')),
            
            Picture::make('transaction.proof_of_payment')                                
                ->title(__('Proof of Payment')),

            Label::make('transaction.payment_status')
                ->title(__('Payment Status')),

            TextArea::make('transaction.payment_reject_reason')
                ->title(__('Payment Reject Reason')),                        
        ];
    }
}
