<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsCode extends Model
{
    protected $guarded = [];
    
	//Delete temporary sms codes
    public function deleteTempCode($id)
    {
        $this->where('id', $id)->forceDelete();
    }

    //Store sms codes
    public function store($user_id, $otp)
    {
        // Delete existing otp
        $this->where('user_id', $user_id)->forceDelete();
        $smsCodes = [
                    'user_id' => $user_id,
                    'code'    => $otp,
                    'status'  => 0,
                    ];
        $this->create($smsCodes)->id;
       
    }

    //Resend otp
    public function resendSMS($mobile)
    {
        $fields = [
                    'users.id as user_id'
                ];
        return $this->join('users','users.id','=','sms_codes.user_id')
                    ->where('users.phone', $mobile)
                    ->where('users.status', 0)
                    ->where('sms_codes.status', 0)
                    ->first($fields);
    }

    //Resend otp for password reset
    public function resendResetPwdSMS($mobile)
    {
        $fields = [
                    'users.id as user_id'
                ];
        return $this->join('users','sms_codes.user_id','=','users.id')
                    ->where('users.phone', $mobile)
                    ->where('users.status', 1)
                    ->where('sms_codes.status', 1)
                    ->first($fields);
    }

    public function activateSmsCode( $id ) {
        $this->find($id)->update(['status' => 1]);
    }
}
