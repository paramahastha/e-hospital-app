<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Show the user transaction.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $currUser = Auth::user();
        $transactionList = Transaction::with('consultation')->where('user_id', $currUser->id)->get();        
                
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
}