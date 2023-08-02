<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Auth\Guard;
class AuthenticateUserFront
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
    public function handle($request, Closure $next)
    { 
        if(\Auth::check()){
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
               if(\Auth::User()->user_type == 3 || \Auth::User()->user_type == 4){
                return redirect()->guest('/');
            }
            }
        }
    }
    else {
          $redirect_url = Route::currentRouteName();
          \Session::start();
          \Session::put('redirect_url', $redirect_url);
         
          return back()->with('login_popup', 'Login please');
        }

        $currentRouteName = Route::currentRouteName();
        return $next($request);
    }
}
