<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Session, Socialite;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider($provider)
    {


        if ($provider == 'google') {

        
 
            return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect(); 

        } else {
         //return Socialite::driver($provider)->redirect();
            return redirect()->route('home');
        }
    }

    public function handleProviderCallback($provider)
{
    try{


        $user = Socialite::driver($provider)->stateless()->user();

     //   dd($user);

        // Set token for the Google API PHP Client
        $authUser = $this->findOrCreateUser($user, $provider);
        \Auth::login($authUser, true);
        //\Auth()->login($authUser, true);

        
           $redirectTo = \Session::get('redirect_url');

        //   dd($redirectTo);

        if($redirectTo){
         return redirect()->route($redirectTo);
        

        } else {

         return redirect()->route('home');
        }

    }
    catch(\Exception $e){
  //dd($e);
        return redirect()->route('home');
 
    }
}


    public function findOrCreateUser($user, $provider)
    {
     try{
        $authUser = User::where('email', $user->email)->first();
        if ($authUser) {
        return $authUser;
        }
       else {

        $User_data = new User();
        $User_data->name = $user->name;
        $User_data->password = $user->id;
        $User_data->email = $user->email;
        $User_data->status = 1;
        $User_data->provider = $provider;
        $User_data->user_type = 3;
        $User_data->save();

        $authUser = User::where('email', $user->email)->first();

        return $authUser;

       }
        

    }  catch(\Exception $e){

      //   dd($e);
        return redirect()->route('home');
    }

 }



}
