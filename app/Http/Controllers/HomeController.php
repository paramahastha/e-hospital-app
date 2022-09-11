<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $doctorList = User::whereHas('roles', function($query) {
            return $query
                ->where('slug','doctor');                
        })->with('userInfo')->with('consultRule')->get();
                
        return view('home', compact('doctorList'));
    }
}
