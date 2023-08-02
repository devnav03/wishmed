<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefaultAddress extends Model
{
    use SoftDeletes;
   
    protected $fillable = [
       	'user_id', 'address_id', 'created_at', 'updated_at', 'deleted_at'
    ];

   
}
