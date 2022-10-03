<?php

namespace App\Orchid\Screens\ConsultationManagement;

use App\Models\Consultation;
use App\Orchid\Layouts\ConsultationManagement\ConsultationListLayout;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Screen;

class ConsultationListScreen extends Screen
{

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {                           
        $consultations = Consultation::whereHas('consultUsers', function($query) {

            $currUser = Auth::user();
            return $query->where('user_id',$currUser->id);

        })->get(); 
        
        return [
            'consultation' => $consultations,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'ConsultationListScreen';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'List of consultation data';
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.consultation.management',
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ConsultationListLayout::class,
        ];
    }
}
