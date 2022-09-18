<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\ConsultationUser;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultController extends Controller
{
    public function create(User $user, Request $request)
    {            
        $request->validate([
            'consultDate' => 'required',
        ]);
        
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
}
