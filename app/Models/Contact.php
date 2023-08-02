<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

     protected $table = 'contact_enquiry';

    protected $fillable = [
        'first_name', 'last_name', 'email', 'message', 'phone', 'subject',
        'created_at', 'updated_at'
    ];


     public function front_contact($inputs, $id=null)
     {
        
            $rules['first_name'] = 'required|max:100';
            $rules['last_name'] = 'required|max:100';
            $rules['email'] = 'required|max:100';
            $rules['message'] = 'required|max:800';
            $rules['phone'] = 'required';
            $rules['subject'] = 'required|max:100';
            

        return \Validator::make($inputs, $rules);
     }


    public function store($inputs, $id = null)
     {

         if ($id) {
             return $this->find($id)->update($inputs);
         } else {
             return $this->create($inputs)->id;
         }
     }

     public function getContact($search = null, $skip, $perPage)
     {
         $take = ((int)$perPage > 0) ? $perPage : 20;
         $filter = 1; // default filter if no search

         $fields = [
            'id',
            'first_name',
            'last_name',
            'email',
            'phone',
            'subject',
            'message'
          ];

         $sortBy = [
             'first_name' => 'first_name',
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
                 " AND (first_name LIKE '%" .addslashes(trim($search['keyword'])) . "%')" : "";
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
     public function totalContact($search = null)
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

}
