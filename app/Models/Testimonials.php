<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Testimonials extends Model
{
    use SoftDeletes;
   
    protected $fillable = [
       	'name','image', 'review', 'status', 'created_at', 
        'updated_at', 'deleted_at', 'created_by', 'deleted_by', 'updated_by'
    ];

    public function validate($inputs, $id = null)
    {
        $rules['review'] = 'required';
        $rules['image'] = 'required|mimes:jpeg,jpg,png|required|max:1024KB';
        $rules['name'] = 'required|regex:/^[a-zA-Z ]+$/';
        return \Validator::make($inputs, $rules);
    }
    
    public function validate_front($inputs, $id = null)
    {
        $rules['review'] = 'required';
        $rules['image'] = 'required|mimes:jpeg,jpg,png|required|max:1024KB';
        $rules['name'] = 'required|regex:/^[a-zA-Z ]+$/';
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

    public function deleteReview($id)
    {
        $this->where('id', $id)->delete();
    }

  
    public function getReviews($search = null, $skip, $perPage)
     {
         $take = ((int)$perPage > 0) ? $perPage : 20;
         $filter = 1; // default filter if no search

         $fields = [
            'testimonials.id',
            'testimonials.name',
            'testimonials.review',
            'testimonials.status',
            'testimonials.image',
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
             $keyword = (array_key_exists('keyword', $search)) ?
                 " AND (testimonials.name LIKE '%" .addslashes(trim($search['keyword'])) . "%')" : "";
             $filter .= $keyword;
         }

         return $this
                ->whereRaw($filter)
                ->orderBy($orderEntity, $orderAction)
                ->skip($skip)->take($take)
                ->get($fields);
     }

     /**\
      * @param null $search
      * @return mixed
      */
     public function totalReviews($search = null)
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
