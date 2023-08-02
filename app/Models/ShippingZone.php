<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingZone extends Model {
   
    protected $fillable = [
       	'name',  
        'flat_rate',
        'delivery',
        'local_pickup',
        'flat_price',
        'flat_tax',
        'delivery_price',
        'delivery_tax',
        'local_price',
        'local_tax',
        'pincodes',
        'status', 
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];

    public function validate($inputs, $id = null) {
        $rules['name'] = 'required|unique:shipping_zones|regex:/^[a-zA-Z ]+$/';
        return \Validator::make($inputs, $rules);
    }

    public function validateEdit($inputs, $id = null)
    {
        $rules['id'] = 'required';
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

    public function deleteGoal($id)
    {
        $this->where('id', $id)->delete();
    }

    public function getSlider($search = null, $skip, $perPage) {
        $take = ((int)$perPage > 0) ? $perPage : 20;
        $filter = 1; // default filter if no search

        $fields = [
            'id',
            'name',
            'status',
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
                 " AND (name LIKE '%" .addslashes(trim($search['keyword'])) . "%')" : "";
             $filter .= $keyword;
        }

        return $this
            ->whereRaw($filter)
            ->orderBy($orderEntity, $orderAction)
            ->skip($skip)->take($take)
            ->get($fields);
     }

    
     public function totalSlider($search = null)
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
   
}
