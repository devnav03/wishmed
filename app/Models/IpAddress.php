<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IpAddress extends Model {
   
    protected $fillable = [
       	'ip', 'created_at', 'updated_at'
    ];

}
// 