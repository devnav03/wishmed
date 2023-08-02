<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestCart extends Model
{
   use SoftDeletes;

    protected $fillable = [
        'user_id', 'product_id', 'quantity',
        'created_at', 'updated_at', 'deleted_at', 'updated_by', 'deleted_by', 'created_by'
    ];

	//Validate
    public function validateCart($inputs)
    {
        $rules = [
            'product_id' => 'required|numeric',
            'quantity' => 'required|numeric',
        ];

        return \Validator::make($inputs, $rules);
    }

    //Validate
    public function validateUpdateCart($inputs)
    {
        $rules = [
            'id' => 'required|numeric',
            'quantity' => 'required|numeric',
        ];

        return \Validator::make($inputs, $rules);
    }

     //Validate
    public function validateRemoveCart($inputs)
    {
        $rules = [
            'product_id' => 'required|numeric',
            'cart_id' => 'required|numeric',
        ];

        return \Validator::make($inputs, $rules);
    }

     //Validate
    public function validateRemoveWishlist($inputs)
    {
        $rules = [
            'product_id' => 'required|numeric',
            'id' => 'required|numeric',
        ];

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

    public function deleteItem($id, $product_id)
    {
        $this->where('id', $id)->where('product_id', $product_id)->delete();
    }

    public function clearCart($id)
    {
        $this->where('user_id', $id)->delete();
    }

      public function getcartproducts($id){
        return $this->where('guest_carts.user_id', $id)
                    ->get();
    }

    public function removeCart($inputs){
       return $this->where('guest_carts.product_id', $inputs['product_id'])
                    ->where('guest_carts.user_id', $inputs['user_id'])
                    ->delete();
    }
}
