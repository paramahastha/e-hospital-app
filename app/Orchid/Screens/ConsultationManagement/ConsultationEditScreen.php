<?php

namespace App\Orchid\Screens\ConsultationManagement;

use App\Helper\UserActivityHelper;
use App\Models\Consultation;
use App\Models\Transaction;
use App\Orchid\Layouts\ConsultationManagement\ConsultationEditLayout;
use App\Orchid\Layouts\ConsultationManagement\ConsultationMedicalRecordLayout;
use App\Orchid\Layouts\ConsultationManagement\ConsultationReceipeRecordLayout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ConsultationEditScreen extends Screen
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
        return [
            'consultation' => $consultation,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'ConsultationEditScreen';
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
        $consultValidation = (strtotime(date("Y-m-d H:i:s")) > strtotime($this->consultation->session_start)
            && strtotime(date("Y-m-d H:i:s")) < strtotime($this->consultation->session_end)); 
            
        $patient = $this->consultation->user('patient');

        $transaction = Transaction::with('consultation')
            ->where('consultation_id', $this->consultation->id)
            ->where('user_id', $patient->id)->first();

        return [
            Layout::block(ConsultationEditLayout::class)
                ->title(__("Consultation Information"))
                ->commands(
                    Button::make(__('Consultation'))
                        ->disabled($this->consultation->status != 'confirm' && $this->consultation->status != 'ongoing' || !$consultValidation)
                        ->type(Color::PRIMARY())
                        ->icon('bubbles')
                        ->method('startConsultSession'),
                ),

            Layout::block(ConsultationMedicalRecordLayout::class)
                ->title(__("Medical Record Information"))
                // ->commands(
                //     Button::make(__('Submit'))
                //         // ->disabled($transaction->status == 'done')
                //         ->type(Color::PRIMARY())
                //         ->icon('doc')
                //         ->method('submitMedicalRecord'),
                // )
                ,


            Layout::block(ConsultationReceipeRecordLayout::class)
            ->title(__("Reciepe Record Information"))
            ->commands(
                Button::make(__('Submit'))
                    // ->disabled($transaction->status == 'done')
                    ->type(Color::PRIMARY())
                    ->icon('doc')
                    ->method('submitMedicalRecord'),
            ),




                
        ];
    }

    public function startConsultSession(Consultation $consult, Request $request)
    {                               
        if ($consult->status != 'ongoing') {
            $consult->status = 'ongoing';         
            $consult->save();

            UserActivityHelper::record('Doctor Start Consultation', UserActivityHelper::$CONSULTATION_MANAGEMENT);
            
            Toast::info(__('Consultation was started'));
        }               
         
        return redirect()->route('consult.doctor.detail.chat', ['consult' => $consult->id]);
    }

    public function submitMedicalRecord(Consultation $consult, Request $request)
    {                                       
        $request->validate([
            'consultation.medical_record' => 'required',
            'consultation.receipe_record' => 'required'
        ]);
        
        $patient = $consult->user('patient');
        
        $transaction = Transaction::with('consultation')
            ->where('consultation_id', $consult->id)
            ->where('user_id', $patient->id)->first();

        $transaction->status = 'done';
        $consult->medical_record = $request->get('consultation')['medical_record'];   
        $consult->receipe_record = $request->get('consultation')['receipe_record'];       
        $transaction->save();
        $consult->save();

        UserActivityHelper::record('Doctor Submit Medical Record', UserActivityHelper::$CONSULTATION_MANAGEMENT);
        
        Toast::info(__('Medical Record and Receipe was submitted'));
        
    }
}
