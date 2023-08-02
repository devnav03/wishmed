<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OfferType;


class OfferTypeController extends Controller
{
      //Create Offer Type
    public function createOfferType(Request $request){
    	try{
    		$inputs = $request->all();
    		$validator = ( new OfferType )->validate( $inputs );
            if( $validator->fails() ) {
                return apiResponseApp(false, 406, "", errorMessages($validator->messages()));
            }
       		$inputs = $inputs + [
                                'created_by' => \Auth::User()->id,
                                'status' => 1
                                ];
            (new OfferType)->store($inputs);

			return apiResponseApp(true, 200, lang('Offer Type created successfully'));

    	}catch(Exception $e){
			return apiResponseApp(false, 500, lang('messages.server_error'));
    	}
    }

    //All Offer Type
    public function allOfferType(){
    	try{
    		$data = OfferType::orderBy('created_at', 'desc')->where('status', 1)->get();
    		if (count($data) != 0) {
				return apiResponseApp(true, 200, null, null, $data);
    		}else{
				return apiResponseApp(false, 400, lang('No Offer Type exist in our records'));
    		}
    	}catch(Exception $e){
			return apiResponseApp(false, 500, lang('messages.server_error'));
    	}
    }
}
