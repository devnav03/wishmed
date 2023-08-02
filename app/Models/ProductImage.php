<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
    use SoftDeletes;
   
    protected $fillable = [
       	'product_id', 'product_image', 'created_at', 
        'updated_at', 'deleted_at', 'created_by', 'deleted_by', 'updated_by'
    ];

    public function store($input, $id = null)
    {
        if ($id) {
            return $this->find($id)->update($input);
        } else {
            return $this->create($input)->id;
        }
    }

    public function deleteProductImage($id)
    {
        $this->where('id', $id)->delete();
    }
}
