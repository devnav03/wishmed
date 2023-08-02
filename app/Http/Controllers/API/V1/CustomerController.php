<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;

class CustomerController extends Controller
{
    //Add Customer
    public function storeCustomer(Request $request){
		try{    
            $inputs = $request->all();
            $validator = (new Customer)->validate($inputs);
            if( $validator->fails() ) {
                return apiResponse(false, 200, "", errorMessages($validator->messages()));
            }
           
            $inputs = $inputs + ['created_by' => \Auth::User()->id];

            (new Customer)->store($inputs);
            return apiResponse(true, 200, lang('Customer added successfully'));

      	}catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
      	}
    }

    //All Store
    public function allCustomer(){
		try{    
            
              $data = Customer::where('created_by', \Auth::User()->id)->orderBy('created_at', 'desc')->get();
            if (count($data) != 0) {
            	return apiResponse(true, 200, null, null, $data);
            }else{
            	return apiResponse(false, 200, lang('No Store found'));
            }


      	}catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
      	}
    }
}
