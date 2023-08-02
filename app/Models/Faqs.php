<?php

 namespace App\Models;
 use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;

 class Faqs extends Model
 {
     use SoftDeletes;

     protected $table = 'faq';

     protected $fillable = [
        'title',
        'description',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
     ];

     /**
      * @param $query
      * @return mixed
      */
     public function scopeActive($query)
     {
         return $query->where('status', 1);
     }
     

     public function validate($inputs, $id = null)
     { 
   

        $rules['title']          = 'required';   
        $rules['description'] = 'required'; 
        

        return \Validator::make($inputs, $rules);
     }

     /**
      * @param $input
      * @param null $id
      * @return mixed
      */
     public function store($input, $id = null)
     {
         if ($id) {
             return $this->find($id)->update($input);
         } else {
             return $this->create($input)->id;
         }
     }

     /**
      * @return mixed
      */
     public function updateStatusAll()
     {
         return $this->company()->where('status', 1)->update(['status' => 0]);
     }

     /**
      * @param null $search
      * @param $skip
      * @param $perPage
      * @return mixed
      */
     public function getFaq ($search = null, $skip, $perPage)
     {
         $take = ((int)$perPage > 0) ? $perPage : 20;
         $filter = 1; // default filter if no search

         $fields = [
             'id',
             'title',
             'description',
             'status'
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
                 " AND (title LIKE '%" .addslashes(trim($search['keyword'])) . "%')" : "";
             $filter .= $keyword;
         }

         return $this
             ->whereRaw($filter)
             ->orderBy($orderEntity, $orderAction)
             ->skip($skip)->take($take)->get($fields);
     }

     /**\
      * @param null $search
      * @return mixed
      */
     public function totalfaq($search = null)
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

     /**
      * @return mixed
      */
     public function getActiveFinancialYear()
     {
        return $this->active()->company()->first();
     }

     
 
    /**
      * @param $id
      */
    public function tempDelete($id)
    {
        $this->find($id)->update([ 'deleted_by' => authUserId(), 'deleted_at' => convertToUtc()]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function permanentlyDelete($id)
    {
        return $this->find($id)->forceDelete();
    }
    /**
      * @param null $search
      * @param $skip
      * @param $perPage
      * @return mixed
      */
 
   
     public function getAllFaq()
     {
        $fields = [
            'id',
             'title',
             'description',
             'category',
             'status'
        ];

        return $this->where('status',1)
                    ->paginate(50);
     }
}