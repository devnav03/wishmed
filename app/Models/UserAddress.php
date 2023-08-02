<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 
        'billing_first_name', 
        'billing_last_name',
        'billing_company_name',
        'billing_street_address', 
        'billing_street_address2', 
        'billing_suburb', 
        'billing_state', 
        'billing_postcode', 
        'billing_phone',
        'billing_email_address',
        'ship_different_address',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_company_name',
        'shipping_street_address',
        'shipping_street_address2',
        'shipping_suburb',
        'shipping_state',
        'shipping_postcode',
        'created_at', 
        'updated_at', 
        'deleted_at', 
        'created_by', 
        'updated_by', 
        'deleted_by'
    ];


    public function validateUserAddress($inputs) {
        $rules = [
            'billing_first_name' => 'required|max:50',
            'billing_street_address' => 'required|max:200',
            'billing_suburb' => 'required',
            'billing_state' => 'required',
            'billing_postcode' => 'required',
        ];
        return \Validator::make($inputs, $rules);
    }

    public function validateAddressID($inputs) {
        $rules = [
            'id' => 'required|numeric',
        ];
        return \Validator::make($inputs, $rules);
    }
    

    public function store($input, $id = null) {
        if ($id) {
            return $this->find($id)->update($input);
        } else {
            return $this->create($input)->id;
        }
    }

    public function deleteAddress($id) {
        $this->where('id', $id)->where('user_id', \Auth::User()->id)->delete();
    }

    public function validate($inputs, $id = null) {
        $rules['billing_first_name'] = 'required|max:255|regex:/^[a-zA-Z ]+$/';
        $rules['billing_street_address'] = 'required|max:255';
        $rules['billing_suburb'] = 'required|max:255';
        $rules['billing_state'] = 'required|max:255';
        $rules['billing_postcode'] = 'required|max:255';
      
        return \Validator::make($inputs, $rules);
    }


}
