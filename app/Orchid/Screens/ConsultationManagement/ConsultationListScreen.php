<?php

namespace App\Orchid\Screens\ConsultationManagement;

use App\Models\Consultation;
use Orchid\Screen\Screen;

class ConsultationListScreen extends Screen
{
    /**
     * @var Consultation
     */
    public $consultation;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Consultation $consultation): iterable
    {            
        dd($consultation);

        return [                        
            'consultation'       => $consultation,
            'patient'            => $consultation->user('patient'),
            'doctor'             => $consultation->user('doctor'),
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
        return [];
    }
}
