<?php

namespace App\Orchid\Layouts\TransactionManagement;

use App\Models\Transaction;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class TransactionListLayout extends Table
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
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('code', __('Code'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Transaction $transaction) {
                    return $transaction->code;
                }),
                            
            TD::make('description', __('Description'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Transaction $transaction) {
                    return $transaction->code;
                }),

            TD::make('payment_status', __('Payment Status'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Transaction $transaction) {
                    return $transaction->code;
                }),

            TD::make('status', __('Status'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Transaction $transaction) {
                    return $transaction->code;
                }),           

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Transaction $transaction) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->route('platform.transaction.management.edit', $transaction->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm(__('Once the transaction is deleted, all of its resources and data will be permanently deleted.'))
                                ->method('remove', [
                                    'id' => $transaction->id,
                                ]),
                        ]);
                }),
        ];
    }
}
