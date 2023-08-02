<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'status',
        'created_at', 'updated_at', 'deleted_at', 'updated_by', 'deleted_by', 'created_by'
    ];

    public function validate($inputs)
    {
    	$rules = [
    		'product_id' => 'required',
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
}
