<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProductsHistory extends Model {
 
    protected $fillable = [
        'order_history_id', 
        'order_product_id',
        'qty',
        'created_at', 
        'updated_at'
    ];
    
    public function store($input, $id = null) {
        if ($id) {
            return $this->find($id)->update($input);
        } else {
            return $this->create($input)->id;
        }
    }

}
