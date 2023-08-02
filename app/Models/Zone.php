<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'status', 'created_at', 'updated_at', 'deleted_at'
    ];

    
    public function store($input, $id = null)
    {
        if ($id) {
            return $this->find($id)->update($input);
        } else {
            return $this->create($input)->id;
        }
    }

    public function deleteAddress($id) {
        $this->where('id', $id)->where('user_id', \Auth::User()->id)->delete();
    }



}
