<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderHistory extends Model {
 
    protected $fillable = [
        'order_id', 
        'last_price',
        'created_at', 
        'updated_at', 
        'updated_by'
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
