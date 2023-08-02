<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offers';

    protected $fillable = [
        'type_id', 'title', 'show_in_website', 'section', 'message', 'status', 'category_id', 'product_id','discount_type','sub_product', 'brand_id',
        'min_amount','off_amount','valid_to', 'valid_from','per_user', 'max_user', 'off_percentage', 
        'max_discount', 'promo_code',
        'created_at', 'updated_at', 'deleted_at', 'updated_by', 'deleted_by', 'created_by'
    ];

  
    public function validateProduct($inputs)
    {
        $rules = [
            'product_id' => 'required',
        ];
        return \Validator::make($inputs, $rules);
    }

     //Validate
    public function validateOffer($inputs)
    {
        $rules = [
            'offer_id' => 'required',
        ];
        return \Validator::make($inputs, $rules);
    }


     //Validate
    public function validatePromoCode($inputs)
    {
        $rules = [
            'promo_code' => 'required',
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

    // Navjot Code Start
    public function getOffer($search = null, $skip, $perPage)
     {
         $take = ((int)$perPage > 0) ? $perPage : 20;
         $filter = 1; // default filter if no search

         $fields = [
            'offers.id',
            'offers.type_id',
            'offers.message',
            'offers.category_id',
            'offers.max_discount',
            'offers.title',
            'offers.valid_to',
            'offers.status',
            'offer_type.name',
          ];

         $sortBy = [
             'title' => 'title',
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
             $keyword = (array_key_exists('keyword', $search)) ?
                 " AND (offers.title LIKE '%" .addslashes(trim($search['keyword'])) . "%')" : "";
             $filter .= $keyword;
         }

         return $this->join('offer_type', 'offer_type.id' ,'=', 'offers.type_id')
                ->whereRaw($filter)
                ->orderBy($orderEntity, $orderAction)
                ->skip($skip)->take($take)
                ->get($fields);
     }

    
     public function totalOffer($search = null)
     {
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

    public function tempDelete($id)
    {
        $this->find($id)->update([ 'deleted_by' => authUserId(), 'deleted_at' => convertToUtc()]);
    }

     public function getOfferService()
    {
        $result = $this->where('status', 1)->pluck('title', 'id')->toArray();
        return ['' => '-Select Offer-'] + $result;
    }

    public function validate($inputs, $id = null)
    {
        $rules['title'] = 'required';
        $rules['type_id'] = 'required';
        $rules['per_user'] = 'required';
        $rules['valid_from'] = 'required';
        $rules['valid_to'] = 'required';
        $rules['message'] = 'required';
        if($inputs['type_id']== 1){ 
            $rules['off_amount'] =  'max:15|required';
            $rules['min_amount'] =  'max:15|required';
        }  
        if($inputs['type_id']== 2){ 
            $rules['off_percentage'] =  'max:3|required';
            $rules['max_discount'] =  'max:15|required';
            $rules['min_amount'] =  'max:15|required';
        }
        if($inputs['type_id']== 3){ 
            $rules['product_id'] =  'max:15|required';
            $rules['discount_type'] =  'max:15|required';
            if($inputs['discount_type']== 'Price'){ 
                $rules['off_amount'] =  'max:3|required';
                $rules['min_amount'] =  'max:15|required';
                $rules['max_discount'] =  'max:15|required';
            } else {
                $rules['off_percentage'] =  'max:3|required';
                $rules['max_discount'] =  'max:15|required';
                $rules['min_amount'] =  'max:15|required';
            }
        }
        if($inputs['type_id']== 4){ 
            $rules['category_id'] =  'max:15|required';
            $rules['discount_type'] =  'max:15|required';
            if($inputs['discount_type']== 'Price'){ 
                $rules['off_amount'] =  'max:3|required';
                $rules['min_amount'] =  'max:15|required';
                $rules['max_discount'] =  'max:15|required';
            } else {
                $rules['off_percentage'] =  'max:3|required';
                $rules['max_discount'] =  'max:15|required';
                $rules['min_amount'] =  'max:15|required';
            }
        }
        if($inputs['type_id']== 7){ 
            $rules['brand_id'] =  'max:15|required';
            $rules['discount_type'] =  'max:15|required';
            if($inputs['discount_type']== 'Price'){ 
                $rules['off_amount'] =  'max:3|required';
                $rules['min_amount'] =  'max:15|required';
                $rules['max_discount'] =  'max:15|required';
            } else {
                $rules['off_percentage'] =  'max:3|required';
                $rules['max_discount'] =  'max:15|required';
                $rules['min_amount'] =  'max:15|required';
            }
        }
        if($inputs['type_id']== 6){ 
            $rules['product_id'] =  'max:15|required';
            $rules['sub_product'] =  'max:15|required';
        }

        return \Validator::make($inputs, $rules);
    }

}
