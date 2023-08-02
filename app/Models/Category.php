<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $table = 'categories';
   
    protected $fillable = [
        'name', 'parent_id', 'image', 'order', 'url', 'status', 'created_at', 
        'updated_at', 'deleted_at', 'created_by', 'deleted_by', 'updated_by'
    ];

    public function validate($inputs, $id = null)
    {
        $rules['name'] = 'required|unique:categories';
        $rules['order'] = 'required';
        $rules['image'] = 'required';
        return \Validator::make($inputs, $rules);
    }

    public function validateEdit($inputs, $id = null)
    {
        $rules['id'] = 'required';
        return \Validator::make($inputs, $rules);
    }

    public function validateStatus($inputs, $id = null)
    {
        $rules['status'] = 'required';
        return \Validator::make($inputs, $rules);
    }


    public function store($inputs, $id = null)
    {
       // dd($inputs);
        if ($id) {
            return $this->find($id)->update($inputs);
        } else {
            return $this->create($inputs)->id;
        }
    }

    public function deleteCategory($id)
    {
        $this->where('id', $id)->delete();
    }
// Navjot Code Start
    public function getCategoryService()
    {
        $result = $this->where('status', 1)->pluck('name', 'id')->toArray();
        return ['' => '-Select Category-'] + $result;
    }

    public function getCategory($search = null, $skip, $perPage)
     {
         $take = ((int)$perPage > 0) ? $perPage : 20;
         $filter = 1; // default filter if no search

         $fields = [
             'categories.id',
             'categories.name',
             'categories.parent_id',
             'categories.status',
             'categories.image',
             'c2.name as parent_category'
         ];

         $sortBy = [
             'name' => 'name',
         ];

         $orderEntity = 'name';
         $orderAction = 'asc';
         if (isset($search['sort_action']) && $search['sort_action'] != "") {
             $orderAction = ($search['sort_action'] == 1) ? 'desc' : 'asc';
         }

         if (isset($search['sort_entity']) && $search['sort_entity'] != "") {
             $orderEntity = (array_key_exists($search['sort_entity'], $sortBy)) ? $sortBy[$search['sort_entity']] : $orderEntity;
         }

         if (is_array($search) && count($search) > 0) {
             $keyword = (array_key_exists('keyword', $search)) ?
                 " AND (categories.name LIKE '%" .addslashes(trim($search['keyword'])) . "%')" : "";
             $filter .= $keyword;
         }

         return $this->leftjoin('categories as c2', 'categories.parent_id' ,'=', 'c2.id')
                ->whereRaw($filter)
                ->orderBy($orderEntity, $orderAction)
                ->skip($skip)->take($take)
                ->get($fields);
     }

     /**\
      * @param null $search
      * @return mixed
      */
     public function totalCategory($search = null)
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
