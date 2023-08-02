<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Models\Cart;
use App\Models\GuestCart;
use Illuminate\Http\Request;

class ApiAuthenticate
{
   protected $httpAuthLogin = 'api/v1/login';

    /**
     * Path for logout and clear authorization cache.
     * @var string
     */
    protected $httpAuthLogout = 'api/v1/logout';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {  
        try{ 
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

           if (trim($_SERVER['PHP_AUTH_USER']) != '' && trim($_SERVER['PHP_AUTH_PW']) != '') {

               // login authorization code
               if (\Request::path() == $this->httpAuthLogin) {                
                   // validate user is authorized or not.
                   return $this->doLogin($request,false);

               } elseif (\Request::path() == $this->httpAuthLogout) {

                   // logout user & clear authorization cache.
                   return $this->doLogout();
               } else {
                   // if normal request validate user is authorized or not
                   if ($this->doLogin($request,true) === false) {
                       return $this->apiResponse(false, 401, $this->lang('auth.failed_login'));
                   }
                   else{
                   }
               }
           } else {
               return $this->apiResponse(false, 401, $this->lang('auth.auth_required'));
           }
        } else {
           return $this->apiResponse(false, 401, $this->lang('auth.auth_required'));
        }
        return $next($request);
        } catch (\Exception $exception) {
            return $this->apiResponse(false, 500, $this->lang('messages.server_error'));
        }
    }

    /**
     * Method is used for login authorization.
     *
     * @param bool $request
     *
     * @return Json|Response
     */


    protected function doLogin(Request $_request,$request = false)
    {
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        try {
            $credentials = [
                'email' => $username,
                'password' => $password,
                'status' => 1
            ];

            if (\Auth::once(['email' => $username, 'password' => $password]) ||
                \Auth::once($credentials)
                ) {
                // Normal User Login
                if (\Auth::once(['email' => $username, 'status' => 1 , 'password' => $password]) ||
                \Auth::once($credentials)
                ){
                    if (isset($_request['access_id'])) {
                           $access_id = $_request['access_id'];
                           $pro = (new GuestCart)->getcartproducts($_request['access_id']);
                            foreach ($pro as $product) {
                                $check = (new Cart)->checkExist($product->product_id, \Auth::user()->id);
                                if (count($check) != 0) {
                                    $cart = (new Cart)->updateCart($product->product_id, $product->quantity, \Auth::user()->id);
                                }else{
                                    unset($product->user_id);
                                    $product->user_id = \Auth::user()->id;
                                    $cart = (new Cart)->store($product->toArray());
                                }
                                $in['user_id'] =  $access_id;

                                $in['product_id'] =  $product->product_id;

                                $empty_guest_cart = (new GuestCart)->removeCart($in);
                            }
                    }
                    $user = $this->updateLastLogin();
                }else if (\Auth::once(['email' => $username, 'status' => 1 , 'password' => $username]) ||
                \Auth::once($credentials)
                ){
                    if (isset($_request['access_id'])) {
                           $access_id = $_request['access_id'];
                           $pro = (new GuestCart)->getcartproducts($_request['access_id']);
                            foreach ($pro as $product) {
                                $check = (new Cart)->checkExist($product->product_id, \Auth::user()->id);
                                if (count($check) != 0) {
                                    $cart = (new Cart)->updateCart($product->product_id, $product->quantity, \Auth::user()->id);
                                }else{
                                    unset($product->user_id);
                                    $product->user_id = \Auth::user()->id;
                                    $cart = (new Cart)->store($product->toArray());
                                }
                                $in['user_id'] =  $access_id;

                                $in['product_id'] =  $product->product_id;

                                $empty_guest_cart = (new GuestCart)->removeCart($in);
                            }
                    }
                    $user = $this->updateLastLogin();
                }else{

                    return $this->apiResponse(false, 401, $this->lang('Email is not verified, please verify your email address'));
                }
            }else if(\Auth::once(['email' => $username, 'status' => 1 , 'provider' => 'facebook', 'password' => $username])
                ){
                    $user = $this->updateLastLogin();
            }else if(\Auth::once(['email' => $username, 'status' => 1 , 'provider' => 'google', 'password' => $username])
                ){
                    $user = $this->updateLastLogin();
            } else {
                //$this->loginAttemptsFailed($username);
                if ($request == true) {
                    return false;
                } else {
                    return $this->apiResponse(false, 401, $this->lang('Invalid login credentials'));
                }
            }

            if (\Request::path() == $this->httpAuthLogin) {

                return $this->apiResponse(true, 200, '', [], $user);
            }

        } catch (\Exception $e) {
            return $this->apiResponse(false, 500, $this->lang('messages.server_error').$e->getMessage());
        }
    }

  

    /**
     * Method is used for logout and clear authorization cache.
     *
     * @return  Response|Json
     */
    protected function doLogout()
    {
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            try {
                // unset the http auth values.
                $_SERVER['PHP_AUTH_USER'] = $_SERVER['PHP_AUTH_PW'] = '';
                unset($_SERVER['PHP_AUTH_USER']);
                unset($_SERVER['PHP_AUTH_PW']);
                return $this->apiResponse(true, 200, $this->lang('auth.logout'));

            } catch (\Exception $e) {
                return $this->apiResponse(false, 500, $this->lang('messages.server_error'));
            }
        }
    }

    /**
     * Method is used for update last login time.
     *
     * @return  Response
     */
    protected function updateLastLogin()
    {
        //(new User)->updateLastLogin();
        return \Auth::user();
        $id = \Auth::user()->id;
        $email = \Auth::user()->email;
        
        return [
            'id'        => $id,
            'email'     => $email
         ];
    }

    /**
     * Method is used for update last login time.
     *
     * @param string $username
     *
     * @return Response
     */
    protected function loginAttemptsFailed($username)
    {
        if($username != "") {
            (new User)->updateFailedAttempts($username);
        }
    }

    //Serach Key
    protected function multiKeyExists(array $arr, $key) {
        
        // is in base array?
        if (array_key_exists($key, $arr)) {
            return true;
        }

        // check arrays contained in this array
        foreach ($arr as $element) {
            if (is_array($element)) {
                if ($this->multiKeyExists($element, $key)) {
                    return true;
                }
            }
            
        }

        return false;
    }

    function apiResponse($status, $statusCode, $message, $errors = [], $data = [])
    {
        $response = ['success' => $status, 'status' => $statusCode];
        
        if ($message != "") {
            $response['message']['success'] = $message;
        }

        if (!empty($errors)) {
            $response['message']['errors'] = $errors;
        }

        if (!empty($data)) {
            $response['message']['data'] = $data;
        }
        return response()->json($response);
    }

    function errorMessages($errors = [])
    {
        $error = [];
        foreach($errors->toArray() as $key => $value) {
            foreach($value as $messages) {
                $error[$key] = $messages;
            }
        }
        return $error;
    }

    function lang($path = null, $string = null)
    {
        $lang = $path;
        if (trim($path) != '' && trim($string) == '') {
            $lang = \Lang::get($path);
        } elseif (trim($path) != '' && trim($string) != '') {
            $lang = \Lang::get($path, ['attribute' => $string]);
        }
        return $lang;
    }
}
