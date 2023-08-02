<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ForceUpdate extends Model
{
   
    protected $fillable = [
       	'mobile', 'email', 'version', 'force_update'
    ];

    

}
