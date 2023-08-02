<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    protected $fillable = [
        'pincode', 'zone_id', 'created_at', 'updated_at', 'deleted_at'
    ];

  

}
