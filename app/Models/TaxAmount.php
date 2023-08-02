<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxAmount extends Model {
   
    protected $fillable = [
       	'product_tax', 'shipping_tax', 'created_at', 'updated_at'
    ];

    
    public function store($input, $id = null) {
        if ($id) {
            return $this->find($id)->update($input);
        } else {
            // return $this->create($input)->id;
        }
    }

    
}
