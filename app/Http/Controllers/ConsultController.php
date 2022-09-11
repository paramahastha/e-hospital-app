<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\ConsultationUser;
use App\Models\User;
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
            '".$request->get('consultDate')."'")->whereHas('consultUsers', function($query) use ($currUser, $user) {                
                return $query
                    ->whereIn('user_id', [$currUser->id, $user->id]);                    
        })->first();
        
        if (is_null($consult)) {
            $consult = new Consultation();        
            $consult->session_start = $selectedDate;
            $consult->session_end = $selectedDate;
            $consult->status = 'init';
            $consult->save();     
            
            $patient = new ConsultationUser();
            $patient->user_id = $currUser->id;
            $patient->consultation_id = $consult->id;
            $patient->save();

            $doctor = new ConsultationUser();
            $doctor->user_id = $user->id;
            $doctor->consultation_id = $consult->id;
            $doctor->save();

            return view('consult.personal', compact('user', 'consult'));
        } 
          
        $consult->session_start = $selectedDate;
        $consult->session_end = $selectedDate;
        $consult->status = 'init';
        $consult->save();     

        return view('consult.personal', compact('user'));
    }
}
