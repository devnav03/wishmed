<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    
    protected $fillable = [
        'order_id', 'name', 'address', 'country', 'company_name', 'state', 'city', 'pincode',
        'created_at', 'updated_at', 'deleted_at'
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
