<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\ConsultationUser;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ConsultController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(User $user, Request $request)
    {            
        $request->validate([
            'consultDate' => 'required',
        ]);

        $consultSession = $user->consultRule->schedules
        ->where('day', strtolower(date('l', strtotime($request->get('consultDate')))))
        ->where('active', '1')->first();
        
        if (is_null($consultSession)) {            
            throw ValidationException::withMessages(['consultDate' => 
            'consultation date must be on schedule']);
        }
        
        $currUser = Auth::user();
        
        $selectedDate = date('Y-m-d H:i:s', strtotime($request->get('consultDate')));
        
        $consult = Consultation::with('consultUsers')->whereRaw("DATE_FORMAT(session_start, '%Y-%m-%d') = 
            '".$request->get('consultDate')."'")->whereHas('consultUsers', function($query) use ($user) {                
                $currUser = Auth::user();   

                return $query
                    ->whereIn('user_id', [$currUser->id, $user->id])
                    ->where('status', 'init');
        })->first();
                
        if (!is_null($consult)) {

            $consult->session_start = $selectedDate;
            $consult->session_end = $selectedDate;
            $consult->status = 'init';
            $consult->save();   

            $trx = Transaction::where('user_id', $currUser->id)->where('consultation_id', $consult->id)->first();
            
            if (is_null($trx)) {
                $trx = new Transaction();
                $trx->user_id = $currUser->id;
                $trx->consultation_id = $consult->id;

                $trxCode = "TRX". $currUser->id . date("dmy") . rand(0,99);
                $trx->code = $trxCode;
                $trx->total_price = $user->consultRule->price;

                $trx->description = "consultation transaction";
                $trx->payment_status = "init";
                $trx->status = "init";
                $trx->save();
            }
            
            return view('consult.personal', compact('user', 'currUser', 'consult'));
        } 
        
        $consult = new Consultation();        
        $consult->session_start = $selectedDate;
        $consult->session_end = $selectedDate;
        $consult->status = 'init';
        $consult->save();    
        
        $trx = Transaction::where('user_id', $currUser->id)->where('consultation_id', $consult->id)->first();
            
        if (is_null($trx)) {
            $trx = new Transaction();
            $trx->user_id = $currUser->id;
            $trx->consultation_id = $consult->id;

            $trxCode = "TRX". $currUser->id . date("dmy") . rand(0,99);
            $trx->code = $trxCode;
            $trx->total_price = $user->consultRule->price;

            $trx->description = "consultation transaction";
            $trx->payment_status = "init";
            $trx->status = "init";
            $trx->save();
        }
        
        $patient = new ConsultationUser();
        $patient->user_id = $currUser->id;
        $patient->consultation_id = $consult->id;
        $patient->save();

        $doctor = new ConsultationUser();
        $doctor->user_id = $user->id;
        $doctor->consultation_id = $consult->id;
        $doctor->save();

        return view('consult.personal', compact('user', 'currUser', 'consult'));
    }

    public function complete(User $user, Consultation $consult, Request $request) {        
        $request->validate([
            'dob' => 'before:today,required',
            'gender' => 'required',
            'identity_number' => 'required',
            'phone_number' => 'required'            
        ]);

        $currUser = Auth::user();   

        $userInfo = UserInfo::where('user_id', $currUser->id)->first();

        if (is_null($userInfo)) {
            $userInfo = new UserInfo();
            $userInfo->user_id = $currUser->id;
            $userInfo->gender = $request->get('gender');
            $userInfo->date_of_birth = $request->get('dob');
            $userInfo->identity_number = $request->get('identity_number');
            $userInfo->phone_number = $request->get('phone_number');
            $userInfo->address = $request->get('address');
            $userInfo->save();

            return view('consult.complete', compact('user', 'currUser'));
        }

        $userInfo->gender = $request->get('gender');
        $userInfo->date_of_birth = $request->get('dob');
        $userInfo->identity_number = $request->get('identity_number');
        $userInfo->phone_number = $request->get('phone_number');
        $userInfo->address = $request->get('address');

        $userInfo->save();

        return view('consult.complete', compact('user', 'currUser'));
    }

    public function consultChat(Consultation $consult, Request $request) {       
        return view('consult.chat', compact('consult'));
    }

    public function fetchMessages(Consultation $consult)
    {         
        return Message::with('user', 'consultation')->where('consultation_id', $consult->id )->get();
    }

    public function sendMessage(Request $request)
    {         
        $consult = Consultation::with('consultUsers')->find($request->consult["id"]);

        if (is_null($consult)) {
            return ['status' => 'Message Failed!'];
        }
        
        $user = Auth::user();
        $message = $user->messages()->create([
            'consultation_id' => $request->consult["id"],
            'message' => $request->input('message')
        ]);

        broadcast(new MessageSent($user, $message))->toOthers();
        
        return ['status' => 'Message Sent!'];
    }
}
