<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Order;
use App\Models\Notification;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\User;
use App\Models\UserDevice;

class OfferController extends Controller
{
      //Create Offer 
    public function createOffers(Request $request){
    	try{
    		$inputs = $request->all();
    		$validator = ( new Offer )->validate( $inputs );
            if( $validator->fails() ) {
                return apiResponseApp(false, 406, "", errorMessages($validator->messages()));
            }

            $featured_image = rand(100000, 999999);

            $request->file('slider_image')->move(public_path().'/uploads/slider_image/', $featured_image);
            $user = \Auth::User()->id;
                unset($inputs['slider_image']);
            $url = 'http://kuickfit.thegirafe.in/uploads/slider_image/'.$featured_image;

       		$inputs = $inputs + [
                                'slider_image' => $featured_image,
                                'created_by' => \Auth::User()->id,
                                ];
            (new Offer)->store($inputs);
            $fields = ['id'];
            if ($inputs['status'] == 1) {
                $users = (new User)->where('status',1)->get($fields);
                (new UserDevice)->sendMessage($inputs['message'], $users, $url);
                foreach ($users as $us) {
                    $notification=new Notification;
                    $notification->user_id= $us->id;
                    $notification->message= $inputs['message'];
                    $notification->image= $url;
                    $notification->save();
                }
            }

			return apiResponseApp(true, 200, lang('Offer created successfully'));

    	}catch(Exception $e){
			return apiResponseApp(false, 500, lang('messages.server_error'));
    	}
    }

    //  public function sendNotification(Request $request){
    //     try{
    //         (new UserDevice)->sendMessageSingle('20% OFF on electronics. Grab the deal now (Limited stock and available for 1 hour only).',\Auth::User()->id);
    //             $notification=new Notification;
    //             $notification->user_id=\Auth::user()->id;
    //             $notification->message='20% OFF on electronics. Grab the deal now (Limited stock and available for 1 hour only).';
    //                 $notification->save();
    //         return apiResponseApp(true, 200, 'saved');
    //     }catch(Exception $e){
    //         return apiResponseApp(false, 500, lang('messages.server_error').$exception->getMessage());
    //     }
    // }

    //All Offers
    public function allOffers(){
    	try{
    		$data = Offer::orderBy('created_at', 'desc')->get();
    		if (count($data) != 0) {
				return apiResponseApp(true, 200, null, null, $data);
    		}else{
				return apiResponseApp(false, 400, lang('No Offer Type exist in our records'));
    		}
    	}catch(Exception $e){
			return apiResponseApp(false, 500, lang('messages.server_error'));
    	}
    }

    //All Offers
    public function allCurrentOffers(){
    	try{
            $data = [];
    		$data['all_offers'] = Offer::whereDate('valid_from', '<=', date('Y-m-d'))->whereDate('valid_to', '>=', date('Y-m-d'))->where('status', 1)->orderBy('created_at', 'desc')->get();
            $data['brand_offers'] = Offer::whereDate('valid_from', '<=', date('Y-m-d'))->whereDate('valid_to', '>=', date('Y-m-d'))->where('brand_id', '!=', null)->where('status', 1)->orderBy('created_at', 'desc')->get();
            foreach ($data['brand_offers'] as $brands) {
                $brands['brand_detail'] = Brand::where('id', $brands->brand_id)->first();
            }
            $data['category_offers'] = Offer::whereDate('valid_from', '<=', date('Y-m-d'))->whereDate('valid_to', '>=', date('Y-m-d'))->where('category_id', '!=', null)->where('status', 1)->orderBy('created_at', 'desc')->get();
            foreach ($data['category_offers'] as $category) {
                $category['category_detail'] = Category::where('id', $category->category_id)->first();
            }
    		if (count($data) != 0) {
				return apiResponseApp(true, 200, null, null, $data);
    		}else{
				return apiResponseApp(false, 400, lang('No Offer Type exist in our records'));
    		}
    	}catch(Exception $e){
			return apiResponseApp(false, 500, lang('messages.server_error'));
    	}
    }

    //All Offers
    public function allCurrentOffersProduct(Request $request){
        try{
            $inputs = $request->all();
            $validator = ( new Offer )->validateProduct( $inputs );
            if( $validator->fails() ) {
                return apiResponseApp(false, 406, "", errorMessages($validator->messages()));
            }
            $data = Offer::where('product_id', $inputs['product_id'])->whereDate('valid_from', '<=', date('Y-m-d'))->whereDate('valid_to', '>=', date('Y-m-d'))->where('status', 1)->orderBy('created_at', 'desc')->get();
            if (count($data) != 0) {
                return apiResponseApp(true, 200, null, null, $data);
            }else{
                $category_id = Product::where('id', $inputs['product_id'])->value('category_id');
                 $data = Offer::where('category_id', $category_id)->whereDate('valid_from', '<=', date('Y-m-d'))->whereDate('valid_to', '>=', date('Y-m-d'))->where('status', 1)->orderBy('created_at', 'desc')->get();
                 if (count($data) != 0) {
                return apiResponseApp(true, 200, null, null, $data);
                     }else{
                return apiResponseApp(false, 400, lang('No Offer Type exist in our records'));
                     }
            }
        }catch(Exception $e){
            return apiResponseApp(false, 500, lang('messages.server_error'));
        }
    }

     //All Offers
    public function offerDetail(Request $request){
        try{
            $inputs = $request->all();
            $validator = ( new Offer )->validateOffer( $inputs );
            if( $validator->fails() ) {
                return apiResponseApp(false, 406, "", errorMessages($validator->messages()));
            }
            $data = Offer::where('id', $inputs['offer_id'])->whereDate('valid_from', '<=', date('Y-m-d'))->whereDate('valid_to', '>=', date('Y-m-d'))->orderBy('created_at', 'desc')->first();
            $used = Order::where('user_id',\Auth::User()->id)->where('offer_id', $inputs['offer_id'])->count();
            $max_used = Order::where('offer_id', $inputs['offer_id'])->count();
            $data['used'] = $used;
            $data['max_used'] = $max_used;

            if ($used == $data['per_user']) {
                return apiResponseApp(false, 200, 'Not Applicable', null, $data);
            }else if($max_used == $data['max_user']){
                return apiResponseApp(false, 200, 'Not Applicable', null, $data);
            }else if($data['type_id'] == 1){
                $cart = Cart::where('user_id', \Auth::User()->id)->get();
                $total = 0;
                foreach ($cart as $products) {                    
                    $products['product'] = Product::where('id', $products->product_id)->first();
                    $total += $products['product']->sale_price * $products->quantity;   
                }

                if ($total >= $data['min_amount']) {
                    return apiResponseApp(true, 200, null, null, $data);
                }else{
                    return apiResponseApp(false, 200, 'Not Applicable', null, $data);
                }


            }else if($data['type_id'] == 2){
                $cart = Cart::where('user_id', \Auth::User()->id)->get();
                $total = 0;
                foreach ($cart as $products) {
                    $products['product'] = Product::where('id', $products->product_id)->first();
                    $total += $products['product']->sale_price * $products->quantity;   
                }

                if ($total >= $data['min_amount']) {
                    return apiResponseApp(true, 200, null, null, $data);
                }else{
                    return apiResponseApp(false, 200, 'Not Applicable', null, $data);
                }

            }else if($data['type_id'] == 3){
                $cart = Cart::where('user_id', \Auth::User()->id)->get();
                $total = 0;
                foreach ($cart as $products) {
                    $products['product'] = Product::where('id', $products->product_id)->first();
                    if ($products->product_id == $data['product_id']) {
                        $total += $products['product']->sale_price * $products->quantity;   
                    }
                }

                if ($total > 0) {
                    return apiResponseApp(true, 200, null, null, $data);
                }else{
                    return apiResponseApp(false, 200, 'Not Applicable', null, $data);
                }

            }else if($data['type_id'] == 4){
                $cart = Cart::where('user_id', \Auth::User()->id)->get();
                $total = 0;
                foreach ($cart as $products) {
                    $products['product'] = Product::where('id', $products->product_id)->first();
                    $category_id = Product::where('id', $data['product_id'])->value('category_id');
                    if ($category_id == $data['category_id']) {
                        $total += $products['product']->sale_price * $products->quantity;   
                    }
                }

                if ($total > 0) {
                    return apiResponseApp(true, 200, null, null, $data);
                }else{
                    return apiResponseApp(false, 200, 'Not Applicable', null, $data);
                }

            }else if($data['type_id'] == 6){
                $cart = Cart::where('user_id', \Auth::User()->id)->get();
                $total = 0;
                foreach ($cart as $products) {
                    if ($products->product_id == $data['product_id']) {
                        $quantity = Product::where('id', $data['sub_product'])->value('quantity');

                        if ($quantity != 0) {
                            $inputs = $inputs + [
                                        'user_id' => \Auth::User()->id,
                                        'product_id' => $data['sub_product'],
                                        'quantity' => 1,
                                        'created_by' => \Auth::User()->id
                            ];
                            (new Cart)->store($inputs);
                            return apiResponseApp(true, 200, null, null, $data);
                        }else{
                            return apiResponseApp(false, 200, 'Not Applicable', null, $data);
                        }   
                    }
                }
            }else if($data['type_id'] == 7){
                $cart = Cart::where('user_id', \Auth::User()->id)->get();
                $total = 0;
                foreach ($cart as $products) {
                    $products['product'] = Product::where('id', $products->product_id)->first();
                    $brand_id = Product::where('id', $data['product_id'])->value('brand_id');
                    if ($brand_id == $data['brand_id']) {
                        $total += $products['product']->sale_price * $products->quantity;   
                    }
                }

                if ($total > 0) {
                    return apiResponseApp(true, 200, null, null, $data);
                }else{
                    return apiResponseApp(false, 200, 'Not Applicable', null, $data);
                }
            }else{
                return apiResponseApp(false, 200, 'Not Applicable', null, $data);
            }

            
            
        }catch(Exception $e){
            return apiResponseApp(false, 500, lang('messages.server_error'));
        }
    }

      //All Offers
    public function offerDetailGuest(Request $request){
        try{
            $inputs = $request->all();
            $validator = ( new Offer )->validateOffer( $inputs );
            if( $validator->fails() ) {
                return apiResponseApp(false, 406, "", errorMessages($validator->messages()));
            }
            $data = Offer::where('id', $inputs['offer_id'])->whereDate('valid_from', '<=', date('Y-m-d'))->whereDate('valid_to', '>=', date('Y-m-d'))->orderBy('created_at', 'desc')->first();
            $data['used'] = 0;
            $data['max_used'] = 0;
            
            return apiResponseApp(true, 200, null, null, $data);
        }catch(Exception $e){
            return apiResponseApp(false, 500, lang('messages.server_error'));
        }
    }

      //All Offers
    public function checkPromoCode(Request $request){
        try{
            $inputs = $request->all();
            $validator = ( new Offer )->validatePromoCode( $inputs );
            if( $validator->fails() ) {
                return apiResponseApp(false, 406, "", errorMessages($validator->messages()));
            }
            $data = Offer::where('promo_code', $inputs['promo_code'])->whereDate('valid_from', '<=', date('Y-m-d'))->whereDate('valid_to', '>=', date('Y-m-d'))->orderBy('created_at', 'desc')->first();
            $used = Order::where('user_id', \Auth::User()->id)->where('offer_id', $data['id'])->count();
            $max_used = Order::where('offer_id', $data['id'])->count();

            if (isset($data)) {
                $data['used'] = $used;
                $data['max_used'] = $max_used;
                
                if ($used == $data['per_user']) {
                    return apiResponseApp(false, 200, 'Not Applicable', null, $data);
                }else if($max_used == $data['max_user']){
                    return apiResponseApp(false, 200, 'Not Applicable', null, $data);
                }else if($data['type_id'] == 1){
                    $cart = Cart::where('user_id', \Auth::User()->id)->get();
                    $total = 0;
                    foreach ($cart as $products) {                    
                        $products['product'] = Product::where('id', $products->product_id)->first();
                        $total += $products['product']->sale_price * $products->quantity;   
                    }

                    if ($total >= $data['min_amount']) {
                        return apiResponseApp(true, 200, null, null, $data);
                    }else{
                        return apiResponseApp(false, 200, 'Not Applicable', null, $data);
                    }


                }else if($data['type_id'] == 2){
                    $cart = Cart::where('user_id', \Auth::User()->id)->get();
                    $total = 0;
                    foreach ($cart as $products) {
                        $products['product'] = Product::where('id', $products->product_id)->first();
                        $total += $products['product']->sale_price * $products->quantity;   
                    }

                    if ($total >= $data['min_amount']) {
                        return apiResponseApp(true, 200, null, null, $data);
                    }else{
                        return apiResponseApp(false, 200, 'Not Applicable', null, $data);
                    }

                }else if($data['type_id'] == 3){
                    $cart = Cart::where('user_id', \Auth::User()->id)->get();
                    $total = 0;
                    foreach ($cart as $products) {
                        $products['product'] = Product::where('id', $products->product_id)->first();
                        if ($products->product_id == $data['product_id']) {
                            $total += $products['product']->sale_price * $products->quantity;   
                        }
                    }

                    if ($total >= $data['min_amount']) {
                        return apiResponseApp(true, 200, null, null, $data);
                    }else{
                        return apiResponseApp(false, 200, 'Not Applicable', null, $data);
                    }

                }else if($data['type_id'] == 4){
                    $cart = Cart::where('user_id', \Auth::User()->id)->get();
                    $total = 0;
                    foreach ($cart as $products) {
                        $products['product'] = Product::where('id', $products->product_id)->first();
                        $category_id = Product::where('id', $data['product_id'])->value('category_id');
                        if ($category_id == $data['category_id']) {
                            $total += $products['product']->sale_price * $products->quantity;   
                        }
                    }

                    if ($total >= $data['min_amount']) {
                        return apiResponseApp(true, 200, null, null, $data);
                    }else{
                        return apiResponseApp(false, 200, 'Not Applicable', null, $data);
                    }

                }else if($data['type_id'] == 6){
                    $cart = Cart::where('user_id', \Auth::User()->id)->get();
                    $total = 0;
                    foreach ($cart as $products) {
                        if ($products->product_id == $data['product_id']) {
                            $quantity = Product::where('id', $data['sub_product'])->value('quantity');

                            if ($quantity != 0) {
                                $inputs = $inputs + [
                                            'user_id' => \Auth::User()->id,
                                            'product_id' => $data['sub_product'],
                                            'quantity' => 1,
                                            'created_by' => \Auth::User()->id
                                ];
                                (new Cart)->store($inputs);
                                return apiResponseApp(true, 200, null, null, $data);
                            }else{
                                return apiResponseApp(false, 200, 'Not Applicable', null, $data);
                            }   
                        }
                    }
                }else if($data['type_id'] == 7){
                    $cart = Cart::where('user_id', \Auth::User()->id)->get();
                    $total = 0;
                    foreach ($cart as $products) {
                        $products['product'] = Product::where('id', $products->product_id)->first();
                        $brand_id = Product::where('id', $data['product_id'])->value('brand_id');
                        if ($brand_id == $data['brand_idt ']) {
                            $total += $products['product']->sale_price * $products->quantity;   
                        }
                    }

                    if ($total >= $data['min_amount']) {
                        return apiResponseApp(true, 200, null, null, $data);
                    }else{
                        return apiResponseApp(false, 200, 'Not Applicable', null, $data);
                    }
                }else{
                    return apiResponseApp(false, 200, 'Not Applicable', null, $data);
                }
                }else{
                    return apiResponseApp(false,400, 'Promo code is invalid');
                }
            
        }catch(Exception $e){
            return apiResponseApp(false, 500, lang('messages.server_error'));
        }
    }

         //All Offers
    public function checkPromoCodeGuest(Request $request){
        try{
            $inputs = $request->all();
            $validator = ( new Offer )->validatePromoCode( $inputs );
            if( $validator->fails() ) {
                return apiResponseApp(false, 406, "", errorMessages($validator->messages()));
            }
            $data = Offer::where('promo_code', $inputs['promo_code'])->whereDate('valid_from', '<=', date('Y-m-d'))->whereDate('valid_to', '>=', date('Y-m-d'))->orderBy('created_at', 'desc')->first();

            if (isset($data)) {
                $data['used'] = 0;
                $data['max_used'] = 0;
                return apiResponseApp(true, 200, null, null, $data);
            }else{
                return apiResponseApp(false, 400, 'Promo code is invalid');
            }
            
        }catch(Exception $e){
            return apiResponseApp(false, 500, lang('messages.server_error'));
        }
    }

    //All Offers
    public function allOffersAdmin(){
        try{
            $data = Offer::orderBy('created_at', 'desc')->get();
            if (count($data) != 0) {
                foreach ($data as $d) {
                    $d['used'] = Order::where('offer_id', $d->id)->count();
                    $d['max_used'] = Order::where('offer_id', $d->id)->count();
                }
                return apiResponseApp(true, 200, null, null, $data);
            }else{
                return apiResponseApp(false, 400, lang('No Offer Type exist in our records'));
            }
        }catch(Exception $e){
            return apiResponseApp(false, 500, lang('messages.server_error'));
        }
    }

}
