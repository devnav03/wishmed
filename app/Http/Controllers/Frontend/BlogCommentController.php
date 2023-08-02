<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BlogComment;

class BlogCommentController extends Controller
{
    public function store(Request $request)
    {
        validator($request->all(), [
            'name' => [
                'required', 'string', 'regex:/^[a-zA-Z ]*$/'
            ],
            'email' => 'nullable|email',
            'comment' => 'required',
           
        ])->validate();

    	try{
    		$inputs = $request->all();
        	if(\Auth::check()){
        		$inputs = $inputs + [
        			'user_id' => \Auth::User()->id,
        			'created_by' => \Auth::User()->id,
        		];
        	}
            $inputs = $inputs + [	
                'status'    => 0,
            ];          
            (new RecordComment)->store($inputs);

         
            return response(['message' => trans('common.created', ['attribute' => 'Record Comment'])], 200);

            //return back()->with('success', 'record successfully submitted.');
    	}
    	catch(\Exception $e){
    		//dd($e);
    		return back();
    	}
    }
}
