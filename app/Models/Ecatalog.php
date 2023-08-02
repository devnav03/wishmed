<?php

 namespace App\Models;
 use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;

 class Ecatalog extends Model
 {
     use SoftDeletes;

     protected $table = 'e_catalogs';

     protected $fillable = [
        'title',
        'background_image',
        'catalog_file',
        'category',
        'url',
        'created_by',
        'updated_by',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
     ];

     
     public function scopeActive($query)
     {
         return $query->where('status', 1);
     }
     
   
    public function validate($inputs, $id = null) { 
   
        $rules['title']  = 'required';   
        $rules['background_image']     = 'required';

        if(isset($inputs['catalog_file'])) {
            
        } else {
            $rules['url'] = 'required';
        }

        

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


 
     public function getEcatalog($search = null, $skip, $perPage)
     {
         $take = ((int)$perPage > 0) ? $perPage : 20;
         $filter = 1; // default filter if no search

         $fields = [
             'id',
             'title',
             'background_image',
             'catalog_file',
             'url',
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

     public function totalEcatalog($search = null)
     {
         $filter = 1; // if no search add where

         // when search
         if (is_array($search) && count($search) > 0) {
             $partyName = (array_key_exists('keyword', $search)) ? " AND title LIKE '%" .
                 addslashes(trim($search['keyword'])) . "%' " : "";
             $filter .= $partyName;
         }
         return $this->select(\DB::raw('count(*) as total'))
             ->whereRaw($filter)->first();
     }

     public function getActiveFinancialYear()
     {
        return $this->active()->company()->first();
     }

     

    public function tempDelete($id)
    {
        $this->find($id)->update([ 'deleted_by' => authUserId(), 'deleted_at' => convertToUtc()]);
    }

    public function permanentlyDelete($id)
    {
        return $this->find($id)->forceDelete();
    }
 
   
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