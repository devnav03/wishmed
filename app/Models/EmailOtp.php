<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{

     protected $table = 'email_otps';

    protected $fillable = [
        'user_id', 'ip_address', 'new_order_email', 'cancel_order_email', 'otp', 'status',
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function store($inputs, $id = null)
     {

         if ($id) {
             return $this->find($id)->update($inputs);
         } else {
             return $this->create($inputs)->id;
         }
     }

    


}
