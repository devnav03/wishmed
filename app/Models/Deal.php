<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $fillable = [
        'min_qty', 'discount', 'status', 'created_at', 'updated_at', 'deleted_at'
    ];

  

}
