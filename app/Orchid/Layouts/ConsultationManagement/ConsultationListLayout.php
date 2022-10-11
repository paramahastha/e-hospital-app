<?php

namespace App\Orchid\Layouts\ConsultationManagement;

use App\Models\Consultation;
use App\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ConsultationListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'consultation';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', __('ID'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Consultation $consultation) {
                    return $consultation->id;
                }),  

            TD::make('session_start', __('Session Start'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Consultation $consultation) {
                    return $consultation->session_start;
                }),  
                
            TD::make('session_end', __('Session End'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Consultation $consultation) {
                    return $consultation->session_end;
                }),
            
            TD::make('status', __('Status'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Consultation $consultation) {
                    return $consultation->status;
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Consultation $consultation) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make(__('Detail'))
                                ->route('platform.consultation.management.detail', $consultation->id)
                                ->icon('pencil'),
                        ]);
                }),
        ];
    }
}
