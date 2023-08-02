<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Auth\Guard;
class Authenticate
{
    protected $auth;
    /**
     * Create a new filter instance.
     * @param  Guard  $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    // public function handle($request, Closure $next)
    // {
    //     if ($this->auth->guest()) {

    //         if ($request->ajax()) {
    //             return response('Unauthorized.', 401);
    //         } else {
    //             return redirect()->guest('/');
    //         }
    //     }

    //     $currentRouteName = Route::currentRouteName();
    //     return $next($request);
    // }

    public function handle($request, Closure $next)
    {
        
        
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('/');
            }
        }


        if(\Auth::User()->user_type == 1 || \Auth::User()->user_type == 3 || \Auth::User()->user_type == 6){
            $currentRouteName = Route::currentRouteName();
            return $next($request);
        }
        else{
            return redirect()->route('home');
            //return redirect()->guest('/login');
        }
        
    }
}
