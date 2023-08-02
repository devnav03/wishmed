<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PushNotification;

class UserDevice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'device_token', 'created_at', 'updated_at', 'deleted_at'
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
