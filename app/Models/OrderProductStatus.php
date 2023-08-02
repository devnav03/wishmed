<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProductStatus extends Model
{
    protected $fillable = [
        'status', 'order_id',
        'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'
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
