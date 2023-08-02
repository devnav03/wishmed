<?php

namespace App\Http\Controllers\Frontend;
/**
 * :: Store Controller ::
 * To manage homepage.
 *
 **/
Use Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Goal;
use App\Models\Cart;
use App\Models\StoreOrderProduct;
use App\Models\Offer;
use App\Models\StoreOrder;
use App\Models\ProductLot;
use App\Models\Order;
use App\Models\Store;
use App\Models\ReturnRequest;
use App\Models\ReturnProduct;
use App\User;
use App\Models\Location;
use App\Models\WalletTrajection;
use App\Models\DealerTrajections;
use App\Models\States;
use App\Models\Wishlist;
use App\Models\UserAddress;
use App\Models\Brand;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use PDF;
use Auth;

class StoreController extends Controller{
    /**
     * Display a listing of resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customerProfile(Request $request)
    {

  try {
      
      $sale_price = 0;
      $list_price = 0;
      $tax = 0;
      $discount = 0;
      $user_id =  Auth::id();
      $inputs = $request->all();
      $validator = (new Store)->validate_customer($inputs);
        if( $validator->fails() ) {
          return back()->withErrors($validator)->withInput();
      }

      $phone = $request->number;
      $customer = Customer::where('mobile_no', $request->number)->first();
      $states = States::where('country_id', 101)->where('status', 1)->get();

      $session_id = $_SERVER['HTTP_USER_AGENT'];
    $cart_items = Cart::where('session_id', $session_id)->get();
    $cart_products = [];
    if($cart_items){
      foreach ($cart_items as $key => $cart_item) {
          $cart_products[$key] = \DB::table('products')
          ->join('product_lots', 'product_lots.product_id', '=','products.id')
          ->join('carts', 'carts.product_id', '=','products.id')
          ->select('products.name','products.featured_image', 'product_lots.sale_price', 'carts.quantity', 'product_lots.list_price', 'carts.id as c_id', 'products.id as pid', 'products.url')
          ->where('products.id', $cart_item->product_id)
          ->where('products.status', 1)
          ->where('products.show_off', '!=', 3)
          ->where('products.show_off', '!=', 0)
          ->where('product_lots.quantity', '!=', 0)
          ->first();
          if(empty($cart_products[$key])){
          $cart_products[$key] = \DB::table('products')
          ->join('product_lots', 'product_lots.product_id', '=','products.id')
          ->join('carts', 'carts.product_id', '=','products.id')
          ->select('products.name','products.featured_image', 'product_lots.sale_price', 'carts.quantity', 'product_lots.list_price', 'carts.id as c_id', 'products.id as pid', 'products.url')
          ->where('products.id', $cart_item->product_id)
          ->where('products.status', 1)
          ->where('products.show_off', '!=', 3)
          ->where('products.show_off', '!=', 0)
          ->orderby('product_lots.created_at', 'desc')
          ->first();
          }

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
          ->where('products.show_off', '!=', 3)
          ->where('products.show_off', '!=', 0)
          ->first();
        }
         else if($offer->type_id == 4){
           $categories_based[] = \DB::table('categories')
          ->join('offers', 'offers.category_id', '=','categories.id')
          ->select('categories.name','categories.image', 'offers.title', 'offers.off_amount', 'offers.off_percentage', 'offers.discount_type', 'offers.id', 'categories.url')
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
          ->where('products.show_off', '!=', 3)
          ->where('products.show_off', '!=', 0)
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

$total_pay_amount =  (int)$sale_price+$tax-$discount;

$total_pay_amout = (int)$total_pay_amount;



     return view('frontend.pages.customer-profile', compact('customer', 'phone', 'states', 'total_pay_amout'));
    }
    catch (\Exception $exception) {
       //dd($exception);
            return back();
        }

    }


 public function storeOrder(Request $request)
    {

  try {

      
      $sale_price = 0;
      $list_price = 0;
      $tax = 0;
      $discount = 0;
      $offer_id = null;
       $session_id = $_SERVER['HTTP_USER_AGENT'];
      $auth_id =  Auth::id();
      $inputs = $request->all();

      $validator = (new Store)->validate_customer_order($inputs);
        if( $validator->fails() ) {
          return back()->withErrors($validator)->withInput();
        //  dd('here');
      }

     if(isset($request->already_id)){
      $user_id = $request->already_id;

      Customer::where('id', $user_id)
            ->update([
            'city' =>  $request->city,
            'state' => $request->state,
            'name' =>  $request->name,
            'email' => $request->email,
            'pincode' => $request->pincode,
            'updated_by' => $auth_id,
      ]);

     } else {
      $city = Location::where('city_id', $request->city)->first();
      $inputs['city'] = $city->city_name;
      $inputs['state'] = $city->state_name;
      $inputs['created_by'] = $auth_id;
      $user_id = (new Customer)->store($inputs); 
    }


    $cart_items = Cart::where('session_id', $session_id)->get();
    $cart_products = [];
    if($cart_items){
      foreach ($cart_items as $key => $cart_item) {
          $cart_products[$key] = \DB::table('products')
          ->join('product_lots', 'product_lots.product_id', '=','products.id')
          ->join('carts', 'carts.product_id', '=','products.id')
          ->select('products.name','products.featured_image', 'product_lots.sale_price', 'carts.quantity', 'product_lots.list_price', 'carts.id as c_id', 'products.id as pid')
          ->where('products.id', $cart_item->product_id)
          ->where('products.status', 1)
          ->where('products.show_off', '!=', 3)
          ->where('products.show_off', '!=', 0)
          ->where('product_lots.quantity', '!=', 0)
          ->first();
          if(empty($cart_products[$key])){
          $cart_products[$key] = \DB::table('products')
          ->join('product_lots', 'product_lots.product_id', '=','products.id')
          ->join('carts', 'carts.product_id', '=','products.id')
          ->select('products.name','products.featured_image', 'product_lots.sale_price', 'carts.quantity', 'product_lots.list_price', 'carts.id as c_id', 'products.id as pid')
          ->where('products.id', $cart_item->product_id)
          ->where('products.status', 1)
          ->where('products.show_off', '!=', 3)
          ->where('products.show_off', '!=', 0)
          ->orderby('product_lots.created_at', 'desc')
          ->first();
          }
      }

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
      $s_price = $cart_product->sale_price-$sale_discount;
      $sale_price += $s_price*$cart_product->quantity;
      $list_price += $cart_product->list_price*$cart_product->quantity; 
      $tax += get_tax($cart_product->c_id);
    }
  }
}
 
    $total_pay_amount =  $sale_price+$tax-$discount;
  
    $input['user_id'] =  $user_id;
    $input['total_price'] = $total_pay_amount;
    $input['tax'] =  $tax;
    $input['discount'] = $discount;
    $input['payment_method'] = $request->payment_method;
    $input['status'] = 0;
    $input['created_by'] = $auth_id;
    $input['current_status'] = 7;
    $input['offer_id'] = $offer_id;
    $input['order_nr'] = $this->generateOrderNR_store();
    $order_id = (new StoreOrder)->store($input); 


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
      $s_price = $cart_product->sale_price-$sale_discount;
      $sale_price += $s_price*$cart_product->quantity;
      $OrderProduct = new StoreOrderProduct();
      $OrderProduct->order_id = $order_id;
      $OrderProduct->product_id = $cart_product->pid;
      $OrderProduct->user_id = $user_id;
      $OrderProduct->quantity = $cart_product->quantity;
      $OrderProduct->price = $s_price;
      $OrderProduct->save();
    }
  }
}


        $free_pro = null;
        if($offer_based){
          $Coupon_Use = new CouponUse();
          $Coupon_Use->user_id = $user_id;
          $Coupon_Use->offer_id = $offer_id;
          $Coupon_Use->use_from = 'web';
          $Coupon_Use->save();
          if($offer_based == 'get_one'){
         
            $Free_Product = new FreeProduct();
            $Free_Product->order_id = $order_id;
            $Free_Product->user_id = $user_id;
            $Free_Product->free_product_id = $sub_product;
            $Free_Product->product_id = $product_id;
            $Free_Product->save();

          $free_pro = Product::where('id', $sub_product)->first(); 

          }

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

        StoreOrder::where('id', $order_id)
            ->update([
            'status' =>  1,
            'current_status' => 5,
        ]);

        $store_user = User::where('id', $auth_id)->first();
        $data['store_address'] = Store::where('id', $store_user->store_id)->first();            

        $current_order = StoreOrder::where('id', $order_id)->first();
        $email = $request->email;
        $data['cart_items'] =  $cart_products;
        $data['tax'] = $tax;
        $data['discount'] = $discount;
        $data['sale_price'] = $sale_price;
        $data['payment_from'] = $request->payment_method;
        $data['current_order'] = $current_order;
        $data['total_pay_amount'] = $total_pay_amount;
        $data['free_pro'] = $free_pro;




        \Mail::send('email.store_order', $data, function($message) use ($email){
          $message->from($email);
          $message->to('kuickfit@thegirafe.in');
          $message->subject('Kuickfit-Engineered Nutrition - New Order');
        });

        \Mail::send('email.store_order', $data, function($message) use ($email){
          $message->from('kuickfit@thegirafe.in');
          $message->to($email);
          $message->subject('Kuickfit-Engineered Nutrition - Order Confirm');
        });

        foreach($cart_products as $del_item){
          if(isset($del_item)){
          $Product_lot = ProductLot::where('product_id', $del_item->pid)->where('quantity', '!=', 0)->first();
          if(empty($Product_lot)){
            $Product_lot = ProductLot::where('product_id', $del_item->pid)->orderBy('created_at', 'desc')->first();
          }
          if($Product_lot->quantity<$del_item->quantity){
            $p_quantity = 0;
          } else {
            $p_quantity = $Product_lot->quantity-$del_item->quantity;
          }

          ProductLot::where('id', $Product_lot->id)
            ->update([
            'quantity' => $p_quantity,
          ]);

           \DB::table('carts')->where('id', $del_item->c_id)->delete();
          }
        }

        //dd(\Session::all());
       // return redirect()->route('home');
        return redirect()->route('order-success');
      }

    catch (\Exception $exception) {
       //dd($exception);
            return back();
        }

    }

  public function generateOrderNR_store()
  {
        $orderObj = StoreOrder::orderBy('created_at', 'desc')->value('order_nr');
        if ($orderObj) {
            $orderNr = $orderObj;
            $removed1char = substr($orderNr, 1);
            $generateOrder_nr = $stpad = '#' . str_pad($removed1char + 1, 8, "0", STR_PAD_LEFT);
        } else {
            $generateOrder_nr = '#' . str_pad(1, 8, "0", STR_PAD_LEFT);
        }
        return $generateOrder_nr;
    }

   public function orderSuccess(){


    return view('frontend.pages.success');
   } 

  public function myCustomers(){

    try {
      
      $auth_id =  Auth::id();

      $customers = Customer::where('created_by', $auth_id)->paginate(15);

    return view('frontend.pages.my-customers', compact('customers'));

  } catch (\Exception $exception) {
       // dd($exception);
            return back();
        }

  }


}