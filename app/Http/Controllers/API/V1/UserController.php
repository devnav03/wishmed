<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\UserDevice;
use App\Models\Notification;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use App\Models\Notify;
use App\Models\SmsCode;
use App\Models\GuestUser;
use App\Models\GuestCart;
use App\Models\Contact;
use App\Models\ForceUpdate;
use App\Models\ZoneState;
use App\Models\Pincode;
use App\Models\Cart;
use App\Models\AboutContent;
use App\Models\PreRegister;
use App\Models\Category;
use Auth;
use Ixudra\Curl\Facades\Curl;
use PDF;
use App\PasswordHash;

class UserController extends Controller
{

    public function about(){
      try{
        $data['content'] = AboutContent::where('id', 1)->first();
        return apiResponseApp(true, 200, null, null, $data);
      } catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    public function lab_supply_cat(){
        $cats = Category::where('parent_id', 6)->select('name', 'id', 'image')->get();
        $url = route('home'); 
        $data = [];
        if($cats){
            foreach ($cats as $key => $slider) {
                $slide['category_id'] = $slider->id;
                $slide['name'] = $slider->name;
                $slide['image'] = $url.$slider->image;
                $data[] = $slide; 
            }
        }
        return apiResponseApp(true, 200, null, null, $data);
    }
    
    public function dental_supply_cat(){
        $cats = Category::where('parent_id', 19)->select('name', 'id', 'image')->get();
        $url = route('home'); 
        $data = [];
        if($cats){
            foreach ($cats as $key => $slider) {
                $slide['category_id'] = $slider->id;
                $slide['name'] = $slider->name;
                $slide['image'] = $url.$slider->image;
                $data[] = $slide; 
            }
        }
        return apiResponseApp(true, 200, null, null, $data);
    }
    
    
    
    public function lab_equipment_cat(){
        $cats = Category::where('parent_id', 4)->select('name', 'id', 'image')->get();
        $url = route('home'); 
        $data = [];
        if($cats){
            foreach ($cats as $key => $slider) {
                $slide['category_id'] = $slider->id;
                $slide['name'] = $slider->name;
                $slide['image'] = $url.$slider->image;
                $data[] = $slide; 
            }
        }
        return apiResponseApp(true, 200, null, null, $data);
    }
    
    public function medical_supply_cat(){
        $cats = Category::where('parent_id', 7)->select('name', 'id', 'image')->get();
        $url = route('home'); 
        $data = [];
        if($cats){
            foreach ($cats as $key => $slider) {
                $slide['category_id'] = $slider->id;
                $slide['name'] = $slider->name;
                $slide['image'] = $url.$slider->image;
                $data[] = $slide; 
            }
        }
        return apiResponseApp(true, 200, null, null, $data);
    }
    
    
    
    public function support(){

      try{
        $data['support'] = ForceUpdate::where('id', 1)->select('mobile', 'email')->first();
        return apiResponseApp(true, 200, null, null, $data);
      } catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
        }
    }
    
    public function force_update(){

      try{
        $data['support'] = ForceUpdate::where('id', 1)->select('force_update', 'version')->first();
        return apiResponseApp(true, 200, null, null, $data);
      } catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
        }
    }
    
    public function user_device(Request $request){
      try{
          $check = UserDevice::where('device_token', $request->device_token)->first();
          if($request->api_key){
              $user = User::where('api_key', $request->api_key)->select('id')->first();
              $user_id = $user->id;
          } else {
            $user_id = "";
          }

          if($check){
           if($user_id){
            UserDevice::where('id', $check->id)
            ->update([
              'user_id' => $user_id,
            ]);
           }
          } else{
            if($user_id){
              UserDevice::create([
              'device_token' => $request->device_token,
              'user_id' => $user_id,
              ]);
            } else {
              UserDevice::create([
              'device_token' => $request->device_token,
              ]);
            }
          }

        $message = "Device Token Successfully Saved";
        return apiResponseAppmsg(true, 200, $message, null, null);

      } catch(Exception $e){
          return apiResponse(false, 500, lang('messages.server_error'));
      }
    }

    public function contact_save(Request $request){
      try{

          $inputs = $request->all();
          (new Contact)->store($inputs);
          $email = $inputs['email'];
          $data['mail_data'] = $inputs;
          $message = "Your enquiry has been received and we will be contacting you shortly to follow-up.";
          return apiResponseAppmsg(false, 200, $message, null, null);

      } catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
      }
    }

    public function changePassword(Request $request){
      try {
           
          if($request->api_key){
            $inputs = $request->all();
            $user = User::where('api_key', $request->api_key)->select('id', 'password')->first();
            $password = \Hash::make($inputs['password']);  
            $old_password = \Hash::make($inputs['old_password']);

            if (!\Hash::check($request->old_password, $user->password)){

            $message = "Old password not match";
            return apiResponseAppmsg(false, 200, $message, null, null);


            } else {
              $id = $user->id;
              unset($inputs['password']);
              $inputs = $inputs + ['password' => $password];
              (new User)->store($inputs, $id);

              $message = "Password successfully Changed";
              return apiResponseAppmsg(true, 200, $message, null, null);
           }  

          }

      } catch(Exception $e){
          return apiResponse(false, 500, lang('messages.server_error'));
      }
    }
    

    public function forgot_password_otp(Request $request){
      try {
          if($request->otp){
              $user = User::where('login_otp', $request->otp)->select('id')->first();
              if($user){
                $password = \Hash::make($request->password);
                User::where('id', $user->id)->update(['password' => $password, 'login_otp' => NULL]);
                $message = "Password Successfully Changed";
                return apiResponseAppmsg(true, 200, $message, null, null);

              } else {
                $message = "OTP not valid";
                return apiResponseAppmsg(false, 200, $message, null, null);
              }

          }

      } catch(Exception $e){
          return apiResponse(false, 500, lang('messages.server_error'));
      }
    }
    

    public function forgot_password(Request $request){
      try{
          $user = User::where('email', $request->email)->where('status', 1)->select('name', 'id')->first();
          if($user) {
            $email = $request->email;
            $data['name'] = $user->name;
            $otp = rand(100000, 999999);
            $data['otp'] = $otp; 
            User::where('id', $user->id)
            ->update([
                'login_otp' =>  $otp,
            ]);
            \Mail::send('email.forgot_password_app', $data, function($message) use($email){
              $message->from('no-reply@wishmed.com');
              $message->to($email);
              $message->subject('Wishmed - Forgot Password');
            });
            $message = "We sent the OTP to your registerd email id. Kindly check your email.";
            
            return apiResponseAppmsg(true, 200, $message, null, $otp);
          } else {
            $message = "Hey, looks like we don't have your email in our database";
            return apiResponseAppmsg(false, 200, $message, null, null);
          }
      } catch(Exception $e){
          return apiResponse(false, 500, lang('messages.server_error'));
      }
    }



    public function signup_user(Request $request){
        try {

          $inputs = $request->all();

          $name = $request->first_name .' '. $request->last_name;
          $inputs['name'] = $name;

          $check_val =  User::where('email', $request->email)->first();
          if($check_val){

          $check_val1 =  User::where('mobile', $request->mobile)->first();
          if($check_val1){
            $data['message'] = "The email and mobile number has already registered";
            return apiResponseApp(false, 200, null, null, $data); 
          } else {
            $data['message'] = "The email has already registered";
            return apiResponseApp(false, 200, null, null, $data); 
          }
            
          }
          $check_val1 =  User::where('mobile', $request->mobile)->first();
          if($check_val1){

            $data['message'] = "The mobile number has already registered";
            return apiResponseApp(false, 200, null, null, $data); 
          }

          // $api_key = $this->generateApiKey();
          
          $password = \Hash::make($inputs['password']);
           unset($inputs['password']);

          $inputs['password'] = $password;
          $inputs['user_type'] = 2;
          $inputs['status'] = 1;

          $user_id = (new User)->store($inputs);
          $user_data = User::where('id', $user_id)->first();

            $mobile = $inputs['mobile'];
            // $message = 'Dear '.$inputs['name'].', Welcome to Puka Creation. We wish to inform that your account has successfully been activated. Regards, Team Puka Creation';
            // sendSms($mobile, $message); 

            // User::where('id', $user_id)
            // ->update([
            //   'api_key'  => $api_key,
            // ]);

            // $data['user'] = User::where('id', $user_id)->select('first_name', 'last_name', 'email', 'mobile')->first();


            // $GuestUser = GuestUser::where('access_id', $request->access_id)->select('id')->first();
            //     if($GuestUser){
            //       $GuestCarts = GuestCart::where('user_id', $GuestUser->id)->select('id', 'product_id', 'quantity')->get();
            //       if($GuestCarts){
            //         foreach ($GuestCarts as $GuestCart) {
            //           $check_cart = Cart::where('product_id', $GuestCart->product_id)->where('user_id', $user_id)->select('id', 'quantity')->first();
            //           if($check_cart){
            //               $qty = $check_cart->quantity+$GuestCart->quantity;
            //               Cart::where('id', $check_cart->id)
            //                 ->update([
            //                   'quantity'  => $qty,
            //               ]);
                            
            //               \DB::table('guest_carts')->where('id', $GuestCart->id)->delete();
            //           } else{

            //             $cart = new Cart();
            //             $cart->user_id = $user_id;
            //             $cart->product_id = $GuestCart->product_id;
            //             $cart->quantity = $GuestCart->quantity;
            //             $cart->save();
            //             \DB::table('guest_carts')->where('id', $GuestCart->id)->delete();
            //           }
            //         }
            //       }
            //     }
              
             // $data['id'] = $user_data;
            //  $data['name'] = $user_data->name;    
              //$data['email']  = $user_data->email;
            //  $data['mobile']  = $user_data->mobile; 
            //  $email = $user_data->email;
             // \Mail::send('email.user_verify', $data, function($message) use ($email){
              //  $message->from('no-reply@pukacreations.com');
              //  $message->to($email);
              //  $message->subject('Register');
             // }); 

              $message = "Your Account has been created with Wishmed. We have sent a confirmation link on your registered email Kindly check & Confirm.";
              return apiResponseAppmsg(true, 200, $message, null, null);

              // return apiResponseApp(true, 200, null, null, $data);



        } catch(Exception $e){

         // dd($e);

        return apiResponse(false, 500, lang('messages.server_error'));

        }


    }



    public function login_with_google(Request $request){

        try{
 
          $user_data = User::where('email', $request->email)->first();
          if($user_data){

             if($user_data->user_type == 4){

                return apiResponse(false, 200, 'You are not allow on APP');

               } else {

                $api_key = $this->generateApiKey();
                if($user_data->api_key){

                } else {
                  User::where('email', $request->email)
                  ->update([
                    'api_key' =>  $api_key,
                  ]);
                } 

                $user_data1 = User::where('email', $request->email)->first();

                $data['name'] = $user_data1->name;
                $data['email'] = $user_data1->email;
                $data['image'] = $user_data1->profile_image;
                $data['mobile'] = $user_data1->mobile;  
                if($user_data->api_key){
                  $data['api_key'] = $user_data->api_key;
                } else {
                  $data['api_key'] = $user_data1->api_key;
                }

                $GuestUser = GuestUser::where('access_id', $request->access_id)->select('id')->first();
                if($GuestUser){
                  $GuestCarts = GuestCart::where('user_id', $GuestUser->id)->select('id', 'product_id', 'quantity')->get();
                  if($GuestCarts){
                    foreach ($GuestCarts as $GuestCart) {
                      $check_cart = Cart::where('product_id', $GuestCart->product_id)->where('user_id', $user_data->id)->select('id', 'quantity')->first();
                      if($check_cart){
                          $qty = $check_cart->quantity+$GuestCart->quantity;
                          Cart::where('id', $check_cart->id)
                            ->update([
                              'quantity'  => $qty,
                          ]);
                          \DB::table('guest_carts')->where('id', $GuestCart->id)->delete();
                      } else{

                        $cart = new Cart();
                        $cart->user_id = $user_data->id;
                        $cart->product_id = $GuestCart->product_id;
                        $cart->quantity = $GuestCart->quantity;
                        $cart->save();
                        \DB::table('guest_carts')->where('id', $GuestCart->id)->delete();
                      }
                    }
                  }
                }

                return apiResponseApp(true, 200, null, null, $data);
              
              }

          } else {

              $data['email'] = $request->email;
              $data['name'] = $request->name;

              return apiResponseApp(false, 200, null, null, $data);
          }


        }catch(Exception $e){
        
          return apiResponse(false, 500, lang('messages.server_error'));
        }


    }

    public function login_user(Request $request){

        try{

        $credentials = [
            'email' => $request->get('username'),
            'password' => $request->get('password'),
            'status' => 1
        ];

        $credentials1 = [
            'mobile' => $request->get('username'),
            'password' => $request->get('password'),
            'status' => 1
        ];
         
          $url = route('home');
          $inputs = $request->all();
            // $validator = (new User)->validateLoginUser($inputs);
            // if( $validator->fails() ) {
            //   $data['messages'] = "Enter required field";
            //     return apiResponseApp(false, 200, null, null, $data);
            // }

         $user = User::where('email', $request->username)->where('status', 1)->select('password')->first(); 
         if($user){
          $wp_hasher = new PasswordHash(8, TRUE);
          $plain_password = $request->password; 
          $password_hashed  =  $user->password;

          if($wp_hasher->CheckPassword($plain_password, $password_hashed)) {
            $user = User::where('email', $request->username)->where('status', 1)->first(); 
          } else {
            $user = ''; 
          }
         }
            if (!empty($user))  {
                
                $user_data = User::where('email', $request->username)->first();

                $api_key = $this->generateApiKey();
                if($user_data->api_key){
                $api_key = $user_data->api_key;
                } else {
                User::where('email', $request->username)
                ->update([
                'api_key' =>  $api_key,
                 ]);
                }
                
                $data['name'] = $user_data->name;
                $data['email'] = $user_data->email;
                if($user_data->profile_image){
                  $data['image'] = $url.$user_data->profile_image;
                } else {
                  $data['image'] =$user_data->profile_image;
                }
                $data['mobile'] = $user_data->mobile;  
                $data['gender'] = $user_data->gender;  
                $data['api_key'] = $api_key;  


                // $GuestUser = GuestUser::where('access_id', $request->access_id)->select('id')->first();
                // if($GuestUser){
                //   $GuestCarts = GuestCart::where('user_id', $GuestUser->id)->select('id', 'product_id', 'quantity')->get();
                //   if($GuestCarts){
                //     foreach ($GuestCarts as $GuestCart) {
                //       $check_cart = Cart::where('product_id', $GuestCart->product_id)->where('user_id', $user_data->id)->select('id', 'quantity')->first();
                //       if($check_cart){
                //           $qty = $check_cart->quantity+$GuestCart->quantity;
                //           Cart::where('id', $check_cart->id)
                //             ->update([
                //               'quantity'  => $qty,
                //           ]);
                //           \DB::table('guest_carts')->where('id', $GuestCart->id)->delete();
                //       } else{

                //         $cart = new Cart();
                //         $cart->user_id = $user_data->id;
                //         $cart->product_id = $GuestCart->product_id;
                //         $cart->quantity = $GuestCart->quantity;
                //         $cart->save();
                //         \DB::table('guest_carts')->where('id', $GuestCart->id)->delete();
                //       }
                //     }
                //   }
                // }

                //return apiResponseApp(true, 200, null, null, $data);
                $message = "Login Successful";
                return apiResponseAppmsg(true, 200, $message, null, $data);

          } else if (Auth::attempt($credentials))  {

                $user_data = User::where('email', $request->username)->first();

                $api_key = $this->generateApiKey();
                if($user_data->api_key){
                  $api_key = $user_data->api_key;
                } else {
                User::where('email', $request->username)
                ->update([
                'api_key' =>  $api_key,
                 ]);
                }
                

              if($user_data->user_type == 4){

                return apiResponse(false, 200, 'You are not allow on APP');

               } else {

                $data['name'] = $user_data->name;
                $data['email'] = $user_data->email;
                if($user_data->profile_image){
                  $data['image'] = $url.$user_data->profile_image;
                } else {
                  $data['image'] =$user_data->profile_image;
                }
                $data['mobile'] = $user_data->mobile;  
                $data['gender'] = $user_data->gender;  
                $data['api_key'] = $api_key;  


                // $GuestUser = GuestUser::where('access_id', $request->access_id)->select('id')->first();
                // if($GuestUser){
                //   $GuestCarts = GuestCart::where('user_id', $GuestUser->id)->select('id', 'product_id', 'quantity')->get();
                //   if($GuestCarts){
                //     foreach ($GuestCarts as $GuestCart) {
                //       $check_cart = Cart::where('product_id', $GuestCart->product_id)->where('user_id', $user_data->id)->select('id', 'quantity')->first();
                //       if($check_cart){
                //           $qty = $check_cart->quantity+$GuestCart->quantity;
                //           Cart::where('id', $check_cart->id)
                //             ->update([
                //               'quantity'  => $qty,
                //           ]);
                //           \DB::table('guest_carts')->where('id', $GuestCart->id)->delete();
                //       } else{

                //         $cart = new Cart();
                //         $cart->user_id = $user_data->id;
                //         $cart->product_id = $GuestCart->product_id;
                //         $cart->quantity = $GuestCart->quantity;
                //         $cart->save();
                //         \DB::table('guest_carts')->where('id', $GuestCart->id)->delete();
                //       }
                //     }
                //   }
                // }

                //return apiResponseApp(true, 200, null, null, $data);
                
                $message = "Login Successful";
                return apiResponseAppmsg(true, 200, $message, null, $data);
              
              }

          } else if(Auth::attempt($credentials1)) {
                $user_data = User::where('mobile', $request->username)->first();
                $api_key = $this->generateApiKey();
                if($user_data->api_key){
                $api_key = $user_data->api_key;
                } else {
                User::where('email', $request->username)
                ->update([
                'api_key' =>  $api_key,
                 ]);
                }
               

                if($user_data->user_type == 4){

                  return apiResponse(false, 200, 'You are not allow on APP');
                } else {

                $data['name'] = $user_data->name;
                $data['email'] = $user_data->email;
                if($user_data->profile_image){
                  $data['image'] = $url.$user_data->profile_image;
                } else {
                  $data['image'] =$user_data->profile_image;
                }
                $data['mobile'] = $user_data->mobile; 
                $data['gender'] = $user_data->gender;  
                $data['api_key'] = $api_key; 
                
                // $GuestUser = GuestUser::where('access_id', $request->access_id)->select('id')->first();
                // if($GuestUser){
                //   $GuestCarts = GuestCart::where('user_id', $GuestUser->id)->select('id', 'product_id', 'quantity')->get();
                  // if($GuestCarts){
                  //   foreach ($GuestCarts as $GuestCart) {
                  //     $check_cart = Cart::where('product_id', $GuestCart->product_id)->where('user_id', $user_data->id)->select('id', 'quantity')->first();
                  //     if($check_cart){
                  //         $qty = $check_cart->quantity+$GuestCart->quantity;
                  //         Cart::where('id', $check_cart->id)
                  //           ->update([
                  //             'quantity'  => $qty,
                  //         ]);
                  //         \DB::table('guest_carts')->where('id', $GuestCart->id)->delete();
                  //     } else{

                  //       $cart = new Cart();
                  //       $cart->user_id = $user_data->id;
                  //       $cart->product_id = $GuestCart->product_id;
                  //       $cart->quantity = $GuestCart->quantity;
                  //       $cart->save();
                  //       \DB::table('guest_carts')->where('id', $GuestCart->id)->delete();
                  //     }
                  //   }
                  // }
                // }

                //return apiResponseApp(true, 200, null, null, $data);
                
                $message = "Login Successful";
                return apiResponseAppmsg(true, 200, $message, null, $data);

                }

        } else {
         //  dd($request);
          
          //return apiResponse(false, 200, 'Invalid login credentials');
          
         $message = "Invalid login credentials";
        return apiResponseAppmsg(false, 201, $message, null, null);

        }
             
              
    } catch(Exception $e){
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }


     /*create app key*/
    private function generateApiKey() {
        return md5(uniqid(rand(), true));
    }

    public function emailVerifyApp($user_id)
    {
        try{
            if($user_id){
                $de_crypt_user_id = Hashid::decode($user_id);
                $data = [];
                $user = User::where('id', $de_crypt_user_id)->first();
                User::where('id',  $de_crypt_user_id)->update(['status' => 1]);
                return redirect()->route('home');
            }
        }
        catch(\Exception $e){
            return back();
        }
    }

   

    public function logout(Request $request){
       try{
        if($request->api_key){
           $user = User::where('api_key', $request->api_key)->select('id')->first();
          if($user) {
           User::where('id', $user->id)
            ->update([
              'api_key'  => null
            ]);
        
          $message = "Logout successfully";
          return apiResponseAppmsg(true, 200, $message, null, null);
          }
        }

       } catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

      //Update Profile
    public function updateProfile(Request $request){
      try{   

          if($request->api_key){
            $user = User::where('api_key', $request->api_key)->select('id', 'profile_image')->first();
            if($user) {  

            $inputs = $request->all();
            
            $name = $request->first_name .' '. $request->last_name;
            $inputs['name'] = $name;

            if(isset($inputs['profile_image']) or !empty($inputs['profile_image']))
            {

                $image_name = rand(100000, 999999);
                $fileName = '';

                if($file = $request->hasFile('profile_image')) 
                {
                    $file = $request->file('profile_image') ;
                    $img_name = $file->getClientOriginalName();
                    $fileName = $image_name.$img_name;
                    $destinationPath = public_path().'/uploads/user_images/';
                    $file->move($destinationPath, $fileName);
                }
                $fname ='/uploads/user_images/';
                $profile_images = $fname.$fileName;
       

            } else {
                $profile_images = $user->profile_image;
            }

           unset($inputs['profile_image']);
            $inputs = $inputs + [    
                                  'updated_by' => $user->id,
                                  'profile_image' => $profile_images,];

            (new User)->store($inputs, $user->id);
            $url = route('home'); 
            
            $u_data = User::where('id', $user->id)->select('id', 'name', 'first_name', 'last_name', 'email', 'address', 'pincode', 'state', 'city', 'gender', 'mobile', 'profile_image', 'date_of_birth')->first();

            $data['id'] = $u_data->id;
            $data['first_name'] = $u_data->first_name;
            $data['last_name'] = $u_data->last_name;
            $data['address'] = $u_data->address;
            $data['pincode'] = $u_data->pincode;
            $data['state'] = $u_data->state;
            $data['city'] = $u_data->city;
            $data['email'] = $u_data->email;
            $data['mobile'] = $u_data->mobile;
             
            if($u_data->profile_image){
                  $data['profile_image'] = $url.$u_data->profile_image;
                } else {
                  $data['profile_image'] =$u_data->profile_image;
            }
            $data['gender'] = $u_data->gender;

            return apiResponseApp(true, 200, null, null, $data);

            //return apiResponse(true, 200, lang('User added successfully'));
           }
          }
        } catch(Exception $e){
        
         // dd($e);
          // return apiResponse(false, 500, lang('messages.server_error'));
           return apiResponseApp(true, 200, null, null, $e);
        }
    }

    public function addDeviceToken(Request $request){
        try{    
            $inputs = $request->all();

            $token_exist = UserDevice::where('device_token', $inputs['device_token'])->first();
            if (isset($token_exist)) {
                (new UserDevice)->store($inputs, $token_exist['id']);
            } else{
                (new UserDevice)->store($inputs);
            }
            return apiResponse(true, 200, lang('User added successfully'));

        }catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
        }
    }


    public function facebookLogin(Request $request)
    {
        try{
            $inputs = $request->all();
            $validator = ( new User )->validatefb( $inputs );
            if( $validator->fails() ) {
                return apiResponse(false, 406, "", errorMessages($validator->messages()));
            } 

            $check_email = (new User)->where('email', $inputs['email'])
                                    ->where('access_token', null)
                                    ->get();

            if (count($check_email) >=1) {
                return apiResponse(false, 500, lang('Email Address already exist in our records'));
            }

            $api_key = $this->generateApiKey();

            $password = \Hash::make($inputs['email']);

            $inputs = $inputs + [
                                    'role' => 2,
                                    'api_key'   => $api_key,
                                    'provider'   => 'facebook',
                                    'user_type'   => '3',
                                    'status'    => 1,
                                    'password' => $password
                                ];
            
            $check = (new User)->where('email', $inputs['email'])->first();
            if (count($check) == 0) {
                    $user = (new User)->store($inputs);
                    return apiResponse(true, 200, lang('User Successfully created'));
            }else{
                if ($check->access_token == null) {
                    $user = (new User)->store($inputs);
                    return apiResponse(true, 200, lang('User Successfully created'));
                }else{
                    $user = (new User)->updatestorfb($check->id, $inputs['access_token']);
                    return apiResponse(true, 200, lang('User Successfully created'));
                }
            
            }


        }
        catch(Exception $exception){
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }


    public function googleLogin(Request $request)
    {
        try{
            $inputs = $request->all();
            $validator = ( new User )->validatefb( $inputs );
            if( $validator->fails() ) {
                return apiResponse(false, 406, "", errorMessages($validator->messages()));
            } 

            $check_email = (new User)->where('email', $inputs['email'])
                                    ->where('access_token', null)
                                    ->get();

            if (count($check_email) >=1) {
                return apiResponse(false, 500, lang('Email Address already exist in our records'));
            }

            $api_key = $this->generateApiKey();

            $password = \Hash::make($inputs['email']);

            $inputs = $inputs + [
                                    'role' => 2,
                                    'api_key'   => $api_key,
                                    'provider'   => 'google',
                                    'user_type'   => '3',
                                    'status'    => 1,
                                    'password' => $password
                                ];
            
            $check = (new User)->where('email', $inputs['email'])->first();
            if (count($check) == 0) {
                    $user = (new User)->store($inputs);
                    return apiResponse(true, 200, lang('User Successfully created'));
            }else{
                if ($check->access_token == null) {
                    $user = (new User)->store($inputs);
                    return apiResponse(true, 200, lang('User Successfully created'));
                }else{
                    $user = (new User)->updatestorfb($check->id, $inputs['access_token']);
                    return apiResponse(true, 200, lang('User Successfully created'));
                }
            
            }


        }
        catch(Exception $exception){
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }



    public function profile(Request $request){
        try{
          if($request->api_key){
           $user = User::where('api_key', $request->api_key)->select('id', 'name', 'first_name', 'address', 'state', 'city', 'pincode', 'last_name', 'email', 'mobile', 'profile_image', 'gender')->first();
            $url = route('home'); 

            if($user){
            $data['id'] = $user->id;
            $data['first_name'] = $user->name;
            $data['last_name'] = $user->name;
            $data['email'] = $user->email;
            $data['mobile'] = $user->mobile;
            $data['address'] = $user->address;
            $data['pincode'] = $user->pincode;
            $data['state'] = $user->state;
            $data['city'] = $user->city;
            if($user->profile_image){
                  $data['profile_image'] = $url.$user->profile_image;
                } else {
                  $data['profile_image'] = $user->profile_image;
            }
            $data['gender'] = $user->gender;
            return apiResponseApp(true, 200, null, null, $data); 
          }
          }

        } catch(Exception $e){
           // dd($e);
           return apiResponse(false, 500, lang('messages.server_error'));
        }

    }





   

    

}
