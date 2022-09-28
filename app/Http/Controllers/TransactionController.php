<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the user transaction.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $currUser = Auth::user();
        $transactionList = Transaction::with('consultation')
            ->where('user_id', $currUser->id)->whereHas('user', function($query) {
                $query->whereHas('userInfo');
            })->get();
                
        return view('transaction.index', compact('transactionList'));
    }

    /**
     * Show the user transaction.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function detail(Transaction $transaction)
    {
        $patient = Auth::user();

        $consult = $transaction->consultation;
        
        $doctor = $transaction->consultation->user('doctor');        
                
        return view('transaction.detail', compact('transaction', 'patient', 'consult', 'doctor'));
    }

    /**
     * upload proof of payment from transaction detail
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function uploadPayment(Transaction $transaction, Request $request)
    {
        $request->validate([
            'proof_of_payment' => 'required|mimes:jpg,jpeg,png'
        ]);    
    
        if($request->file()) {
            $fileName = $request->proof_of_payment->getClientOriginalName();
            $filePath = $request->file('proof_of_payment')->storeAs('uploads', $fileName, 'public');                        
            $transaction->proof_of_payment = '/storage/' . $filePath;
            $transaction->payment_status = 'waiting';
            $transaction->status = 'payment_process';
            $transaction->save();
            return back()->with('success','File has been uploaded.')->with('proof_of_payment', $fileName);
        }    
    }

    /**
     * download proof of payment from transaction detail
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function downloadPayment(Transaction $transaction, Request $request)
    {
        if (!is_null($transaction->proof_of_payment)) {            
            return Response::download(public_path($transaction->proof_of_payment));
        }
    }
}
