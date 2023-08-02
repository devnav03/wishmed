<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderUpdate extends Model {
   
    protected $fillable = [
       	'user_id', 'order_id', 'message', 'created_at', 'updated_at'
    ];

}
// 