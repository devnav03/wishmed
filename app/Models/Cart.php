<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;
   
    protected $fillable = [
       	'user_id', 'product_id', 'session_id', 'quantity', 'created_at', 
        'updated_at', 'deleted_at', 'created_by', 'deleted_by', 'updated_by'
    ];

    public function validate($inputs, $id = null)
    {
        $rules['product_id'] = 'required';
        $rules['quantity'] = 'required';
        return \Validator::make($inputs, $rules);
    }

    public function validateEdit($inputs, $id = null)
    {
        $rules['id'] = 'required';
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

public function validate_guest_delete($inputs, $id = null)
    {
        $rules['product_id'] = 'required';
        $rules['access_id'] = 'required';

        return \Validator::make($inputs, $rules);
    }
    //Validate
    public function validateUpdateCart($inputs)
    {
        $rules = [
            'id' => 'required|numeric',
            'quantity' => 'required|numeric',
        ];

        return \Validator::make($inputs, $rules);
    }

    public function deleteCart($id)
    {
        $this->where('id', $id)->delete();
    }
}
