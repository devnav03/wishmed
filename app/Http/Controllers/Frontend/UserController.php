<?php

/**
 * @Author Manjit Mattu
 * @Created_at 20-07-2018
 */
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
/*use App\NotificationLog;*/
use Mail;
use App\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use League\Flysystem\Exception;
//use Illuminate\Support\Facades\Mail;
use Ixudra\Curl\Facades\Curl;

class UserController extends Controller
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
   public function listUser(Request $request){
       try {
            $inputs = $request->all();
            $result = [];
            $users = (new User)->getUsers($inputs);
            if(count($users) == 0) {
                return apiResponse(false, 404, lang('messages.not_found', lang('user.user')));
            }

            foreach ($users as $user) {
                $result[] = [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'address' => $user->address,
                    'mobile' => $user->mobile,
                    'phone' => $user->phone,
                    'is_approved' => ($user->status == 0)?'Not approved':'Approved'
                ];
            }
           return apiResponse(true, 200 , null, [], $result);
       }
       catch (Exception $exception) {
           \DB::rollBack();
           return apiResponse(false, 500, lang('messages.server_error'));
       }
   }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( Request $request ){
        
        try{

            $inputs = $request->all(); 

            $validator = (new User)->validate_front($inputs);
            if( $validator->fails() ) {
               // dd($validator);
                return back()->withErrors($validator)->withInput() ->with('erroor_reg', lang('messages.created', lang('blogs.blogs')));;
            }

        
            $password = \Hash::make($inputs['password']);
            unset($inputs['password']);

            $inputs = $inputs + ['password' => $password];
            /* setting up the default user type to 2 [ marketer ] */

            // Generating API key
            $remember_token = $this->generateTokenKey();
            $inputs = $inputs + [
                                    'user_type' => 3,
                                    'remember_token'   => $remember_token,
                                    'provider' => 'normal',
                                    'status' => 1,
                                ];

            $id = (new User)->store($inputs);  
            $data['id'] = $id;
            $data['name'] = $inputs['name'];    
            $data['email']  = $inputs['email'];
            $email = $inputs['email'];
             

            \Mail::send('email.user_verify', $data, function($message) use ($email){
                $message->from('navjot@abc.com');
                $message->to($email);
                $message->subject('Register');
            }); 

            \Mail::send('email.user_reg', $data, function($message) use ($email){
                $message->from($email);
                $message->to('navjot@abc.com');
                $message->subject('New User Register');
            });
          
          $id_data = User::where('id', $id)->first();

            \Auth::login($id_data);
            return redirect()->back()->with('message_reg', 'Register Done!');
        

        }
        catch (Exception $exception) {

        //dd($exception);
            //\DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    /**
     * Send OTP
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */


    /**
     * Resend SMS Code
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function resendOtp(Request $request){  
        try{
            $inputs = $request->all();

            $alreadyActivated = ( new User)->alreadyActivateNo($inputs['mobile_no']);
            if(!empty($alreadyActivated)){
                $errorAlreadyActivated = '<li>This mobile no. already activatd.</li>';
                return ['error' => $errorAlreadyActivated];
            }

            $user = ( new User)->getUserByMobile($inputs['mobile_no']);
            if(empty($user)){
                $errorExisting = '<li>This mobile no. does not exist.</li>';
                return ['error' => $errorExisting];
            }
            
            $validator = ( new User )->validateMobile( $inputs );
            if( $validator->fails() ) {
                $error = [];
                $messages = $validator->messages();

                foreach ($messages->toArray() as $vky => $vkv) {
                    foreach ($vkv as $k => $v) {
                        $error[] = $v; 
                    }
                }

                $html = '<ul>';
                foreach ($error as $k => $v) {
                    $html .= '<li>'.$v.'
                        </li>';
                }
                $html .= '</ul>';
                
                return ['error' => $html];
            }

            $mobile = $inputs['mobile_no'];
            // SMS OTP
            $otp = rand(100000, 999999);
            $this->sendOTP($mobile, $otp); 
            return apiResponse(true, 200, lang('user.otp_sent'),[],['temp_id'=> $user->id]);           
            
        }catch(\Exception $exception){
            return apiResponse(false, 500, lang('messages.server_error').$exception->getMessage());
        }
               
    }


    /**
     * Activate Account
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function activateUserStatus(Request $request){

        $inputs = $request->all();   
        $user = []; 
        try{
            $validator = ( new User )->validateOtp( $inputs);
            if( $validator->fails() ) {
                $error = [];
                $messages = $validator->messages();

                foreach ($messages->toArray() as $vky => $vkv) {
                    foreach ($vkv as $k => $v) {
                        $error[] = $v; 
                    }
                }

                $html = '<ul>';
                foreach ($error as $k => $v) {
                    $html .= '<li>'.$v.'
                        </li>';
                }
                $html .= '</ul>';
                
                return ['error' => $html];
            }

            if(isset($inputs['otp_number']) && $inputs['otp_number'] != '' && $inputs['temp'] != ''){
                $user = (new User)->getUserByOtp($inputs['temp'], $inputs['otp_number']);
                if(empty($user)){
                    $errorWrongOtp = '<li>'.lang('user.otp_wrong').'</li>';
                    return ['error' => $errorWrongOtp];
                }

                if(!empty($user) && $user->user_id != '' && $user->sms_id != ''){
                    \DB::beginTransaction();
                    (new User)->activateAccount($user->user_id);
                    (new SmsCodes)->activateSmsCode($user->sms_id);

                    \DB::commit();
                    $user =User::where('id',$user->user_id)->first();
                    return apiResponse(true, 200, lang('user.account_activated',$user->email));
                }
            }   
        }catch (\Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        } 

    }    
    
    /**
     * Generating random Unique MD5 String for user Api key
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    private function generateTokenKey() {
        return md5(uniqid(rand(), true));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        try {
            $id=\Auth::user()->id;
            \DB::beginTransaction();
            /* FIND WHETHER THE USER EXISTS OR NOT */
            $user = User::find($id);
            if(!$user) {
                return apiResponse(false, 404, lang('messages.not_found', lang('user.user')));
            }

            if($user->id == authUserId() ||  isAdmin()) {

                $inputs = $request->all();

                if(array_key_exists('password', $inputs)) {
                    if($inputs['password'] == '') {
                        unset($inputs['password']);
                    }
                    else {
                        $password = \Hash::make($inputs['password']);
                        unset($inputs['password']);
                        $inputs = $inputs + ['password' => $password];
                    }
                }

                if(array_key_exists('mobile_no', $inputs)) {
                    $phone = ( empty( $inputs['mobile_no'] ) )?null:$inputs['mobile_no'];
                    $inputs = $inputs + ['mobile_no' => $phone];
                }
                if(\Auth::user()->lawyer_id==0)
                    $validator = ( new User )->validate( $inputs, $id );
                else
                    $validator = ( new User )->validateStaff( $inputs, $id );
               
                if( $validator->fails() ) {
                    return apiResponse(false, 406, "", errorMessages($validator->messages()));
                }

                (new User)->store($inputs, $id);
                \DB::commit();
                return apiResponse(true, 200, lang('messages.updated', lang('user.user')));
            }
            else {
                return apiResponse(false, 404, lang('auth.auth_required'));
            }
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function drop($id) {
        try {

            \DB::beginTransaction();
            /* FIND WHETHER THE USER EXISTS OR NOT */
            $user = User::find($id);
            if(!$user) {
                return apiResponse(false, 404, lang('messages.not_found', lang('user.user')));
            }
            if(isAdmin()) {
                if($id == 1) {
                    return apiResponse(false, 406, lang('user.admin_restrict'));
                }
                (new User)->drop($id);
                \DB::commit();
                return apiResponse(true, 200, lang('messages.deleted', lang('user.user')));
            }
            else {
                return apiResponse(false, 404, lang('auth.customer_not_accessible'));
            }
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function activateAccount($id) {
        try {

            \DB::beginTransaction();

            if($id == authUserId() || isAdmin()) {
                /* FIND WHETHER THE USER EXISTS OR NOT */
                $user = User::find($id);
                if(!$user) {
                    return apiResponse(false, 404, lang('messages.not_found', lang('user.user')));
                }
                if($user->status != 1) {
                    (new User)->activateAccount($id);

                    /*Mail::send(lang('email.email_template'), ['user' => $user], function ($m) use ($user) {
                        $m->from(lang('email.from'), lang('email.from_title'));
                        $m->to($user->email, $user->username)->subject(lang('email.subject'));
                    });*/

                    \DB::commit();
                    return apiResponse(true, 200, lang('user.account_activated'));
                }
                return apiResponse(false, 404, lang('auth.already_activated'));
            }
            else {
                return apiResponse(false, 404, lang('auth.customer_not_accessible'));
            }
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserDetail() {
        try {
            if(\Auth::check()) {

                $user =User::where('id',\Auth::user()->id)->first();
                if( $user){
                    
                    return apiResponse(true, 200 , null, [], $user);
                }
                return apiResponse(false, 404, lang('messages.not_found', lang('user.user')));
            }
            else {
                return apiResponse(false, 404, lang('auth.customer_not_accessible'));
            }
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    public function upload_licence(Request $request)
    {
        try {
            $inputs = $request->all();
            $rules = [
                        'image'     => 'required|mimes:jpg,jpeg,png|max:2048',
                        'fileName'  => 'required|max:50|regex:/^[a-zA-z\s]+$/'
                    ];
            $validator=\Validator::make($inputs, $rules);
             if ($validator->fails()) {
                return apiResponse(false, 406, "", errorMessages($validator->messages()));
            }
           
            $filename = $inputs['fileName']; // getting image extension
            $request->file('image')->move(public_path().'/uploads/licence/', $filename);
            
            \Auth::user()->update(['licence'=>$filename]);
            return apiResponse(true, 200, lang('user.licence_updated'));
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

        /**
     * Get Bareact chapters
     *
     * @return \Illuminate\Http\Response
     */
    public function menusList()
    {
        try {
            $menus = \App\Menu::get();

            if(count($menus)>0){
                return apiResponse(true, 200, '', [], $menus);
            }
       
            else{
                return apiResponse(false, 404, lang('menu.not_found'));
            }
        } catch (\Exception $exception) {
            return apiResponse(false, 500, lang('messages.server_error').$exception->getMessage());
        }
    }

        /** change password
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePwd(Request $request)
    {
        try {
            $id=\Auth::user()->id;
            \DB::beginTransaction();
            /* FIND WHETHER THE USER EXISTS OR NOT */
            $user = User::find($id);
            if(!$user) {
                return apiResponse(false, 404, lang('messages.not_found', lang('user.user')));
            }
            $inputs = $request->all();
            $rules = [
                    'password' => 'required',
                    'new_password'=>'required|min:6'
                    ];
            $validator=\Validator::make($inputs, $rules);
            if ($validator->fails()) {
                return apiResponse(false, 406, "", errorMessages($validator->messages()));
            }
      
                if (!\Hash::check($inputs['password'], \Auth::user()->password) ){
                    return apiResponse(false, 406,lang('user.password_not_match'));
                }

                $password = \Hash::make($inputs['new_password']);
                unset($inputs['password']);
                $inputs = $inputs + ['password' => $password];
                
                (new User)->store($inputs, $id);
                \DB::commit();
                return apiResponse(true, 200, lang('messages.updated', lang('user.user')));
           
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

        /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserMenu() {
        try {
            $menus=null;
            if(\Auth::user()->lawyer_id==0){
                $lawyer_package=\App\LawyerPackage::where('lawyer_id',\Auth::user()->id)->first();
                $package = \App\Package::where('id',$lawyer_package->package_id)->first();
                if( $package) {
                  $menus=json_decode($package->menu_id);
               }
            }
            else{
                $result = LawyerStaffPermission::where('staff_id',\Auth::user()->id)->first();
                if($result) {
                $menus=json_decode($result->menu);
                }
            }
               if($menus){
                  $menus_all=[];
                  foreach ($menus as $key=>$value) {
                    if(is_array($value))
                        $menus_all[]=\App\Menu::with(['childMenu' => function($query)use($value){
                            $query->whereIn('id', $value);
                        }])->where('id',$key)->first();
                    else
                        $menus_all[]=\App\Menu::with(['childMenu'=>function($query){
                            $query->where('id', 0);
                        }])->where('id',$key)->first();
                  }
                  return apiResponse(true, 200 , null, [], $menus_all);
                }
                return apiResponse(false, 404, lang('messages.not_found', lang('package.menu')));
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }


    /**
     * Get Notification settings details 
     *
     * @return \Illuminate\Http\Response
     */
    public function notificationSettings(Request $request)
    {    
        try {
            $inputs = $request->all();
            $settings = NotificationSetting::where('created_by',\Auth::user()->id)->first();
            
            if($settings){
                return apiResponse(true, 200, '', [], $settings);
            }
       
            else{
                return apiResponse(false, 404, lang('user.not_found_settings'));
            }
        } catch (\Exception $exception) {
            return apiResponse(false, 500, lang('messages.server_error').$exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeSettings(Request $request)
    {
        $inputs = $request->all();

        try {
            \DB::beginTransaction();

            $settings = NotificationSetting::firstOrNew(['created_by'=>\Auth::user()->id]);
            $settings->next_date=$inputs['next_date'];
            $settings->orders=$inputs['orders'];
            $settings->causelist=$inputs['causelist'];
            $settings->created_by=\Auth::user()->id;
            $settings->save();
            \DB::commit();

            return apiResponse(true, 200, lang('messages.created', lang('user.settings')));
        } catch (\Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }


    public function forgotPwdRequest( Request $request ){
        
        try{
            $inputs = $request->all(); 

            $mobile = $inputs['mobile_no'];

            $user = User::where('mobile_no',$mobile)->first();
            if($user){
            // SMS OTP
            $otp = rand(100000, 999999);
            // Store SMS Code
            (new SmsCodes)->store($user->id, $otp);

            
            //Send OTP
             if(is_numeric($mobile) && is_numeric($otp) && $mobile != '' && $otp != ''){
                $message = 'The otp for Reset Password on Legal Basta is '.$otp;
                $sender = 'VSBJAJ';
                $httpKey = '010lMuo40GFqg5aZnrl2axBrw8Qw0k';
                $profileID = '620985';
                $mobile_no = $mobile;
                $url = 'http://www.digikliksms.com/shn/api/pushsms.php?usr='.$profileID.'&key='.$httpKey.'&sndr='.$sender.'&ph='.$mobile_no.'&text='.$message.'&rpt=1&type=1';
                $url = str_replace(" ", '%20', $url);
                // Send a GET request to: http://www.digikliksms.com
                $response = Curl::to($url)->get();
            }
            /*return apiResponse(true, 200, lang('user.otp_sent'));
            return apiResponse(true, 200,null, lang('user.otp_no_sent'));*/

            /*Mail::send(lang('email.registered-notification'), ['user' => $user], function ($m) use ($user) {
                $m->from(lang('email.from'), lang('email.from_title'));
                $m->to($user->email, $user->username)->subject(lang('email.thankyou_email'));
            });    */  
            return apiResponse(true, 200, lang('user.otp_sent'));
            }
            else{
                return apiResponse(false, 404, lang('user.not_found_user'));
            }
        }
        catch (Exception $exception) {
            //\DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }


    public function resetPassword( Request $request ){
        
        try{
            $inputs = $request->all(); 
            $validator = ( new User )->validateOtp( $inputs);
            if( $validator->fails() ) {
                return apiResponse(false, 406, null, errorMessages($validator->messages()));
            }

            $mobile = $inputs['mobile_no'];

            $user = User::where('mobile_no',$mobile)->first();
            if($user){            
                if(isset($inputs['otp']) && $inputs['otp'] != ''){
                    $otp = SmsCodes::where(\DB::raw('TIMESTAMPDIFF(MINUTE,sms_codes.created_at,now())'),'<=',10)->where('sms_codes.code', $inputs['otp'])
                        ->where('sms_codes.status', 0)->where('sms_codes.user_id', $user->id)->first();
                    if(!$otp){
                       return apiResponse(false, 404, null, lang('messages.not_found', lang('user.user')));
                    }

                    // SMS OTP
                    $otp = rand(100000, 999999);
                    $user->password=\Hash::make($otp);
                    $user->save();
                    //Send OTP
                    $message = 'New Password for your account is '.$otp.' on Legal Basta.';
                    $mobile_no = $mobile;
                    $url = \Config::get('sms.url').'?usr='.\Config::get('sms.profileID').'&key='.\Config::get('sms.httpKey').'&sndr='.\Config::get('sms.sender').'&ph='.$mobile_no.'&text='.$message.'&rpt=1&type=1';
                    $url = str_replace(" ", '%20', $url);
                    // Send a GET request to: http://www.digikliksms.com
                    $response = Curl::to($url)->get();
                    return apiResponse(true, 200, lang('user.password_reset'));
                }
            }
            else{
                return apiResponse(false, 404, null, lang('messages.not_found', lang('user.user')));
            }
        }
        catch (Exception $exception) {
            //\DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    /**
     * Resend SMS Code
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function getProfile(){  
        try{
            $result = (new User)->getUserProfile();
            return view('frontend.pages.profile', compact('result'));
        }catch(\Exception $exception){
            return apiResponse(false, 500, lang('messages.server_error').$exception->getMessage());
        }
               
    }

    public function Welcome(){

        $user_id =  authUserIdNull();
        $user_data = User::where('id', $user_id)->first();
        
    return view('frontend.pages.welcome', compact('user_data'));
    }

    public function contactInformation(){

        $user_id =  authUserIdNull();
        $result = User::where('id', $user_id)->first();

    return view('frontend.pages.contact-information', compact('result'));
}

    public function exhibitorManuals(){

    $manuals = (new Manual)->getAllManual();
    return view('frontend.pages.exhibitor-manuals', compact('manuals'));
    }
    
    public function Forms(){

    return view('frontend.pages.forms');
    }

    public function badgeCreation(){

         $user_id =  authUserIdNull();
        $company = User::where('id', $user_id)->first();

    return view('frontend.pages.badge-creation',compact('company'));
    }


    public function badgeList(){
    
    $user_id =  authUserIdNull();

    $company = User::where('id', $user_id)->first();

    $badges  = Badge::where('created_by', $user_id)->where('status', 1)->get();

    return view('frontend.pages.badge-list', compact('badges','company'));
    }

    public function badgeEdit($id){


    $user_id =  authUserIdNull();
    $company = User::where('id', $user_id)->first();

     $result = (new Badge)->find($id);
        if (!$result) {
            abort(401);
        }
 


    return view('frontend.pages.badge-creation', compact('result','company'));
    }
     public function emailVerify($user_id)
    {
        try{
            if($user_id){
                User::where('id', $user_id)->update(['status' => '1']);

                $user_data = User::where('id', $user_id)->first();

                \Auth::login($user_data);

                // $email = $user_data['email'];
                // $data['username'] = $user_data['username'];
                // $data['first_name'] = $user_data['first_name'];

            //     \Mail::send('email.aprove', $data, function($message) use ($email){
            //     $message->from('admin@gexpo.in');
            //     $message->to($email);
            //     $message->subject('Account Approved');
            // });  

               // dd($user_data);

                // return view('frontend.pages.login');
                 return redirect()->route('home')->with('account_confirm', 'account_confirm');
            }
        }
        catch(\Exception $e){
           // dd($e);
        }
    }
}