<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestUser extends Model
{
    protected $fillable = [
        'access_id', 
        'created_at', 'updated_at', 'deleted_at'
    ];

    //Validate
    public function validateUser($inputs)
    {
        $rules = [
            'access_id' => 'required',
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
