<?php

namespace App\Http\Controllers;

use App\Models\User;

class DoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function detail(User $user)
    {             
        return view('doctor.detail', compact('user'));
    }
}
