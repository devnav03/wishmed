<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EmailSetting extends Model
{
   
    protected $fillable = [
       	'new_order_email', 'cancel_order_email', 'updated_by', 'created_at', 'updated_at'
    ];

    
    public function store($input, $id = null)
    {
        if ($id) {
            return $this->find($id)->update($input);
        } else {
            // return $this->create($input)->id;
        }
    }

    
}
