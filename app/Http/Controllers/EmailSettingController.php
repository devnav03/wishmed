<?php

namespace App\Http\Controllers;

use Auth;
use Files;
use Illuminate\Support\Facades\Storage;
use App\Models\EmailSetting;
use App\Models\EmailOtp;
use App\User;
use Illuminate\Http\Request;

class EmailSettingController extends  Controller{
    
    public function index() {
       $id = 1;
       $result = (new EmailSetting)->find($id);
        if (!$result) {
            abort(401);
        }
       // dd($result);
        return view('admin.email_setting.create', compact('result'));
    }

    public function email_settings_otp() {
        return view('admin.email_setting.email_settings_otp');
    }


    public function update(Request $request){
        $id = 1;
        $result = (new EmailSetting)->find($id);
        if (!$result) {
            abort(401);
        }

        $inputs = $request->all();
        try {

            // $inputs = $inputs + [
            //     'updated_by' => Auth::id(),
            // ];  
            // (new EmailSetting)->store($inputs, $id);

            $ip = $request->getClientIp();
            $user_id = Auth::id();
            $otp = rand(100000, 999999);

            EmailOtp::create([
                'user_id' =>  $user_id,
                'ip_address' =>  $ip,
                'new_order_email' =>  $request['new_order_email'],
                'cancel_order_email' =>  $request['cancel_order_email'],
                'otp' =>  $otp
            ]);

            $user = User::where('id', $user_id)->select('email', 'name')->first();
            $email = $user->email;
            $data['name'] = $user->name;
            $data['otp'] = $otp;
            $data['ip'] = $ip;

            \Mail::send('email.email_setting', $data, function($message) use ($email){
                $message->from('no-reply@pukacreations.com');
                $message->to($email);
                $message->subject('Puka Creations - Email Settings OTP');
            });

            return redirect()->route('email-settings-otp');
            
            // return redirect()->route('email-settings')
            //     ->with('success', lang('messages.updated', lang('Emails')));

        } catch (\Exception $exception) {
        
            return redirect()->route('content-management')
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

    public function email_settings_otp_enter(Request $request){
        try{

            $result = EmailOtp::where('otp', $request->otp)->where('status', 0)->select('user_id', 'new_order_email', 'cancel_order_email', 'id')->first();
            if($result){

            EmailSetting::where('id', 1)
            ->update([
                'new_order_email' =>  $result->new_order_email,
                'cancel_order_email' =>  $result->cancel_order_email,
                'updated_by' =>  $result->user_id,
            ]);

            EmailOtp::where('id', $result->id)
            ->update([
                'status' =>  1,
            ]);

            return redirect()->route('email-settings')->with('success', lang('messages.updated', lang('Emails')));

            } else {
                return back()->with('not_match', lang('messages.created', lang('not_match')));
            }

        } catch (\Exception $exception) {
            return back();
        }

    }




    // public function edit($id = null) {
    //     $result = (new EmailSetting)->find($id);
    //     if (!$result) {
    //         abort(401);
    //     }
    //    // dd($result);
    //     return view('admin.content_management.create', compact('result'));
    // }
    

}
