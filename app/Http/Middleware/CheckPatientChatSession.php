<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPatientChatSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        if (!empty($request->route()->parameters)) {
            $consult = $request->route()->parameters['consult'];
            $currTime = strtotime(now());
            if (strtotime($consult->session_start) > $currTime) {            
                return abort(404, 'Session has not start yet.');
            } else if (strtotime($consult->session_start) < $currTime &&
                 strtotime($consult->session_end) < $currTime) {                
                return abort(404, 'Session has ended.');
            }
    
        }    

        return $response;
    }
}
