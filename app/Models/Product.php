<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
   
    protected $fillable = [
        'sku',
        'quantity', 
        'thumbnail',  
        'trending', 
        'offer_price', 
        'four_lavel', 
        'featured_product', 
        'regular_price', 
        'five_lavel', 
        'six_lavel', 
        'seven_lavel', 
        'category_id', 
        'tax',
        'sub_category', 
        'sub_sub_category', 
        'product_description', 
        'name', 
        'description', 
        'product_type',
        'url', 
        'is_featured',
        'featured_image', 
        'meta_title', 
        'meta_description',  
        'status', 
        'created_at', 
        'updated_at', 
        'deleted_at', 
        'created_by', 
        'updated_by', 
        'deleted_by'
    ];

    public function validate($inputs, $id = null) {
        // $rules['sku'] = 'required';
        $rules['quantity'] = 'required';
        $rules['name'] = 'required|max:255';
        $rules['category_id'] = 'required|max:255';
        $rules['description'] = 'required';
        // $rules['regular_price'] = 'required|max:15';
        // $rules['offer_price'] = 'required|max:15';
        $rules['featured_image'] = 'required';
        $rules['meta_title'] = 'required|max:65';
        $rules['meta_description'] = 'required|max:160';
        
        return \Validator::make($inputs, $rules);
    }

    public function validate_update($inputs, $id = null) {
        $rules['category_id'] = 'required';
        // $rules['sku'] = 'required';
        $rules['name'] = 'required|max:255';
        // $rules['regular_price'] = 'required|max:15';
        // $rules['offer_price'] = 'required|max:15';
        $rules['description'] = 'required';
        $rules['meta_title'] = 'required|max:65';
        $rules['meta_description'] = 'required|max:160';
        return \Validator::make($inputs, $rules);
    }

    public function validateEdit($inputs, $id = null) {
        $rules['id'] = 'required';
        return \Validator::make($inputs, $rules);
    }

    public function validateEditStatus($inputs, $id = null) {
        $rules['id'] = 'required';
        $rules['status'] = 'required';
        return \Validator::make($inputs, $rules);
    }

    public function store($input, $id = null) {
        if ($id) {
            return $this->find($id)->update($input);
        } else {
            return $this->create($input)->id;
        }
    }

    public function deleteProduct($id) {
        $this->where('id', $id)->delete();
    }


    public function getProduct($search = null, $skip, $perPage) {
        $take = ((int)$perPage > 0) ? $perPage : 20;
        $filter = 1; // default filter if no search

        $fields = [
            'products.id',
            'products.name',
            'products.sku',
            'products.status',
            'products.product_type',
            'products.offer_price',
            'products.regular_price',
            'products.quantity',
            'products.featured_image',
            'categories.name as category',
            'c2.name as cat2',
            'c3.name as cat3',
            'c4.name as cat4',
            'c5.name as cat5',
            'c6.name as cat6',
            'c7.name as cat7',
        ];

        $sortBy = [
             'name' => 'name',
        ];

        $orderEntity = 'id';
        $orderAction = 'desc';
        if (isset($search['sort_action']) && $search['sort_action'] != "") {
             $orderAction = ($search['sort_action'] == 1) ? 'desc' : 'asc';
        }

        if (isset($search['sort_entity']) && $search['sort_entity'] != "") {
            $orderEntity = (array_key_exists($search['sort_entity'], $sortBy)) ? $sortBy[$search['sort_entity']] : $orderEntity;
        }

         if (is_array($search) && count($search) > 0) {
            $f1 = (array_key_exists('product_type', $search)) ? " AND (products.product_type = '" .
                addslashes($search['product_type']) . "')" : "";
              

            $f2 = (array_key_exists('sku', $search)) ? " AND (products.sku LIKE '%" .
                addslashes($search['sku']) . "%')" : "";


           $f4 = (array_key_exists('name', $search)) ? " AND (products.name LIKE '%" .
                addslashes(trim($search['name'])) . "%')" : "";


            $f6 = (array_key_exists('category_id', $search)) ? " AND (products.category_id = '" .
                addslashes(trim($search['category_id'])) . "')" : "";    


            $filter .= $f1 . $f2 . $f4 . $f6;
        }

        return $this->join('categories', 'categories.id' ,'=', 'products.category_id')
                ->leftjoin('categories as c2', 'c2.id' ,'=', 'products.sub_category')
                ->leftjoin('categories as c3', 'c3.id' ,'=', 'products.sub_sub_category')
                ->leftjoin('categories as c4', 'c4.id' ,'=', 'products.four_lavel')
                ->leftjoin('categories as c5', 'c5.id' ,'=', 'products.five_lavel')
                ->leftjoin('categories as c6', 'c6.id' ,'=', 'products.six_lavel')
                ->leftjoin('categories as c7', 'c7.id' ,'=', 'products.seven_lavel')
                ->whereRaw($filter)
                ->orderBy('products.id', 'desc')
                ->skip($skip)->take($take)
                ->get($fields);
     }


  
    public function totalProduct($search = null) {
         $filter = 1; // if no search add where

         // when search
        if (is_array($search) && count($search) > 0) {
             $partyName = (array_key_exists('keyword', $search)) ? " AND name LIKE '%" .
                 addslashes(trim($search['keyword'])) . "%' " : "";
             $filter .= $partyName;
        }

        return $this->select(\DB::raw('count(*) as total'))
             ->whereRaw($filter)->first();
     }


    public function totalProduct_warehouse_list($search = null) {
         $filter = 1; 

         // when search
         if (is_array($search) && count($search) > 0) {
             $partyName = (array_key_exists('keyword', $search)) ? " AND name LIKE '%" .
                 addslashes(trim($search['keyword'])) . "%' " : "";
             $filter .= $partyName;
         }
         return $this->select(\DB::raw('count(*) as total'))
             ->whereRaw($filter)->first();
    }

     

    public function tempDelete($id)
    {
        $this->find($id)->update([ 'deleted_by' => authUserId(), 'deleted_at' => convertToUtc()]);
    }
    
    public function getProductService()
    {
        $result = $this->where('status', 1)->pluck('name', 'id')->toArray();
        return ['' => '-Select Product-'] + $result;
    }





}
