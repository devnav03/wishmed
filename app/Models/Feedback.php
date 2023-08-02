<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model {
   
    protected $fillable = [
       	'name',  
        'designation',
        'comment',
        'image',
        'status', 
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];

    public function validate($inputs, $id = null) {
        $rules['name'] = 'required';
        $rules['designation'] = 'required';
        $rules['comment'] = 'required';
        $rules['image'] = 'required';
        return \Validator::make($inputs, $rules);
    }

    public function validateEdit($inputs, $id = null) {
        $rules['id'] = 'required';
        return \Validator::make($inputs, $rules);
    }

    public function store($input, $id = null) {
        if ($id) {
            return $this->find($id)->update($input);
        } else {
            return $this->create($input)->id;
        }
    }


    public function getFedback($search = null, $skip, $perPage) {
        $take = ((int)$perPage > 0) ? $perPage : 20;
        $filter = 1; // default filter if no search

        $fields = [
            'id',
            'name',
            'comment',
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

    
    public function totalFedback($search = null) {
        $filter = 1; // if no search add where
         // when search
        if (is_array($search) && count($search) > 0) {
             $partyName = (array_key_exists('keyword', $search)) ? " AND name LIKE '%" .
                 addslashes(trim($search['keyword'])) . "%' " : "";
             $filter .= $partyName;
        }
        return $this->select(\DB::raw('count(*) as total'))->whereRaw($filter)->first();
    }
   
}
