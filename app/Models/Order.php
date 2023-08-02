<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 
        'order_from', 
        'wallet_paid', 
        'total_price', 
        'shipping_charges', 
        'discount', 
        'payment_method', 
        'transaction_id', 
        'status', 
        'po_number',
        'current_status', 
        'shipping_method',
        'offer_id', 
        'created_at', 
        'updated_at', 
        'deleted_at', 
        'amount_paid', 
        'wallet_amount', 
        'shipping_price',
        'shipping_tax',
        'product_tax',
        'order_nr', 
        'order_from',
        'billing_first_name',
        'billing_last_name',
        'billing_company_name',
        'billing_street_address',
        'billing_street_address2',
        'billing_suburb',
        'billing_state',
        'billing_postcode',
        'billing_phone',
        'billing_email_address',
        'ship_different_address',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_company_name',
        'shipping_street_address',
        'shipping_street_address2',
        'shipping_suburb',
        'shipping_state',
        'shipping_postcode',
        'order_notes',
    ];

    //Validate
    public function validateOrder($inputs)
    {
        $rules = [
            'shipping_id' => 'required|numeric',
            'total_price' => 'required|numeric',
            'shipping_charges' => 'required|numeric',
            'tax' => 'required|numeric',
            'discount' => 'required|numeric',
            'payment_method' => 'required'
        ];
        return \Validator::make($inputs, $rules);
    }

    //Validate
    public function validateOrderStatus($inputs)
    {
        $rules = [
            'order_id' => 'required|numeric',
            'status' => 'required|numeric',
        ];
        return \Validator::make($inputs, $rules);
    }

    public function recordvalidate($inputs)
    {
        $rules = [
            'from'  => 'required|date',
            'to'    => 'required|date|after:from',
        ];
        return \Validator::make($inputs, $rules);
    }
 
    public function validateReturnOrder($inputs)
    {
        $rules = [
            'user_id'  => 'required|numeric',
            'order_id'    => 'required|numeric',
            'amount'    => 'required|numeric',
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

  
    public function getOrder($search = null, $skip, $perPage, $user_type, $user_id )
     {
         $take = ((int)$perPage > 0) ? $perPage : 100;
         $filter = 1; // default filter if no search

         $fields = [
            'orders.id',
            'orders.user_id',
            'orders.order_nr',
            'orders.order_from',
            'orders.total_price',
            'orders.transaction_id',
            'orders.payment_method',
            'orders.current_status as c_status',
            'orders.status',
            'orders.created_at',
            'order_statuses.type as current_status',
            'users.name as user_name',

            'orders.billing_first_name',
            'orders.billing_last_name',
            'orders.billing_company_name',
            'orders.billing_street_address',
            'orders.billing_street_address2',
            'orders.billing_suburb',
            'orders.billing_state',
            'orders.billing_postcode',
            'orders.billing_phone',
            'orders.billing_email_address',
            'orders.ship_different_address',
            'orders.shipping_first_name',
            'orders.shipping_last_name',
            'orders.shipping_company_name',
            'orders.shipping_street_address',
            'orders.shipping_street_address2',
            'orders.shipping_suburb',
            'orders.shipping_state',
            'orders.shipping_postcode',
            'orders.order_notes',
            
          ];

         $sortBy = [
             'order_nr' => 'order_nr',
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
            $f1 = (array_key_exists('order_nr', $search)) ? " AND (orders.order_nr = '" .
                addslashes($search['order_nr']) . "')" : "";
              

            $f2 = (array_key_exists('user_id', $search)) ? " AND (orders.user_id = '" .
                addslashes($search['user_id']) . "')" : "";

            $f3 = (array_key_exists('current_status', $search)) ? " AND (orders.current_status = '" .
                addslashes($search['current_status']) . "')" : "";
           

            $filter .= $f1 . $f2 . $f3;
        }

            return $this->join('order_statuses', 'order_statuses.id' ,'=', 'orders.current_status')
            ->join('users', 'users.id' ,'=', 'orders.user_id')
                ->whereRaw($filter)
                ->orderBy($orderEntity, $orderAction)
                ->skip($skip)->take($take)
                ->get($fields);

            
    }

 
    public function totalOrder($search = null, $user_type, $user_id)
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
    
    public function front_validate($inputs, $id = null) {

        $rules['billing_first_name'] = 'required';
        $rules['billing_last_name'] = 'required';
        $rules['billing_street_address'] = 'required';
        $rules['billing_suburb'] = 'required';
        $rules['billing_state'] = 'required';
        $rules['billing_postcode'] = 'required';
        $rules['billing_phone'] = 'required';
        $rules['billing_email_address'] = 'required';
        $rules['card_holder_name'] = 'required';
        $rules['card_number'] = 'required';
        $rules['expiration_month'] = 'required';
        $rules['expiration_year'] = 'required';
        $rules['cvn'] = 'required';

        return \Validator::make($inputs, $rules);
    }
    
    public function front_validate_pay_later($inputs, $id = null) {
        $rules['address'] = 'required';
        return \Validator::make($inputs, $rules);
    }
    
    
     
      


}
