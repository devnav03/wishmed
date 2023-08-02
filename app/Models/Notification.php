<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'message', 'user_id', 'image', 'created_at', 'updated_at', 'type', 'type_id', 'deleted_at'
    ];

     public function validate($inputs)
    {
    	//dd($inputs); exit;
    	$rules = [
    		'message' => 'required',
    	];
    	return \Validator::make($inputs, $rules);
    }
}
