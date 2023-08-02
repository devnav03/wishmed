<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingAddress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'address', 'mobile', 'state', 'billing_phone', 'billing_email', 'billing_company', 'city', 'pincode',
        'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'
    ];


    public function validateUserAddress($inputs)
    {
        $rules = [
            'name' => 'required|max:50',
            'address' => 'required|max:200',
            'state' => 'required',
            'city' => 'required',
            'mobile' => 'required|digits:10',
            'pincode' => 'required|digits:6',
        ];

        return \Validator::make($inputs, $rules);
    }
    
    //Validate
    public function validateAddressID($inputs)
    {
        $rules = [
            'id' => 'required|numeric',
        ];

        return \Validator::make($inputs, $rules);
    }
    
    public function store($input, $id = null)
    {
        if ($id) {
            return $this->find($id)->update($input);
        } else {
            return $this->create($input)->id;
        }
    }

    public function deleteAddress($id)
    {
        $this->where('id', $id)->where('user_id', \Auth::User()->id)->delete();
    }

     public function validate($inputs, $id = null)
    {
        $rules['name'] = 'required|max:255|regex:/^[a-zA-Z ]+$/';
        $rules['address'] = 'required|max:255';
        $rules['state'] = 'required|max:255';
        $rules['city'] = 'required|max:255';
        $rules['pincode'] = 'required|digits:6';
        $rules['mobile'] = 'required|digits:10';
      
        return \Validator::make($inputs, $rules);
    }


}
