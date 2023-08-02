<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ContentManagement extends Model
{
   
    protected $fillable = [
       	'about', 'privacy', 'terms_conditions', 'refund_return', 'contact', 'updated_by', 'created_at', 'updated_at'
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
