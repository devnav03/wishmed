<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AboutContent extends Model
{
 
   
    protected $fillable = [
       	'about', 'privacy', 'terms_conditions'
    ];

    

}
