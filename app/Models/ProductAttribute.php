<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    use SoftDeletes;
   
    protected $fillable = [
       	'product_id', 'attribute_id', 'attribute_value_id', 'created_at', 
        'updated_at', 'deleted_at', 'created_by', 'deleted_by', 'updated_by'
    ];

    public function validateEdit($inputs, $id = null)
    {
        $rules['id'] = 'required';
        return \Validator::make($inputs, $rules);
    }

    public function validateProductVariations($inputs, $id = null)
    {
        $rules['product_id'] = 'required';
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

    public function deleteProductType($id)
    {
        $this->where('id', $id)->delete();
    }
}
