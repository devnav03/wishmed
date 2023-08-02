<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigureProduct extends Model
{
    use SoftDeletes;
   
    protected $fillable = [
        'group_id', 
        'simple_id',  
        'created_at', 
        'updated_at', 
   
    ];


    public function store($input, $id = null)
    {
        if ($id) {
            return $this->find($id)->update($input);
        } else {
            return $this->create($input)->id;
        }
    }




}
