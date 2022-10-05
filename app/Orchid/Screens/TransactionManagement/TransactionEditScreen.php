<?php

namespace App\Orchid\Screens\TransactionManagement;

use App\Helper\UserActivityHelper;
use App\Models\Transaction;
use App\Orchid\Layouts\TransactionManagement\TransactionConsultEditLayout;
use App\Orchid\Layouts\TransactionManagement\TransactionDoctorEditLayout;
use App\Orchid\Layouts\TransactionManagement\TransactionEditLayout;
use App\Orchid\Layouts\TransactionManagement\TransactionPatientEditLayout;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Illuminate\Validation\ValidationException;
use Orchid\Support\Facades\Toast;

class TransactionEditScreen extends Screen
{
    /**
     * @var Transaction
     */
    public $transaction;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Transaction $transaction): iterable
    {            
        return [                        
            'transaction'        => $transaction,
            'patient'            => $transaction->user,
            'doctor'             => $transaction->consultation->user('doctor'),
            'consultation'       => $transaction->consultation
        ];
    }

     /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->transaction->exists ? 'Edit Transaction' : 'Create Transaction';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [            
            Button::make(__('Back'))
                ->icon('check')
                ->method('back'),
        ];
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.transaction.management',
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {        
        return [
            Layout::block(TransactionEditLayout::class)
                ->title(__("Transaction Information"))
                ->commands([                    
                    Button::make(__('Approve'))
                        ->disabled($this->transaction->payment_status != 'waiting')
                        ->type(Color::SUCCESS())
                        ->icon('check')
                        ->method('approvePayment'),
                        
                    Button::make(__('Reject'))
                        ->disabled($this->transaction->payment_status != 'waiting')
                        ->type(Color::DANGER())
                        ->icon('close')
                        ->method('rejectPayment'),
                ]),
                
            Layout::block(TransactionConsultEditLayout::class)
                ->title(__("Consultation Information"))
                ->commands(
                    Button::make(__('Confirm'))
                        ->disabled($this->transaction->consultation->status != 'init')
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->method('confirmConsult')
                ),
                
            Layout::block(TransactionDoctorEditLayout::class)
                ->title(__("Doctor Information")),

            Layout::block(TransactionPatientEditLayout::class)
                ->title(__("Patient Information")),
        ];
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function back()
    {        
        return redirect()->route('platform.transaction.management');
    }

    /**
     * @param Transaction $transaction
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function remove(Transaction $transaction)
    {
        $transaction->delete();

        UserActivityHelper::record('Remove Transaction', UserActivityHelper::$TRANSACTION_MANAGEMENT);
            
        Toast::info(__('Transaction was removed'));

        return redirect()->route('platform.transaction.management');
    }

    public function confirmConsult(Transaction $transaction, Request $request)
    {        
        $consultSession = $transaction->consultation->user('doctor')->consultRule->schedules
        ->where('day', strtolower(date('l', strtotime($transaction->consultation->session_start))))->first();
          
        $validator = Validator::make($request->all(), [
            'consultation.session_start' => 'required',
            'consultation.session_end' => 'required',
        ]);
        
        $sessionStartReq = strtotime(date('H:i',strtotime($request->get('consultation')['session_start'])));
        $sessionStart = strtotime(date('H:i', strtotime($consultSession->start_time)));

        $sessionEndReq = strtotime(date('H:i',strtotime($request->get('consultation')['session_end'])));
        $sessionEnd = strtotime(date('H:i', strtotime($consultSession->end_time)));

        if ($sessionStartReq < $sessionStart || $sessionStartReq > $sessionEnd) {            
            throw ValidationException::withMessages(['consultation.session_start' => 
            'session_start should be more or equal than '. date('H:i', strtotime($consultSession->start_time))]);
        }

        if ($sessionEndReq <= $sessionStartReq || $sessionEndReq > $sessionEnd) {            
            throw ValidationException::withMessages(['consultation.session_end' => 
            'session_end should be less or equal than '. date('H:i', strtotime($consultSession->end_time))]);
        }
                
        $transaction->consultation->session_start = $request->get('consultation')['session_start'];
        $transaction->consultation->session_end = $request->get('consultation')['session_end'];
        $transaction->consultation->status = 'confirm';
        $transaction->consultation->save();

        UserActivityHelper::record('Confirm Consultation', UserActivityHelper::$TRANSACTION_MANAGEMENT);
            
        Toast::info(__('Consultation was confirmed'));

        return redirect()->route('platform.transaction.management');
    }

    public function approvePayment(Transaction $transaction, Request $request)
    {                               
        $transaction->status = 'consult_process'; 
        $transaction->payment_status = 'approve'; 
        $transaction->payment_reject_reason = '';
        $transaction->save();

        $consultUrl = env('APP_URL').'/consult/detail/chat/'.$transaction->consultation->id;
        $contents = 'Consult Date & Link : '.date("Y-m-d H:i", strtotime($transaction->consultation->session_start)).' 
            | '.$consultUrl;

        Mail::raw($contents, function (Message $message) use ($transaction) {
            $message->from('efatimah@email.com');
            $message->subject('E-Fatimah - Your payment is already approved!');    
            $message->to($transaction->user->email);          
        });

        UserActivityHelper::record('Approve Payment Transaction', UserActivityHelper::$TRANSACTION_MANAGEMENT);
            
        Toast::info(__('Payment was approved'));

        return redirect()->route('platform.transaction.management');
    }

    public function rejectPayment(Transaction $transaction, Request $request)
    {                               
        $transaction->status = 'payment_process'; 
        $transaction->payment_status = 'reject';        
        $transaction->payment_reject_reason = $request->get('transaction')['payment_reject_reason'];
        $transaction->save();

        UserActivityHelper::record('Reject Payment Transaction', UserActivityHelper::$TRANSACTION_MANAGEMENT);
            
        Toast::info(__('Payment was rejected'));

        return redirect()->route('platform.transaction.management');
    }
}
