<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpecialPrice extends Model {

    protected $table = 'special_prices';
   
    protected $fillable = [
        'user_id', 'product_id', 'price', 'created_at', 'updated_at'
    ];

    public function validate($inputs, $id = null) {
        // $rules['product_id'] = 'required';
        // $rules['price'] = 'required';
        return \Validator::make($inputs, $rules);
    }


    public function store($inputs, $id = null) {
       // dd($inputs);
        if ($id) {
            return $this->find($id)->update($inputs);
        } else {
            return $this->create($inputs)->id;
        }
    }


}
