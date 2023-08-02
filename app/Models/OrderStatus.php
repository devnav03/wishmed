<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatus extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'type', 'status',
        'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'
    ];

	//Validate
    public function validateOrderStatus($inputs)
    {
        $rules = [
            'type' => 'required|max:50|unique:order_statuses',
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

     public function getOrderStatusService()
    {
        $result = $this->where('status', 1)->pluck('type', 'id')->toArray();
        return ['' => '-Select Order Status-'] + $result;
    }
}
