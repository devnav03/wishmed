<?php

namespace App\Http\Controllers\Frontend;
/**
 * :: Brand Controller ::
 * To manage homepage.
 *
 **/

Use Mail;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\User;
use App\Models\DefaultAddress;
use App\Models\Cart;
use App\Models\Offer;
use App\Models\BillingAddress;
use App\Models\WalletTrajection;
use App\Models\DealerTrajections;
use App\Models\States;
use App\Models\ShippingZone;
use App\Models\Wishlist;
use App\Models\UserAddress;
use App\Models\TaxAmount;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use Ixudra\Curl\Facades\Curl;
use PDF;
use Razorpay\Api\Api;
use Auth;
use Files;
use Session;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller{
    
    public function log_in(Request $request){

       return view('frontend.pages.signin');
    }

    public function deliveryAddress(Request $request){

    try{
     $sale_price = 0;
     $list_price = 0;
     $tax = 0;
     $discount = 0;
     $user_add = "";
     $user_id =  Auth::id();
   
    if($request->address){
      \Session::start();
      \Session::put('user_add', $request->address);
      $user_add = \Session::get('user_add');
    }

     $user_address = UserAddress::where('user_id', $user_id)->where('id', $user_add)->first();

     if($user_address == null){
        return redirect()->route('manage-address');
      }
     

      $session_id = $_SERVER['HTTP_USER_AGENT'];
      if(Auth::id()){
      $cart_items = Cart::where('user_id', $user_id)->get();
      $cart_products = \DB::table('products')
        ->join('carts', 'carts.product_id', '=','products.id')
        ->select('products.name','products.thumbnail', 'products.offer_price', 'carts.quantity', 'products.regular_price', 'carts.id as c_id', 'products.id as pid', 'products.url', 'carts.id as c_id')
        ->where('products.status', 1)
        ->where('carts.user_id', $user_id)
        ->get();
      } else {
        $cart_items = Cart::where('session_id', $session_id)->get();
        $cart_products = \DB::table('products')
        ->join('carts', 'carts.product_id', '=','products.id')
        ->select('products.name','products.thumbnail', 'products.offer_price', 'carts.quantity', 'products.regular_price', 'carts.id as c_id', 'products.id as pid', 'products.url')
        ->where('products.status', 1)
        ->where('carts.session_id', $session_id)
        ->get();
      }

 //dd($cart_products);

    $today = date('Y-m-d');

    $offers = Offer::where('valid_from', '<=', $today)->where('valid_to', '>=', $today)->where('status', 1)->where('type_id', '!=', 1)->where('type_id', '!=', 2)->get();
    if($offers){
      $get_one = []; 
      $product_based = []; 
      $categories_based = []; 
      $brand_based = []; 
      foreach ($offers as $key => $offer) {
        if($offer->type_id == 3){
           $product_based[] = \DB::table('products')
          ->join('offers', 'offers.product_id', '=','products.id')
          ->select('products.name','products.featured_image', 'offers.title', 'offers.off_amount', 'offers.off_percentage', 'offers.discount_type', 'offers.id', 'products.url')
          ->where('offers.id', $offer->id)
          ->where('products.status', 1)
          ->first();
        }
         else if($offer->type_id == 4){
           $categories_based[] = \DB::table('categories')
          ->join('offers', 'offers.category_id', '=','categories.id')
          ->select('categories.name', 'offers.title', 'offers.off_amount', 'offers.off_percentage', 'offers.discount_type', 'offers.id', 'categories.url')
          ->where('offers.id', $offer->id)
          ->where('categories.status', 1)
          ->first();
        }
       else  if($offer->type_id == 6){
           $get_one[] = \DB::table('products')
          ->join('offers', 'offers.product_id', '=','products.id')
          ->select('products.name','products.featured_image', 'offers.title', 'offers.sub_product', 'offers.id', 'offers.product_id', 'products.url')
          ->where('offers.id', $offer->id)
          ->where('products.status', 1)
          ->first();
        }
        else{
           $brand_based[] = \DB::table('brands')
          ->join('offers', 'offers.brand_id', '=','brands.id')
          ->select('brands.name','brands.logo', 'offers.title', 'offers.off_amount', 'offers.off_percentage', 'offers.discount_type', 'offers.id', 'brands.url')
          ->where('offers.id', $offer->id)
          ->where('brands.status', 1)
          ->first();
        }
      }
    }
    
   // dd($shipping_charge);
 
    $offer_based = \Session::get('offer_based');
    if($offer_based){
    $discount_type = \Session::get('discount_type');
    $off_percentage = \Session::get('off_percentage');
    $off_amount = \Session::get('off_amount');
    $min_amount = \Session::get('min_amount');
    $max_discount = \Session::get('max_discount');
    $sub_product = \Session::get('sub_product');
    $product_id = \Session::get('product_id');
    $offer_id = \Session::get('offer_id');
    if($offer_based == 'category'){
    $cat_id = \Session::get('cat_id');
     $discount += get_cat_discount($offer_id);
    if(get_cat_minimum($offer_id)<$min_amount) {
     $discount = 0;
    } else {
     if($discount>$max_discount){
     $discount = $max_discount;
   }
  }
  }  
  if($offer_based == 'brand'){
    $cat_id = \Session::get('brand_id');
     $discount += get_brand_discount($offer_id);
    if(get_brand_minimum($offer_id)<$min_amount) {
     $discount = 0;
    } else {
     if($discount>$max_discount){
     $discount = $max_discount;
   }
   }
  } 
  if($offer_based == 'Percentage'){
    $discount += get_percentage_discount($offer_id);
    if(get_price_minimum($offer_id)<$min_amount) {
      $discount = 0;
    } else {
    if($discount>$max_discount){
      $discount = $max_discount;
    }
    }
  }
  if($offer_based == 'Price'){
       $discount += $off_amount;
      if(get_price_minimum($offer_id)<$min_amount) {
       $discount = 0;
      } 
  }
  if($offer_based == 'product'){
    $cat_id = \Session::get('brand_id');
     $discount += get_product_discount($offer_id);

    if(get_product_minimum($offer_id)<$min_amount) {
     $discount = 0;
    } else {
     if($discount>$max_discount){
     $discount = $max_discount;
   }
   }
  }
  }



$states = ShippingZone::where('status', 1)->select('id', 'name')->get();

$TaxAmount = TaxAmount::where('id', 1)->select('product_tax', 'shipping_tax')->first();
$product_tax = $TaxAmount->product_tax;
$shipping_tax = $TaxAmount->shipping_tax;

  $shipping_price = 0;  
      $shipping_type = ''; 
      $tax_status = 0;

      $shipping_tax_price = 0;
      $shipform = '';
      $shipformtype = 0;

      if($user_address->ship_different_address == 1){
        $shipping_state = $user_address->shipping_state;
        $shipping_pincode = $user_address->shipping_postcode;
      } else {
        $shipping_state = $user_address->billing_state;
        $shipping_pincode = $user_address->billing_postcode;
      }
      
      $shipping = ShippingZone::where('id', $shipping_state)->first();

      if($shipping->pincodes){
        $pincodes = explode(',', $shipping->pincodes);
        if(in_array($shipping_pincode, $pincodes)){

          $shipform = "<ul id='shipping_method' style='list-style: none; padding-left: 0px; margin-bottom: 0px; margin-top: 5px;'>
                  <li style='font-weight: normal; font-size: 15px;'>
                  <label><input onchange='changeShip()' type='radio' style='margin-right: 5px; width: 16px; height: 16px; margin-top: 3px; float: left;' name='shipping_method' value='local_pickup' checked='checked'>Pick Up from Blacktown NSW Address: $0</label> </li>
                  <li style='font-weight: normal; font-size: 15px;'>
                  <label><input type='radio' onchange='changeShip1()' style='margin-right: 5px; width: 16px; height: 16px; margin-top: 3px; float: left;' name='shipping_method' value='flat_rate'>Delivery: <span><bdi><span>$</span>".number_format($shipping->delivery_price, 2)."</bdi></span></label></li></ul>";
          $shipformtype = 1;

          $shipping_method = 'local_pickup';

        } else {

          if($shipping->flat_rate == 1){
            $shipping_price = $shipping->flat_price;
            $shipping_type = 'Flat rate:'; 
            $tax_status = $shipping->flat_tax;
            $shipping_method = 'flat_rate';

          $shipform = '<span style="float: right;font-weight: 500; color: #000;" id="total_shipping"> <span style="font-weight: 400; color: #888;" id="shipping_type">Flat rate:</span> $<span id="shipping_price">'.number_format($shipping->flat_price, 2).'</span></span>';

          } elseif ($shipping->local_pickup == 1) {
            
            $shipping_price = $shipping->local_price;
            $shipping_type = 'Local pickup:';
            $tax_status = $shipping->local_tax;
            $shipping_method = 'local_pickup';

            $shipform = '<span style="float: right;font-weight: 500; color: #000;" id="total_shipping"> <span style="font-weight: 400; color: #888;" id="shipping_type">Local pickup:</span> $<span id="shipping_price">'.number_format($shipping->local_price, 2).'</span></span>';

          } else {

            $shipping_price = $shipping->delivery_price;
            $shipping_type = 'Delivery:';
            $tax_status = $shipping->delivery_tax;
            $shipping_method = 'delivery';

            $shipform = '<span style="float: right;font-weight: 500; color: #000;" id="total_shipping"> <span style="font-weight: 400; color: #888;" id="shipping_type">Delivery:</span> $<span id="shipping_price">'.number_format($shipping->delivery_price, 2).'</span></span>';

          }

        }

      } else {

        if($shipping->flat_rate == 1){
          $shipping_price = $shipping->flat_price;
          $shipping_type = 'Flat rate:'; 
          $tax_status = $shipping->flat_tax;
          $shipping_method = 'flat_rate';

          $shipform = '<span style="float: right;font-weight: 500; color: #000;" id="total_shipping"> <span style="font-weight: 400; color: #888;" id="shipping_type">Flat rate:</span> $<span id="shipping_price">'.number_format($shipping->flat_price, 2).'</span></span>';

        } elseif ($shipping->delivery == 1) {
       
          $shipping_price = $shipping->delivery_price;
          $shipping_type = 'Delivery:';
          $tax_status = $shipping->delivery_tax;
          $shipping_method = 'delivery';

          $shipform = '<span style="float: right;font-weight: 500; color: #000;" id="total_shipping"> <span style="font-weight: 400; color: #888;" id="shipping_type">Delivery:</span> $<span id="shipping_price">'.number_format($shipping->delivery_price, 2).'</span></span>';

        } else {

          $shipping_price = $shipping->local_price;
          $shipping_type = 'Local pickup:';
          $tax_status = $shipping->local_tax;

          $shipping_method = 'local_pickup';
          
          $shipform = '<span style="float: right;font-weight: 500; color: #000;" id="total_shipping"> <span style="font-weight: 400; color: #888;" id="shipping_type">Local pickup:</span> $<span id="shipping_price">'.number_format($shipping->local_price, 2).'</span></span>';
        }
      }

    if($tax_status == 1){
        $shipping_tax_price = ($shipping_price/100)*$shipping_tax;
    } 

    $total_price = $sale_price + $shipping_price + $shipping_tax_price;

    // $data['shipping_price'] = $shipping_price;
    // $data['shipping_type']  = $shipping_type;
    // $data['shipping_tax_price']  = $shipping_tax_price;
    // $data['shipform']  = $shipform;
    // $data['shipformtype']  = $shipformtype;
    // $data['total_price']  = number_format($total_price, 2);


//dd($datas);
return view('frontend.pages.checkout', compact('cart_products', 'offers', 'get_one', 'product_based', 'categories_based', 'brand_based', 'user_address', 'states', 'product_tax', 'shipping_tax', 'shipformtype', 'shipform', 'shipping_method', 'shipping_price'));
       } catch(\Exception $exception){
          dd($exception);
            return back();
        }
    }



  public function Wishlist() {

try {
      $user_id =  Auth::id();
      $wishlist_products = \DB::table('products')
        ->join('wishlists', 'wishlists.product_id', '=','products.id')
        ->select('products.thumbnail', 'products.name', 'products.id', 'products.url',  'products.quantity', 'products.regular_price', 'products.offer_price','wishlists.created_at as added_from', 'wishlists.id as w_id')
        ->where('products.status', 1)
        ->where('wishlists.user_id', $user_id)
        ->get();

        $count = \DB::table('products')
        ->join('wishlists', 'wishlists.product_id', '=','products.id')
        ->select('products.id')
        ->where('products.status', 1)
        ->where('wishlists.user_id', $user_id)
        ->count();


     return view('frontend.pages.wishlist', compact('wishlist_products', 'count'));
    }
    catch (\Exception $exception) {
       //dd($exception);
            return back();
        }

    }

    function delAddress($id)
    {
       try {
          \DB::table('user_addresses')->where('id', $id)->delete();
        return back();

      } catch(\Exception $exc){
        
      //  dd($exc);
        return back();
      }

    }


     function delBilAddress($id)
    {
       try {
          \DB::table('billing_addresses')->where('id', $id)->delete();
        return back();

      } catch(\Exception $exc){
        
      //  dd($exc);
        return back();
      }

    }

    

  public function addWishlist($id = null)
  {
       try{
            $user_id =  Auth::id();
            $find = Wishlist::where('user_id', $user_id)->where('product_id', $id)->first();
            if(empty($find)){
                Wishlist::create([
                    'user_id'  =>  $user_id,
                    'product_id'    => $id,
                    'created_by' => $user_id,   
                ]);
            }
             return back()->with('wishlist', lang('messages.created', lang('Wishlist')));
       } catch(\Exception $exc){

        //dd($exc);

        return back();
       } 
  }

  public function deleteWishlist($id = null)
  {
       try{
            $user_id =  Auth::id();
            $find = Wishlist::where('user_id', $user_id)->where('product_id', $id)->first();
            if($find){
                \DB::table('wishlists')->where('product_id', $id)->where('user_id', $user_id)->delete();
            }

             return back()->with('wishlist_delete', lang('messages.created', lang('delete')));;
       } catch(\Exception $exc){

      //  dd($exc);

        return back();
       } 
  }

  public function userAddress() {
    try{
      $user_id = Auth::id();
      $request['session_id'] = $_SERVER['HTTP_USER_AGENT'];
      if(Auth::id()){
        $cart = Cart::where('user_id', $user_id)->count();
      } else {
        $cart = Cart::where('session_id', $request['session_id'])->count();
      }
      $user_id =  Auth::id();
      $user_addresses = \DB::table('user_addresses')->where('user_addresses.user_id', $user_id)->orderby('user_addresses.created_at', 'desc')->get();
      $user_addresses_count = \DB::table('user_addresses')->where('user_addresses.user_id', $user_id)->orderby('user_addresses.created_at', 'desc')->count();
      $df_address = DefaultAddress::where('user_id', $user_id)->orderby('id', 'desc')->first();
      $countries = \DB::table('countries')->select('id', 'country_name as name')->get();

      $states = ShippingZone::where('status', 1)->select('id', 'name')->get();

      return view('frontend.pages.user_address', compact('user_addresses', 'df_address', 'user_addresses_count', 'cart', 'countries', 'states'));

    } catch(\Exception $exc){
      //  dd($exc);
       return back();
    } 
  }

  public function saveAddress(Request $request){

    try{

      $user_id =  Auth::id();
      $request['user_id'] = $user_id;
      $inputs = $request->all();
      // $exist = UserAddress::where('user_id', $user_id)->where('name', $request->name)->where('mobile', $request->mobile)->where('address', $request->address)->where('state', $request->state)->where('city', $request->city)->where('pincode', $request->pincode)->first();
      // if($exist){
      // return back()->with('already_address', lang('messages.created', lang('create_address')));
      // } else {

        $validator = (new UserAddress)->validate($inputs);

        if( $validator->fails() ) {
          return back()->withErrors($validator)->withInput();
        }

      (new UserAddress)->store($inputs); 

     return back()->with('create_address', lang('messages.created', lang('create_address')));
     // }

    } catch(\Exception $exc){
           dd($exc);
      return back();

    }


  }

  

   public function saveBillingAddress(Request $request){


    try{

      $user_id =  Auth::id();
      $request['user_id'] = $user_id;
      $inputs = $request->all();

      $exist = BillingAddress::where('user_id', $user_id)->where('name', $request->name)->where('mobile', $request->mobile)->where('address', $request->address)->where('address', $request->address)->where('state', $request->state)->where('city', $request->city)->where('pincode', $request->pincode)->first();

      if($exist){
      
      return back()->with('already_address_billing', lang('messages.created', lang('create_address')));

      } else {
      $validator = (new BillingAddress)->validate($inputs);

        if( $validator->fails() ) {
          return back()->withErrors($validator)->withInput();
      }

      (new BillingAddress)->store($inputs); 

     return back()->with('create_address_bil', lang('messages.created', lang('create_address')));
     }

    } catch(\Exception $exc){
         //  dd($exc);
      return back();

    }


  }


  public function getCity(Request $request)
  {
      $state_id = $request->state_id;

    $locations = Location::where('state_id', $state_id)->get();
      $check =[];
      $subcategoryList='';
      foreach($locations as $key => $loc){
         
     if(in_array($loc->city_name, $check)){

     } else {
      $check[] = $loc->city_name;

      $subcategoryList .= '<option value="' . $loc->city_id . '">'. $loc->city_name .'</option>';
      
     }

      
 }
 return $subcategoryList; 

}



public function billingAddress($id = null)
{
   try {

      $result1 = (new BillingAddress)->find($id);
        if (!$result1) {
            abort(401);
      }

    $locations = Location::where('state_id', $result1->state)->get();

  
      $check =[];
      $city_list=[];
      foreach($locations as $key => $loc){
         
     if(in_array($loc->city_name, $check)){

     } else {
      $check[] = $loc->city_name;

      $city_list[] = $loc;
     }
      
    }
    
     $sale_price = 0;
     $list_price = 0;
     $tax = 0;
     $discount = 0;
     $user_id =  Auth::id();
    
    $session_id = $_SERVER['HTTP_USER_AGENT'];
    if(Auth::id()){
      $cart_items = Cart::where('user_id', $user_id)->get();
    } else {
      $cart_items = Cart::where('session_id', $session_id)->get();
    }
    $cart_products = [];
    if($cart_items){
      foreach ($cart_items as $key => $cart_item) {
          $cart_products[$key] = \DB::table('products')
          ->join('product_lots', 'product_lots.product_id', '=','products.id')
          ->join('carts', 'carts.product_id', '=','product_lots.id')
          ->join('sizes', 'product_lots.size_id', '=','sizes.id')
          ->select('products.name','products.featured_image', 'product_lots.sale_price', 'carts.quantity', 'product_lots.list_price', 'carts.id as c_id', 'products.id as pid', 'products.url', 'sizes.size', 'products.stich_charges as charges')
          ->where('product_lots.id', $cart_item->product_id)
          ->where('products.status', 1)
          ->first();
      }
    }

    $today = date('Y-m-d');

    $offers = Offer::where('valid_from', '<=', $today)->where('valid_to', '>=', $today)->where('status', 1)->where('type_id', '!=', 1)->where('type_id', '!=', 2)->get();
    if($offers){
      $get_one = []; 
      $product_based = []; 
      $categories_based = []; 
      $brand_based = []; 
      foreach ($offers as $key => $offer) {
        if($offer->type_id == 3){
           $product_based[] = \DB::table('products')
          ->join('offers', 'offers.product_id', '=','products.id')
          ->select('products.name','products.featured_image', 'offers.title', 'offers.off_amount', 'offers.off_percentage', 'offers.discount_type', 'offers.id', 'products.url')
          ->where('offers.id', $offer->id)
          ->where('products.status', 1)
          ->first();
        }
         else if($offer->type_id == 4){
           $categories_based[] = \DB::table('categories')
          ->join('offers', 'offers.category_id', '=','categories.id')
          ->select('categories.name', 'offers.title', 'offers.off_amount', 'offers.off_percentage', 'offers.discount_type', 'offers.id', 'categories.url')
          ->where('offers.id', $offer->id)
          ->where('categories.status', 1)
          ->first();
        }
       else  if($offer->type_id == 6){
           $get_one[] = \DB::table('products')
          ->join('offers', 'offers.product_id', '=','products.id')
          ->select('products.name','products.featured_image', 'offers.title', 'offers.sub_product', 'offers.id', 'offers.product_id', 'products.url')
          ->where('offers.id', $offer->id)
          ->where('products.status', 1)
          ->first();
        }
        else{
           $brand_based[] = \DB::table('brands')
          ->join('offers', 'offers.brand_id', '=','brands.id')
          ->select('brands.name','brands.logo', 'offers.title', 'offers.off_amount', 'offers.off_percentage', 'offers.discount_type', 'offers.id', 'brands.url')
          ->where('offers.id', $offer->id)
          ->where('brands.status', 1)
          ->first();
        }
 
      }

    }
      
    $user_address = UserAddress::where('user_id', $user_id)->orderby('created_at', 'desc')->first();
    $user_address_billing = BillingAddress::where('user_id', $user_id)->orderby('created_at', 'desc')->first();

    $shipping_charge = null;
    $cod_available = null;
    if($user_address){
      $shipping_charge = States::where('location_id', $user_address->state)->orderby('created_at', 'desc')->first();
      $cod_available = Location::where('pincode', $user_address->pincode)->first();

    }
   // dd($shipping_charge);
 
   $offer_based = \Session::get('offer_based');
    if($offer_based){
    $discount_type = \Session::get('discount_type');
    $off_percentage = \Session::get('off_percentage');
    $off_amount = \Session::get('off_amount');
    $min_amount = \Session::get('min_amount');
    $max_discount = \Session::get('max_discount');
    $sub_product = \Session::get('sub_product');
    $product_id = \Session::get('product_id');
    $offer_id = \Session::get('offer_id');
    if($offer_based == 'category'){
    $cat_id = \Session::get('cat_id');
     $discount += get_cat_discount($offer_id);
    if(get_cat_minimum($offer_id)<$min_amount) {
     $discount = 0;
    } else {
     if($discount>$max_discount){
     $discount = $max_discount;
   }
  }
  }  
  if($offer_based == 'brand'){
    $cat_id = \Session::get('brand_id');
     $discount += get_brand_discount($offer_id);
    if(get_brand_minimum($offer_id)<$min_amount) {
     $discount = 0;
    } else {
     if($discount>$max_discount){
     $discount = $max_discount;
   }
   }
  } 
  if($offer_based == 'Percentage'){
    $discount += get_percentage_discount($offer_id);
    if(get_price_minimum($offer_id)<$min_amount) {
      $discount = 0;
    } else {
    if($discount>$max_discount){
      $discount = $max_discount;
    }
    }
  }
  if($offer_based == 'Price'){
       $discount += $off_amount;
      if(get_price_minimum($offer_id)<$min_amount) {
       $discount = 0;
      } 
  }
  if($offer_based == 'product'){
    $cat_id = \Session::get('brand_id');
     $discount += get_product_discount($offer_id);

    if(get_product_minimum($offer_id)<$min_amount) {
     $discount = 0;
    } else {
     if($discount>$max_discount){
     $discount = $max_discount;
   }
   }
  }
  }

if(isset($cart_products)) {
  foreach($cart_products as $cart_product){
    if(isset($cart_product->sale_price)){
      if(get_flash_price($cart_product->pid)){
                $s_type = 'flash';
                $sale_discount = get_flash_price($cart_product->pid);
              } else if(get_clearence_price($cart_product->pid)){
                $s_type = 'clearence';
                $sale_discount = get_clearence_price($cart_product->pid);
             } else if(get_happyhour_price($cart_product->pid)){
               $s_type = 'happy_hour';
                $sale_discount = get_happyhour_price($cart_product->pid);
            } else {
            $sale_discount = 0;
            }
      if(\Auth::check()){
        if(\Auth::user()->user_type == 4) {    
          $s_price = $cart_product->sale_price-get_dealer_discount($cart_product->pid)-$sale_discount;
        } else {
          $s_price = $cart_product->sale_price-$sale_discount;
        }
      }else {
          $s_price = $cart_product->sale_price-$sale_discount;
      }
      $sale_price += $s_price*$cart_product->quantity;
      $list_price += $cart_product->list_price*$cart_product->quantity; 
      $tax += get_tax($cart_product->c_id);
    }
  }
}
//dd($shipping_charge->shipping_charges);
if($shipping_charge){
$total_pay_amount =  (int)$shipping_charge->shipping_charges+$sale_price-$discount;
} else {
$total_pay_amount = (int)$sale_price-$discount;  
}

$total_pay_amout = (int)$total_pay_amount;

$my_valance = 0;
$Wallet_valance = User::where('id', $user_id)->first();
if($Wallet_valance->wallet_valance){
  $my_valance =  $Wallet_valance->wallet_valance;
  if($my_valance >= $total_pay_amout){
   $total_pay_amout = 1;
  } else{
    $total_pay_amout = $total_pay_amout-$my_valance;
  }
}
        

//dd($total_pay_amout);

$api = new Api(('rzp_test_NLMxAE0aD0JNfU'), ('8I8jb4XiOdJbS6RqkiT2FjCX'));
$orderData = [
'receipt' => 3456,
'amount' => $total_pay_amout * 100, // 2000 rupees in paise
'currency' => 'INR',
'payment_capture' => 1 // auto capture
];
$razorpayOrder = $api->order->create($orderData);
$razorpayOrderId = $razorpayOrder['id'];
$_SESSION['razorpay_order_id'] = $razorpayOrderId;
$datas = [
"key" => 'rzp_test_NLMxAE0aD0JNfU',
"name" => "Uphaar",
"description" => "",
"image" => asset('assets/frontend/images/logo.png'),
"prefill" => [
"name" => isset(\Auth::User()->name)?\Auth::User()->name:'',
"email" => isset(\Auth::User()->email)?\Auth::User()->email:'',
"contact" => isset(\Auth::User()->mobile)?\Auth::User()->mobile:'',
],
"notes" => [
"address" => "SCO 92-93-94, Sector 17 D, Chandigarh",
"merchant_order_id" => "12312321",
],
"theme" => [
"color" => "#9D1515"
],
"order_id" => $razorpayOrderId,
];


      $user_id =  Auth::id();
      $user_addresses = \DB::table('user_addresses')
        ->join('locations', 'locations.pincode', '=', 'user_addresses.pincode')
        ->select('user_addresses.*', 'locations.city_name', 'locations.state_name')
        ->where('user_addresses.user_id', $user_id)
        ->orderby('user_addresses.created_at', 'desc')
        ->get();

      $user_addresses_billing = \DB::table('billing_addresses')
        ->join('locations', 'locations.pincode', '=', 'billing_addresses.pincode')
        ->select('billing_addresses.*', 'locations.city_name', 'locations.state_name')
        ->where('billing_addresses.user_id', $user_id)
        ->get();
 

      $states = States::where('country_id', 101)->where('status', 1)->get();

      $pincodes = Location::where('city_id', $result1->city)->get();
      $df_address = DefaultAddress::where('user_id', $user_id)->orderby('id', 'desc')->first();

      return view('frontend.pages.checkout', compact('datas', 'cart_products', 'offers', 'get_one', 'product_based', 'categories_based', 'brand_based', 'shipping_charge', 'user_address', 'cod_available', 'my_valance', 'user_addresses', 'states', 'result1', 'city_list', 'pincodes', 'df_address', 'user_addresses_billing', 'user_address_billing'));

   } catch(\exception $exc){
   // dd($exc);

     return back();
   }

}



public function editAddress($id = null) {
   try {

      $result = (new UserAddress)->find($id);
        if (!$result) {
            abort(401);
      }

      $user_id =  Auth::id();
      $request['session_id'] = $_SERVER['HTTP_USER_AGENT'];
       if(Auth::id()){
        $cart = Cart::where('user_id', $user_id)->count();
      } else{
        $cart = Cart::where('session_id', $request['session_id'])->count();
      }
      
      $user_addresses = \DB::table('user_addresses')->where('user_id', $user_id)->orderby('created_at', 'desc')->get();
      $user_addresses_count = \DB::table('user_addresses')->where('user_id', $user_id)->orderby('created_at', 'desc')->count();

      $states = ShippingZone::where('status', 1)->select('id', 'name')->get();

      $df_address = DefaultAddress::where('user_id', $user_id)->orderby('id', 'desc')->first();

      $countries = \DB::table('countries')->select('id', 'country_name as name')->get();

      return view('frontend.pages.user_address', compact('user_addresses', 'states', 'df_address', 'user_addresses_count', 'cart', 'result', 'countries'));

   } catch(\exception $exc){
      //dd($exc);

     return back();
   }
}

public function updateAddress(Request $request){
   try{

      $user_id =  Auth::id();
      $id = $request->id;
      $request['user_id'] =   $user_id;
      $inputs = $request->all();
      // $validator = (new UserAddress)->validate($inputs);
      //   if( $validator->fails() ) {
      //     return back()->withErrors($validator)->withInput();
      // }

      if(isset($request->ship_different_address)){
      } else {
        $inputs['ship_different_address'] = 0;
      }


      (new UserAddress)->store($inputs, $id);

      return redirect()->route('manage-address');

      // return back()->with('cupdate_address', lang('messages.created', lang('create_address')));

   } catch(\exception $exc){

   // dd($exc);

     return back();
   }

}

public function updateBillingAddress(Request $request){

   try{

      $user_id =  Auth::id();
      $id = $request->id;
      $request['user_id'] =   $user_id;
      $inputs = $request->all();
      $validator = (new BillingAddress)->validate($inputs);
        if( $validator->fails() ) {
          return back()->withErrors($validator)->withInput();
      }


       (new BillingAddress)->store($inputs, $id);


      return back()->with('cupdate_address', lang('messages.created', lang('create_address')));

   } catch(\exception $exc){


     return back();
   }

}


  public function walletTransaction(){

    try {

      $user_id =  Auth::id();
      $user = User::where('id', $user_id)->first();
      if($user->user_type == 4){
      $transactions = \DB::table('dealer_trajections')
      ->leftjoin('orders', 'orders.id', '=', 'dealer_trajections.order_id')
      ->select('dealer_trajections.*', 'orders.order_nr')
      ->where('dealer_id', $user_id)
      ->orderBy('id', 'DESC')
      ->paginate(20); 
         
      return view('frontend.pages.wallet-transaction', compact('user', 'transactions')); 
      } else {

       return back();

      }

    } catch(\exception $exc){
      //dd($exc);

     return back();
   }


  }

   public function ExportRecord(request $request){

    try {
      
      $user_id =  Auth::id();
      $inputs = $request->all();
      $validator = (new DealerTrajections)->recordvalidate($inputs);
          if( $validator->fails() ) {
              return back()->withErrors($validator)->withInput();
          }
          // dd($request['to']);
          $to = date('Y-m-d', strtotime($request['to']));
          $from = date('Y-m-d', strtotime($request['from']));
          // dd($to);
        $data['from'] = $from;
        $data['to'] = $to;
        $data['user'] = User::where('id', $user_id)->first();

        if($request->type == 1)  { 
          $data['trajections'] =  \DB::table('dealer_trajections')
            ->leftJoin('orders', 'orders.id', '=', 'dealer_trajections.order_id')
            ->whereRaw('date_format(dealer_trajections.created_at,"%Y-%m-%d")'.">='".$from . "'")
            ->whereRaw('date_format(dealer_trajections.created_at,"%Y-%m-%d")'."<='".$to . "'")
            ->where('dealer_trajections.dealer_id', $user_id)
            ->select('dealer_trajections.created_at as created_at','orders.order_nr as order_nr', 'dealer_trajections.type as type', 'dealer_trajections.amount as amount', 'dealer_trajections.balance as balance')
            ->get();


         $pdf = \PDF::loadView('pdf.trajections', $data);
          return $pdf->download('trajections.pdf'); 
         }
         else {
            $trajections =  \DB::table('dealer_trajections')
            ->leftJoin('orders', 'orders.id', '=', 'dealer_trajections.order_id')
            ->whereRaw('date_format(dealer_trajections.created_at,"%Y-%m-%d")'.">='".$from . "'")
            ->whereRaw('date_format(dealer_trajections.created_at,"%Y-%m-%d")'."<='".$to . "'")
            ->where('dealer_trajections.dealer_id', $user_id)
            ->select('dealer_trajections.created_at as created_at','orders.order_nr as order_nr', 'dealer_trajections.type as type', 'dealer_trajections.amount as amount', 'dealer_trajections.balance as balance')
            ->get();
         
\Excel::create('trajections', function($excel) use($trajections) {
$excel->sheet('dealer', function($sheet) use($trajections) {
    $excelData = [];
    $excelData[] = [
    'Date',
    'Order No.',
    'Type',
    'Amount',
    'Balance',
    ];
    foreach ($trajections as $key => $value) {
    $excelData[] = [
    date("M d", strtotime($value->created_at)),
    $value->order_nr,
    $value->type,
    $value->amount,
    $value->balance
    ]; 
    }
    $sheet->fromArray($excelData, null, 'A1', true, false);
});
})->download('xlsx');
      
    }

        }
        catch (Exception $exception) {
           // dd($exception);
           return back();
        }

      // dd($orders);

  }
  
  public function changePassword()
  {
    return view('frontend.pages.change-password'); 
  }

  public function changePasswordStore(Request $request){
    
    try{
        $inputs = $request->all();
        $validator = (new User)->password_validate($inputs);
          if( $validator->fails() ) {
            return back()->withErrors($validator)->withInput();
        }

        $id =  Auth::id();
        $user = User::where('id', $id)->first();
        $password = \Hash::make($inputs['new_password']);
        $old_password = \Hash::make($inputs['old_password']);

        // dd($user->id);
     
        if (!\Hash::check($request->old_password, $user->password)){
          return back()->with('old_password_not_match', 'old_password_not_match');  

       } else {

             unset($inputs['new_password']);
        $inputs = $inputs + ['password' => $password];
       (new User)->store($inputs, $id);

       return back()->with('password_change', 'password_change');
       }    

    } catch(Exception $exception){

      return back();
    }

  }

   public function forgotPassword()
    {

        return view('frontend.pages.forgot-password');
    }
    public function checkEmail(Request $request)
    {
        try{
            $inputs = $request->all();
            $validator = (new User)->validateForgotPasswordEmail($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }

            $user_detail = User::where('email', $inputs['email'])->first();
            
            if(!empty($user_detail))
            {
                $data['user_id'] = Hashid::encode($user_detail->id);
                $email = $user_detail->email;
                \Mail::send('email.forgot_password', $data, function($message) use($email){
                    $message->from('no-reply@pukacreations.com');
                    $message->to($email);
                    $message->subject('Puka Creations - Forgot Password');
                });
            }
            else{
                return back()->with('failure_email', 'Email not Found.');
            }

            return back()->with('success_forgot', 'Please Check Your Mail');


        } catch (\Exception $exception) {
           // dd($exception);
            return back()
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
    }

    public function newPassword(Request $request)
    {

      try{
        $inputs = $request->all();
        $validator = (new User)->validateConfirmPassword($inputs);
        if( $validator->fails() ) {
            return back()->withErrors($validator)->withInput();
        }
        
        $password = \Hash::make($inputs['password']);

        User::where('id', $inputs['user_id'])->update(['password' => $password]);

        return back()->with('success', 'Password Successfully Changed');
      } catch(Exception $exception){

      return back();
    }


    }
    public function updatePassword($user_id)
    {
        $user_id = Hashid::decode($user_id);

        $user = User::where('id', $user_id)->first();

        return view('frontend.pages.forgot_change_password', compact('user_id', 'user'));
    }


    public function changePasswordForgot(Request $request)
    {
     
      try{
          $inputs = $request->all();

          $validator = (new User)->validate_password_forgot($inputs);
          if( $validator->fails() ) {
              return back()->withErrors($validator)->withInput();
          }
          
          $password = \Hash::make($inputs['new_password']);
          $id = $inputs['user_id'];
          unset($inputs['new_password']);

           $inputs = $inputs + ['password' => $password];
          //dd($inputs);
            (new User)->store($inputs, $id);
           //dd(User::find($id));



          return back()->with('success', 'Password Successfully Changed');
        } catch(Exception $exception){

        return back();
      }

    }

 
    public function returnProducts(){

      try{

        $id =  Auth::id();
        $user = User::where('id', $id)->first();
        
        $return_orders = \DB::table('return_requests')
        ->join('users', 'users.id', '=','return_requests.user_id')
        ->join('orders', 'orders.id', '=','return_requests.order_id')
        ->select('users.name','users.email','users.mobile','return_requests.reason', 'return_requests.status', 'orders.order_nr', 'orders.id as oid')
        ->where('return_requests.store_id', $user->store_id)
        ->where('return_requests.admin_action', 1)
        ->orderBy('return_requests.created_at', 'desc')
        ->paginate(12);

        return view('frontend.pages.return_requests', compact('return_orders'));

      } catch(Exception $exception){
        return back();
      }

    }

    public function Profile(){

      try{

        $id =  Auth::id();
        $user = User::where('id', $id)->first();
        return view('frontend.pages.profile', compact('user'));
      } catch(Exception $exception){
        return back();
      }

    }
    
    public function editProfile(){

      try{

        $id =  Auth::id();
        $user = User::where('id', $id)->first();
        return view('frontend.pages.profile', compact('user'));
      } catch(Exception $exception){
        return back();
      }

    }

    
    public function updateProfile(Request $request){
      try{
        
        $id =  Auth::id();
        $inputs['id'] = $id;
        $inputs = $request->all(); 
        $validator = (new User)->validate_update_profile($inputs, $id);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        } 

       if(isset($inputs['image']) or !empty($inputs['image']))
            {

                $image_name = rand(100000, 999999);
                $fileName = '';

                if($file = $request->hasFile('image')) 
                {
       
                    $file = $request->file('image') ;
                    $img_name = $file->getClientOriginalName();
                    $image_resize = Image::make($file->getRealPath()); 
                    $image_resize->resize(150, 150);

                    $fileName = $image_name.$img_name;
                    $image_resize->save(public_path('/uploads/user_images/' .$fileName));

                  //  dd($fileName);                      
                }

                $fname ='/uploads/user_images/';
                $image = $fname.$fileName;

            }  else{
              $us = User::where('id', $id)->select('profile_image')->first();
              $image = $us->profile_image;
            }

            unset($inputs['image']);


      User::where('id', $id)
           ->update([
          'mobile' =>  $request->mobile,
          'name' => $request->name,
          'date_of_birth' => $request->date_of_birth,
          'profile_image' => $image,
          'gender'  => $request->gender
      ]);

        return back()->with('profile_update', 'profile_update');

      } catch(Exception $exception){

      //  dd($exception);

        return back();

      }

    }

  public function returnAccept($id = null){

    try{

      ReturnRequest::where('order_id', $id)
           ->update([
          'status' =>  1,
      ]);

      $r_id = ReturnRequest::where('order_id', $id)->first();
      
      ReturnProduct::where('return_id', $r_id->id)
           ->update([
          'status' =>  1,
      ]);
         
      return back()->with('return_accept', 'return_accept');     

    } catch(Exception $exception){

      //dd($exception);
        return back();
    }

  }  
    
 
   public function returnRequestDetail($id = null){

    try{

       $return = ReturnRequest::where('order_id', $id)->first();
       $order = Order::where('id', $return->order_id)->first();
        
       $returnProducts = \DB::table('return_products')
        ->join('order_products', 'order_products.product_id', '=', 'return_products.product_id')
        ->join('products', 'products.id', '=', 'return_products.product_id')
        ->select('products.name', 'products.featured_image', 'products.id', 'order_products.quantity', 'order_products.price', 'products.url')
        ->where('return_products.order_id', $id)
        ->where('order_products.order_id', $id)
        ->get();

        $shipping = \DB::table('orders')
          ->join('order_addresses', 'order_addresses.id', '=', 'orders.shipping_id')
          ->select('orders.id','orders.order_nr', 'orders.total_price', 'order_addresses.name', 'order_addresses.address', 'order_addresses.state', 'order_addresses.city', 'order_addresses.pincode', 'orders.shipping_charges', 'orders.user_id')
          ->where('orders.id', $id)
          ->first();

      return view('frontend.pages.request-detail', compact('returnProducts', 'order', 'return', 'shipping'));    

    } catch(Exception $exception){

      dd($exception);
      
        return back();
    }

  } 

  public function returnAmmount(Request $request){

    try{

        $inputs = $request->all();
        $validator = (new Order)->validateReturnOrder($inputs);
        if( $validator->fails() ) {
            return back()->withErrors($validator)->withInput();
        }
        
        $user_id =  Auth::id();

        ReturnRequest::where('order_id', $request->order_id)
             ->update([
            'status' =>  1,
        ]);

        $r_id = ReturnRequest::where('order_id', $request->order_id)->first();
        
        ReturnProduct::where('return_id', $r_id->id)
             ->update([
            'status' =>  1,
        ]);

        $user_detail = User::where('id', $request->user_id)->first();

        $amount = 0;
        if($user_detail->wallet_valance){
          $amount = $user_detail->wallet_valance+$request->amount;
        } else {
         $amount = $request->amount;
        }

        User::where('id', $request->user_id)
             ->update([
            'wallet_valance' =>  $amount,
        ]);

        $WalletTrajection = new WalletTrajection();
        $WalletTrajection->amount = $request->amount;
        $WalletTrajection->type = 'Credit';
        $WalletTrajection->user_id = $request->user_id;
        $WalletTrajection->created_by = $user_id;
        $WalletTrajection->save();
         
      return back()->with('return_accept', 'return_accept');    

    } catch(Exception $exception){

      //dd($exception);
        return back();
    }

  } 




}