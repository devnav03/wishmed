<?php

 namespace App\Models;
 use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;

 class Tradeshow extends Model
 {
     use SoftDeletes;

     protected $table = 'tradeshow';

     protected $fillable = [
        'name',
        'region',
        'place',
        'from_date',
        'to_date',
        'booth',
        'down_payment_1',
        'down_payment_1_remark',
        'down_payment_2',
        'down_payment_2_remark',
        'down_payment_3',
        'down_payment_3_remark',
        'down_payment_date_1',
        'down_payment_date_2',
        'down_payment_date_3',
        'total_payment',
        'balance',
        'balance_remark',
        'total_cost',
        'total_cost_remark',
        'hotel_booking',
        'hotel_booking_remark',
        'logistics',
        'logistics_remark',
        'comment',
        'created_by',
        'updated_by',
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
     
     /**
      * @param $inputs
      * @param null $id
      * @return \Illuminate\Validation\Validator
      */
     public function validate($inputs, $id = null)
     { 
   
        $rules['name']      = 'required';   
        // $rules['place']     = 'required';
        $rules['from_date'] = 'required'; 
        $rules['to_date'] = 'required'; 
        // $rules['booth'] = 'required'; 

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


 
     public function getTradeshow($search = null, $skip, $perPage)
     {
         $take = ((int)$perPage > 0) ? $perPage : 20;
         $filter = 1; // default filter if no search

         $fields = [
             'id',
             'name',
             'region',
             'place',
             'from_date',
             'to_date',
             'total_payment',
             'down_payment_1',
             'down_payment_2',
             'down_payment_3',
             'booth',
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
                 " AND (name LIKE '%" .addslashes(trim($search['keyword'])) . "%')" : "";
             $filter .= $keyword;
         }

         return $this
             ->whereRaw($filter)
             ->orderBy($orderEntity, $orderAction)
             ->skip($skip)->take($take)->get($fields);
     }

     public function totalTradeshow($search = null)
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