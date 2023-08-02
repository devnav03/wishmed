<?php

namespace App\Http\Controllers\Frontend;
/**
 * :: Brand Controller ::
 * To manage homepage.
 *
 **/
Use Mail;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use App\Models\CouponUse;
use App\Models\Goal;
use App\Models\Brand;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use PDF;

class OfferController extends Controller{
    /**
     * Display a listing of resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getOfferID($id = null)
    {
      try{

           $user_id =  Auth::id();
           $today = date('Y-m-d');

          $offer_id = $id;
          $Offer = Offer::where('id', $offer_id)->where('status', 1)->where('valid_from', '<=', $today)->where('valid_to', '>=', $today)->first();
        if($Offer){
            $offer_use = CouponUse::where('offer_id', $offer_id)->count();
            if($offer_use<$Offer->max_user) {
              $use_user = CouponUse::where('user_id', $user_id)->count();
                if($use_user<$Offer->per_user) {
                 if($Offer->type_id == 4){
                    \Session::start();
                    $already_offer = \Session::get('offer_based');
                    if($already_offer){
                       \Session::forget('offer_based');
                       \Session::forget('cat_id');
                       \Session::forget('name');
                       \Session::forget('offer_id');
                       \Session::forget('brand_id');
                       \Session::forget('product_id');
                       \Session::forget('sub_product');
                       \Session::forget('discount_type');
                       \Session::forget('off_percentage');
                       \Session::forget('off_amount');
                       \Session::forget('min_amount');
                       \Session::forget('max_discount');
                    } 
                    $category_name = Category::where('id', $Offer->category_id)->first();

                    \Session::start();
                    \Session::put('offer_based', 'category');
                    \Session::put('offer_id', $Offer->id);
                    \Session::put('cat_id', $Offer->category_id);
                    \Session::put('name', $category_name->name);
                    \Session::put('discount_type', $Offer->discount_type);
                    \Session::put('off_percentage', $Offer->off_percentage);
                    \Session::put('off_amount', $Offer->off_amount);
                    \Session::put('min_amount', $Offer->min_amount);
                    \Session::put('max_discount', $Offer->max_discount);

                    return back()->with('offer_apply', 'offer_apply');

                 } else if($Offer->type_id == 7){

                    \Session::start();
                    $already_offer = \Session::get('offer_based');
                    if($already_offer){
                       \Session::forget('offer_based');
                       \Session::forget('cat_id');
                       \Session::forget('name');
                       \Session::forget('brand_id');
                       \Session::forget('product_id');
                       \Session::forget('offer_id');
                       \Session::forget('sub_product');
                       \Session::forget('discount_type');
                       \Session::forget('off_percentage');
                       \Session::forget('off_amount');
                       \Session::forget('min_amount');
                       \Session::forget('max_discount');
                    } 

                    $brand_name = Brand::where('id', $Offer->brand_id)->first();

                    \Session::start();
                    \Session::put('offer_based', 'brand');
                    \Session::put('brand_id', $Offer->brand_id);
                    \Session::put('offer_id', $Offer->id);
                    \Session::put('name', $brand_name->name);
                    \Session::put('discount_type', $Offer->discount_type);
                    \Session::put('off_percentage', $Offer->off_percentage);
                    \Session::put('off_amount', $Offer->off_amount);
                    \Session::put('min_amount', $Offer->min_amount);
                    \Session::put('max_discount', $Offer->max_discount);

                    return back()->with('offer_apply', 'offer_apply');

                 } else if($Offer->type_id == 3){

                    \Session::start();
                    $already_offer = \Session::get('offer_based');
                    if($already_offer){
                       \Session::forget('offer_based');
                       \Session::forget('cat_id');
                       \Session::forget('brand_id');
                       \Session::forget('offer_id');
                       \Session::forget('name');
                       \Session::forget('product_id');
                       \Session::forget('sub_product');
                       \Session::forget('discount_type');
                       \Session::forget('off_percentage');
                       \Session::forget('off_amount');
                       \Session::forget('min_amount');
                       \Session::forget('max_discount');
                    } 

                    $product_name = Product::where('id', $Offer->product_id)->first();
                    \Session::start();
                    \Session::put('offer_based', 'product');
                    \Session::put('product_id', $Offer->product_id);
                    \Session::put('offer_id', $Offer->id);
                    \Session::put('name', $product_name->name);
                    \Session::put('discount_type', $Offer->discount_type);
                    \Session::put('off_percentage', $Offer->off_percentage);
                    \Session::put('off_amount', $Offer->off_amount);
                    \Session::put('min_amount', $Offer->min_amount);
                    \Session::put('max_discount', $Offer->max_discount);

                    return back()->with('offer_apply', 'offer_apply');

                 } else {

                    \Session::start();
                    $already_offer = \Session::get('offer_based');
                    if($already_offer){
                       \Session::forget('offer_based');
                       \Session::forget('cat_id');
                       \Session::forget('name');
                       \Session::forget('sub_name');
                       \Session::forget('offer_id');
                       \Session::forget('brand_id');
                       \Session::forget('product_id');
                       \Session::forget('sub_product');
                       \Session::forget('discount_type');
                       \Session::forget('off_percentage');
                       \Session::forget('off_amount');
                       \Session::forget('min_amount');
                       \Session::forget('max_discount');
                    } 

                    $product_name = Product::where('id', $Offer->product_id)->first();
                    $product_sub_name = Product::where('id', $Offer->sub_product)->first();

                    \Session::start();
                    \Session::put('offer_based', 'get_one');
                    \Session::put('product_id', $Offer->product_id);
                    \Session::put('name', $product_name->name);
                    \Session::put('sub_name', $product_sub_name->name);
                    \Session::put('sub_product', $Offer->sub_product);
                    \Session::put('offer_id', $Offer->id);
                    \Session::put('discount_type', $Offer->discount_type);
                    \Session::put('off_percentage', $Offer->off_percentage);
                    \Session::put('off_amount', $Offer->off_amount);
                    \Session::put('min_amount', $Offer->min_amount);
                    \Session::put('max_discount', $Offer->max_discount);

                    return back()->with('offer_apply', 'offer_apply');
                 }


                } else {
                	return back()->with('user_limit', 'You are already used');
                }
            } else {
            
             return back()->with('total_use', 'Coupon already used');
            }

        } else {

           return back()->with('not_valid', lang('messages.created', lang('not_valid')));

          }

      }
      catch(\Exception $exception){

      	//dd($exception);
        return back();
      }

    }
public function couponCode(Request $request)
    {
      try{

          $this->validate($request,[
            'coupon_code' => 'required|max:50',               
          ]); 

          $user_id =  Auth::id();
          $today = date('Y-m-d');
          
          $Offer = Offer::where('promo_code', $request['coupon_code'])->where('status', 1)->where('valid_from', '<=', $today)->where('valid_to', '>=', $today)->first();


        if($Offer){

            $offer_use = CouponUse::where('offer_id', $Offer->id)->count();
            if($offer_use<$Offer->max_user) {
              $use_user = CouponUse::where('user_id', $user_id)->count();
                if($use_user<$Offer->per_user) {
                 if($Offer->type_id == 4){
                    \Session::start();
                    $already_offer = \Session::get('offer_based');
                    if($already_offer){
                       \Session::forget('offer_based');
                       \Session::forget('cat_id');
                       \Session::forget('name');
                       \Session::forget('offer_id');
                       \Session::forget('brand_id');
                       \Session::forget('product_id');
                       \Session::forget('sub_product');
                       \Session::forget('discount_type');
                       \Session::forget('off_percentage');
                       \Session::forget('off_amount');
                       \Session::forget('min_amount');
                       \Session::forget('max_discount');
                    } 
                    $category_name = Category::where('id', $Offer->category_id)->first();

                    \Session::start();
                    \Session::put('offer_based', 'category');
                    \Session::put('offer_id', $Offer->id);
                    \Session::put('cat_id', $Offer->category_id);
                    \Session::put('name', $category_name->name);
                    \Session::put('discount_type', $Offer->discount_type);
                    \Session::put('off_percentage', $Offer->off_percentage);
                    \Session::put('off_amount', $Offer->off_amount);
                    \Session::put('min_amount', $Offer->min_amount);
                    \Session::put('max_discount', $Offer->max_discount);

                    return back()->with('offer_apply', 'offer_apply');

                 } else if($Offer->type_id == 7){

                    \Session::start();
                    $already_offer = \Session::get('offer_based');
                    if($already_offer){
                       \Session::forget('offer_based');
                       \Session::forget('cat_id');
                       \Session::forget('name');
                       \Session::forget('brand_id');
                       \Session::forget('product_id');
                       \Session::forget('offer_id');
                       \Session::forget('sub_product');
                       \Session::forget('discount_type');
                       \Session::forget('off_percentage');
                       \Session::forget('off_amount');
                       \Session::forget('min_amount');
                       \Session::forget('max_discount');
                    } 

                    $brand_name = Brand::where('id', $Offer->brand_id)->first();

                    \Session::start();
                    \Session::put('offer_based', 'brand');
                    \Session::put('brand_id', $Offer->brand_id);
                    \Session::put('offer_id', $Offer->id);
                    \Session::put('name', $brand_name->name);
                    \Session::put('discount_type', $Offer->discount_type);
                    \Session::put('off_percentage', $Offer->off_percentage);
                    \Session::put('off_amount', $Offer->off_amount);
                    \Session::put('min_amount', $Offer->min_amount);
                    \Session::put('max_discount', $Offer->max_discount);

                    return redirect()->route('checkout')->with('offer_apply', 'offer_apply');

                 } else if($Offer->type_id == 3){

                    \Session::start();
                    $already_offer = \Session::get('offer_based');
                    if($already_offer){
                       \Session::forget('offer_based');
                       \Session::forget('cat_id');
                       \Session::forget('brand_id');
                       \Session::forget('offer_id');
                       \Session::forget('name');
                       \Session::forget('product_id');
                       \Session::forget('sub_product');
                       \Session::forget('discount_type');
                       \Session::forget('off_percentage');
                       \Session::forget('off_amount');
                       \Session::forget('min_amount');
                       \Session::forget('max_discount');
                    } 

                    $product_name = Product::where('id', $Offer->product_id)->first();
                    \Session::start();
                    \Session::put('offer_based', 'product');
                    \Session::put('product_id', $Offer->product_id);
                    \Session::put('offer_id', $Offer->id);
                    \Session::put('name', $product_name->name);
                    \Session::put('discount_type', $Offer->discount_type);
                    \Session::put('off_percentage', $Offer->off_percentage);
                    \Session::put('off_amount', $Offer->off_amount);
                    \Session::put('min_amount', $Offer->min_amount);
                    \Session::put('max_discount', $Offer->max_discount);

                    return back()->with('offer_apply', 'offer_apply');

                 } else if($Offer->type_id == 1){


                    \Session::start();
                    $already_offer = \Session::get('offer_based');
                    if($already_offer){
                       \Session::forget('offer_based');
                       \Session::forget('cat_id');
                       \Session::forget('brand_id');
                       \Session::forget('offer_id');
                       \Session::forget('name');
                       \Session::forget('product_id');
                       \Session::forget('sub_product');
                       \Session::forget('discount_type');
                       \Session::forget('off_percentage');
                       \Session::forget('off_amount');
                       \Session::forget('min_amount');
                       \Session::forget('max_discount');
                    } 
                    \Session::start();
                    \Session::put('offer_based', 'Price');
                    \Session::put('offer_id', $Offer->id);
                    \Session::put('off_amount', $Offer->off_amount);
                    \Session::put('min_amount', $Offer->min_amount);

                    return back()->with('offer_apply', 'offer_apply');

                 }  else if($Offer->type_id == 2){

                    \Session::start();
                    $already_offer = \Session::get('offer_based');
                    if($already_offer){
                       \Session::forget('offer_based');
                       \Session::forget('cat_id');
                       \Session::forget('brand_id');
                       \Session::forget('offer_id');
                       \Session::forget('name');
                       \Session::forget('product_id');
                       \Session::forget('sub_product');
                       \Session::forget('discount_type');
                       \Session::forget('off_percentage');
                       \Session::forget('off_amount');
                       \Session::forget('min_amount');
                       \Session::forget('max_discount');
                    } 
                    \Session::start();
                    \Session::put('offer_based', 'Percentage');
                    \Session::put('offer_id', $Offer->id);
                    \Session::put('off_percentage', $Offer->off_amount);
                    \Session::put('min_amount', $Offer->off_percentage);
                    \Session::put('max_discount', $Offer->max_discount);

                    return back()->with('offer_apply', 'offer_apply');

                 } else {

                    \Session::start();
                    $already_offer = \Session::get('offer_based');
                    if($already_offer){
                       \Session::forget('offer_based');
                       \Session::forget('cat_id');
                       \Session::forget('name');
                       \Session::forget('sub_name');
                       \Session::forget('offer_id');
                       \Session::forget('brand_id');
                       \Session::forget('product_id');
                       \Session::forget('sub_product');
                       \Session::forget('discount_type');
                       \Session::forget('off_percentage');
                       \Session::forget('off_amount');
                       \Session::forget('min_amount');
                       \Session::forget('max_discount');
                    } 

                    $product_name = Product::where('id', $Offer->product_id)->first();
                    $product_sub_name = Product::where('id', $Offer->sub_product)->first();

                    \Session::start();
                    \Session::put('offer_based', 'get_one');
                    \Session::put('product_id', $Offer->product_id);
                    \Session::put('name', $product_name->name);
                    \Session::put('sub_name', $product_sub_name->name);
                    \Session::put('sub_product', $Offer->sub_product);
                    \Session::put('offer_id', $Offer->id);
                    \Session::put('discount_type', $Offer->discount_type);
                    \Session::put('off_percentage', $Offer->off_percentage);
                    \Session::put('off_amount', $Offer->off_amount);
                    \Session::put('min_amount', $Offer->min_amount);
                    \Session::put('max_discount', $Offer->max_discount);

                    return back()->with('offer_apply', 'offer_apply');
                 }


                } else {
                  return back()->with('user_limit', 'You are already used');
                }
            } else {
            
             return back()->with('total_use', 'Coupon already used');
            }

        } else {
          

           return back()->with('not_valid', lang('messages.created', lang('not_valid')));

          }

      }
      catch(\Exception $exception){

       dd($exception);
        
        return back();
      }

    }



   
}
