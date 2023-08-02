<?php

namespace App\Http\Controllers\Frontend;


Use Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\User;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Cart;
use App\Models\EmailSetting;
use App\Models\OrderAddress;
use App\Models\FreeProduct;
use App\Models\CouponUse;
use App\Models\OrderBilling;
use App\Models\DefaultAddress;
use App\Models\BillingAddress;
use App\Models\Offer;
use App\Models\UserAddress;
use App\Models\ShippingZone;
use App\Models\Wishlist;
use App\Models\TaxAmount;
use Eway\Rapid;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use PDF;
use Auth;
use Razorpay\Api\Api;
use Session;
use Redirect;
use Illuminate\Support\Facades\Input;

class CheckoutController extends Controller{

    public function CheckoutView(Request $request){
    try{
     $sale_price = 0;
     $list_price = 0;
     $tax = 0;
     $discount = 0;
     $user_add = "";
     $user_id =  Auth::id();

     $user_address = UserAddress::where('user_id', $user_id)->orderby('id', 'DESC')->first();
     if($user_address == null){
        return redirect()->route('manage-address');
      }
     
      $session_id = $_SERVER['HTTP_USER_AGENT'];
      if(Auth::id()){
      $cart_items = Cart::where('user_id', $user_id)->get();
      $cart_products = \DB::table('products')
        ->join('carts', 'carts.product_id', '=','products.id')
        ->select('products.name','products.thumbnail', 'products.offer_price', 'carts.quantity', 'products.regular_price', 'carts.id as c_id', 'products.id as pid', 'products.url')
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
        } else if($offer->type_id == 4){
           $categories_based[] = \DB::table('categories')
          ->join('offers', 'offers.category_id', '=','categories.id')
          ->select('categories.name', 'offers.title', 'offers.off_amount', 'offers.off_percentage', 'offers.discount_type', 'offers.id', 'categories.url')
          ->where('offers.id', $offer->id)
          ->where('categories.status', 1)
          ->first();
        } else  if($offer->type_id == 6){
           $get_one[] = \DB::table('products')
          ->join('offers', 'offers.product_id', '=','products.id')
          ->select('products.name','products.featured_image', 'offers.title', 'offers.sub_product', 'offers.id', 'offers.product_id', 'products.url')
          ->where('offers.id', $offer->id)
          ->where('products.status', 1)
          ->first();
        } else{
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

  }

 
$total_pay_amount = (int)$sale_price-$discount;
$total_pay_amout = (int)$total_pay_amount; 
$last_order = Order::where('user_id', $user_id)->orderBy('id', 'DESC')->first();
$shipping = '';
if($last_order){
  if($last_order->ship_different_address == 1){
      $shipping = ShippingZone::where('id', $last_order->shipping_state)->first();
  }  else {
      $shipping = ShippingZone::where('id', $last_order->billing_state)->first();
  }
      
}

   
$TaxAmount = TaxAmount::where('id', 1)->select('product_tax', 'shipping_tax')->first();

$product_tax = $TaxAmount->product_tax;
$shipping_tax = $TaxAmount->shipping_tax;


$states = ShippingZone::where('status', 1)->select('id', 'name')->get();

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

//dd($datas);
return view('frontend.pages.checkout', compact('cart_products', 'offers', 'get_one', 'product_based', 'categories_based', 'brand_based', 'last_order', 'shipping', 'product_tax', 'shipping_tax', 'states', 'shipformtype', 'shipform', 'shipping_method', 'shipping_price', 'user_address'));
  }  catch(\Exception $exception){
     // dd($exception);
      return back();
    }
}


  public function shipping_calculation(Request $request){

      $user_id = Auth::id();
      $cart_products = \DB::table('products')
        ->join('carts', 'carts.product_id', '=','products.id')
        ->select('products.offer_price', 'carts.quantity', 'products.regular_price', 'carts.id as c_id', 'products.id as pid')
        ->where('products.status', 1)
        ->where('carts.user_id', $user_id)
        ->get();

      $sale_price = 0;

      if(isset($cart_products)) { 
        foreach($cart_products as $cart_product) {
          $sale_price += $cart_product->offer_price*$cart_product->quantity;
        }
      }


      $shipping_price = 0;  
      $shipping_type = ''; 
      $tax_status = 0;

      $shipping_tax_price = 0;
      $shipping_tax = 3;
      $shipform = '';
      $shipformtype = 0;
      
      $shipping = ShippingZone::where('id', $request->zone_id)->first();

      if($shipping->pincodes){
        $pincodes = explode(',', $shipping->pincodes);
        if(in_array($request->pincode, $pincodes)){

          $shipform = "<ul id='shipping_method' style='list-style: none; padding-left: 0px; margin-bottom: 0px; margin-top: 5px;'>
                  <li style='font-weight: normal; font-size: 15px;'>
                  <label><input onchange='changeShip()' type='radio' style='margin-right: 5px; width: 16px; height: 16px; margin-top: 3px; float: left;' name='shipping_method' value='local_pickup' checked='checked'>Pick Up from Blacktown NSW Address: $0</label> </li>
                  <li style='font-weight: normal; font-size: 15px;'>
                  <label><input type='radio' onchange='changeShip1()' style='margin-right: 5px; width: 16px; height: 16px; margin-top: 3px; float: left;' name='shipping_method' value='flat_rate'>Delivery: <span><bdi><span>$</span>".number_format($shipping->delivery_price, 2)."</bdi></span></label></li></ul><input type='hidden' id='shipping_method' name='shipping_method' value='local_pickup'>";
          $shipformtype = 1;

        } else {

          if($shipping->flat_rate == 1){
            $shipping_price = $shipping->flat_price;
            $shipping_type = 'Flat rate:'; 
            $tax_status = $shipping->flat_tax;
            $data['shipping_method'] = 'flat_rate';

          $shipform = '<p style="border-bottom: 1px solid #d6d6d6; padding-bottom: 10px;font-weight: 500; color: #000; ">Shipping <span style="float: right;font-weight: 500; color: #000;" id="total_shipping"> <span style="font-weight: 400; color: #888;" id="shipping_type">Flat rate:</span> $<span id="shipping_price">'.number_format($shipping->flat_price, 2).'</span></span><input type="hidden" id="shipping_method" name="shipping_method" value="flat_rate"></p>';

          } elseif ($shipping->local_pickup == 1) {
            
            $shipping_price = $shipping->local_price;
            $shipping_type = 'Local pickup:';
            $tax_status = $shipping->local_tax;
            $data['shipping_method'] = 'local_pickup';

            $shipform = '<p style="border-bottom: 1px solid #d6d6d6; padding-bottom: 10px;font-weight: 500; color: #000; ">Shipping<span style="float: right;font-weight: 500; color: #000;" id="total_shipping"> <span style="font-weight: 400; color: #888;" id="shipping_type">Local pickup:</span> $<span id="shipping_price">'.number_format($shipping->local_price, 2).'</span></span><input type="hidden" id="shipping_method" name="shipping_method" value="local_pickup"></p>';

          } else {

            $shipping_price = $shipping->delivery_price;
            $shipping_type = 'Delivery:';
            $tax_status = $shipping->delivery_tax;
            $data['shipping_method'] = 'delivery';

            $shipform = '<p style="border-bottom: 1px solid #d6d6d6; padding-bottom: 10px;font-weight: 500; color: #000; ">Shipping<span style="float: right;font-weight: 500; color: #000;" id="total_shipping"> <span style="font-weight: 400; color: #888;" id="shipping_type">Delivery:</span> $<span id="shipping_price">'.number_format($shipping->delivery_price, 2).'</span></span><input type="hidden" id="shipping_method" name="shipping_method" value="delivery"></p>';

          }

        }

      } else {

        if($shipping->flat_rate == 1){
          $shipping_price = $shipping->flat_price;
          $shipping_type = 'Flat rate:'; 
          $tax_status = $shipping->flat_tax;
          $data['shipping_method'] = 'flat_rate';

          $shipform = '<p style="border-bottom: 1px solid #d6d6d6; padding-bottom: 10px;font-weight: 500; color: #000; ">Shipping<span style="float: right;font-weight: 500; color: #000;" id="total_shipping"> <span style="font-weight: 400; color: #888;" id="shipping_type">Flat rate:</span> $<span id="shipping_price">'.number_format($shipping->flat_price, 2).'</span></span><input type="hidden" id="shipping_method" name="shipping_method" value="flat_rate"></p>';

        } elseif ($shipping->delivery == 1) {
       
          $shipping_price = $shipping->delivery_price;
          $shipping_type = 'Delivery:';
          $tax_status = $shipping->delivery_tax;
          $data['shipping_method'] = 'delivery';

          $shipform = '<p style="border-bottom: 1px solid #d6d6d6; padding-bottom: 10px;font-weight: 500; color: #000; ">Shipping<span style="float: right;font-weight: 500; color: #000;" id="total_shipping"> <span style="font-weight: 400; color: #888;" id="shipping_type">Delivery:</span> $<span id="shipping_price">'.number_format($shipping->delivery_price, 2).'</span></span><input type="hidden" id="shipping_method" name="shipping_method" value="delivery"></p>';

        } else {

          $shipping_price = $shipping->local_price;
          $shipping_type = 'Local pickup:';
          $tax_status = $shipping->local_tax;

          $data['shipping_method'] = 'local_pickup';
          
          $shipform = '<p style="border-bottom: 1px solid #d6d6d6; padding-bottom: 10px;font-weight: 500; color: #000; ">Shipping<span style="float: right;font-weight: 500; color: #000;" id="total_shipping"> <span style="font-weight: 400; color: #888;" id="shipping_type">Local pickup:</span> $<span id="shipping_price">'.number_format($shipping->local_price, 2).'</span></span><input type="hidden" id="shipping_method" name="shipping_method" value="local_pickup"></p>';
        }
      }

    if($tax_status == 1){
        $shipping_tax_price = ($shipping_price/100)*$shipping_tax;
    } 

    $total_price = $sale_price + $shipping_price + $shipping_tax_price;

    $data['shipping_price'] = $shipping_price;
    $data['shipping_type']  = $shipping_type;
    $data['shipping_tax_price']  = $shipping_tax_price;
    $data['shipform']  = $shipform;
    $data['shipformtype']  = $shipformtype;
    $data['total_price']  = number_format($total_price, 2);

    return $data;
  }



  public function generateOrderNR() {
        $orderObj = Order::orderBy('created_at', 'desc')->value('order_nr');
        if ($orderObj) {
            $orderNr = $orderObj;
            $removed1char = substr($orderNr, 4);
            $generateOrder_nr = $stpad = '#' . str_pad($removed1char + 1, 5, "0", STR_PAD_LEFT);
        } else {
            $generateOrder_nr = '#' . str_pad(1, 5, "0", STR_PAD_LEFT);
        }
        return $generateOrder_nr;
    }



 public function checkoutSubmit(Request $request){
  try {
    //dd($request);
    $input = $request->all();
    $success = true;
    $error = "Payment Failed";
    $case_deal_price = 0;
    $sale_price = 0;
    $list_price = 0;
    $tax = 0;
    $discount = 0;
    $offer_id = null;
    $stich_price = 0;
    $billing_address_id = 0;
      $validator = (new Order)->front_validate($input);
        if( $validator->fails() ) {
          return back()->withErrors($validator)->withInput();
      } 
  
 
      $user_id =  Auth::id();
      $user = User::where('id', $user_id)->first();

        $cart_items = Cart::where('user_id', $user_id)->get();
        $cart_products = \DB::table('products')
        ->join('carts', 'carts.product_id', '=','products.id')
        ->select('products.offer_price', 'carts.quantity', 'products.regular_price', 'carts.id as c_id', 'products.id as pid', 'products.url')
        ->where('products.status', 1)
        ->where('carts.user_id', $user_id)
        ->get();


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
  }

if(isset($cart_products)) {
  foreach($cart_products as $cart_product){

      if(\Auth::check()){
          $s_price = get_discounted_price($cart_product->pid);
        } else {
          $s_price = $cart_product->offer_price;
      }

      $sale_price += $s_price*$cart_product->quantity;
  }
}

$shipping_price = 0;

if($request->ship_different_address == 1){
    $s_zone = ShippingZone::where('id', $request->shipping_state)->first();
    if($request->shipping_method == 'flat_rate'){
        $shipping_price = $s_zone->flat_price;
    }
    if($request->shipping_method == 'delivery'){
        $shipping_price = $s_zone->delivery_price;
    }
    if($request->shipping_method == 'local_pickup'){
        $shipping_price = $s_zone->local_price;
    }
} else {
    $s_zone = ShippingZone::where('id', $request->billing_state)->first();
    if($request->shipping_method == 'flat_rate'){
        $shipping_price = $s_zone->flat_price;
    }
    if($request->shipping_method == 'delivery'){
        $shipping_price = $s_zone->delivery_price;
    }
    if($request->shipping_method == 'local_pickup'){
        $shipping_price = $s_zone->local_price;
    }
}

$input['payment_method'] = $request->shipping_method;
$input['shipping_price'] = $shipping_price;


        $apiKey = config('F9802AWGyjDwF5mTv1vF4WQXnkpBiVJH0jZewv9Fdemb8aZq6+WnmqpkaWeif8AcqngZYQ');
        $apiPassword = config('Rituishi@1234');

        $client = Rapid::createClient($apiKey, $apiPassword, Rapid\Client::MODE_SANDBOX); 

        $transaction = [
            'Payment' => [
                'TotalAmount' => 1000, 
            ],
            'Customer' => [
              'CardDetails' => [
                  'Name' => $request->card_holder_name,
                  'Number' => $request->card_number,
                  'ExpiryMonth' => $request->expiration_month,
                  'ExpiryYear' => $request->expiration_year,
                  'CVN' => $request->cvn,
              ],
            ],
        ];
    
        $response = $client->createTransaction(Rapid\Enum\ApiMethod::DIRECT, $transaction);

        // dd($response);
        // if (isset($response->TransactionStatus) && $response->TransactionStatus === true)  {
        //   dd($response->TransactionID);
        // } else {
        //    if (is_array($response)) {
        //           dd($response['Errors'][0]['Message']);
        //         } else {
        //           dd('Payment failed. Unable to retrieve error details.');
        //         }
        // }

 
    $total_pay_amount1 =  $sale_price + $shipping_price;
    $total_pay_amount =  $total_pay_amount1;
    $input['order_from'] = "WEB";
    $input['user_id']       =  $user_id;
    $input['total_price']   = $total_pay_amount;

    // $input['card_no']   = $request->card_no;
    // $input['card_holder']  = $request->card_holder;
    // $input['card_type']   = $request->card_type;
    // $input['expiration_month']  = $request->expiration_month;
    // $input['expiration_year']   = $request->expiration_year;
    // $input['card_security_code']  = $request->card_security_code;
    $input['discount'] = $discount;
    $input['status'] = 0;
    $input['current_status'] = 7;
    $input['offer_id'] = $offer_id;
    $input['order_nr'] = $this->generateOrderNR();
    $order_id = (new Order)->store($input); 
 
if(isset($cart_products)) {
  foreach($cart_products as $cart_product){

      if(\Auth::check()){
          $s_price = get_discounted_price($cart_product->pid);
        } else {
          $s_price = $cart_product->offer_price;
      }

      $sale_price += $s_price*$cart_product->quantity;
      $OrderProduct = new OrderProduct();
      $OrderProduct->order_id = $order_id;
      $OrderProduct->product_id = $cart_product->pid;
      $OrderProduct->user_id = $user_id;
      $OrderProduct->quantity = $cart_product->quantity;
      $OrderProduct->price = $s_price;
      $OrderProduct->save();
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

        Order::where('id', $order_id)
            ->update([
            'status' =>  0,
            'current_status' => 1,
        ]);

 
        $current_order = Order::where('id', $order_id)->first();  
        $email = $user->email;
        $data['cus_name'] = $user->name;
        $data['cart_items'] =  $cart_products;
        $data['discount'] = $discount;
        $data['sale_price'] = $sale_price;
        $data['current_order'] = $current_order;
        $data['total_pay_amount'] = $total_pay_amount;


        // $new_order_emails = EmailSetting::where('id', 1)->select('new_order_email')->first();
        // $new_order_email = $new_order_emails->new_order_email; 
        // $emails = explode (",", $new_order_email); 

        // $subject = 'Wishmed - Order Confirm '. $current_order->order_nr.'';
        // foreach ($emails as $key => $value) {
        //   $value = str_replace(' ', '', $value);
        //   \Mail::send('email.admin_order_admin', $data, function($message) use ($email, $value, $subject){
        //     $message->from($email);
        //     $message->to($value);
        //     $message->subject($subject);
        //   });
        // }
        

        // \Mail::send('email.admin_order', $data, function($message) use ($email, $subject){
        //   $message->from('no-reply@wishmed.com');
        //   $message->to($email);
        //   $message->subject($subject);
        // });

 

        foreach($cart_products as $del_item){
          if(isset($del_item)){
          $Product_lot = Product::where('id', $del_item->pid)->select('quantity')->first();

          if($Product_lot->quantity < $del_item->quantity){
            $p_quantity = 0;
          } else {
            $p_quantity = $Product_lot->quantity-$del_item->quantity;
          }
         // Product::where('id', $del_item->pid)
          //  ->update([
         //   'quantity' => $p_quantity,
        //  ]);
           \DB::table('carts')->where('id', $del_item->c_id)->delete();
          }
        }
        return view('frontend.pages.thanku', compact('current_order', 'email', 'request'));
  } catch(\Exception $exception){
   // dd($exception);
      return back();
  }

 }
 
 
 public function pay_later(Request $request){
  try {
    //dd($request);
    $inputs = $request->all();
    $success = true;
    $error = "Payment Failed";
    $case_deal_price = 0;
    $sale_price = 0;
    $list_price = 0;
    $tax = 0;
    $discount = 0;
    $offer_id = null;
    $stich_price = 0;
    $billing_address_id = 0;
    $user_id =  Auth::id();
    $user = User::where('id', $user_id)->first();
    if($user->premium == 1) {
    
      $validator = (new Order)->front_validate_pay_later($inputs);
        if( $validator->fails() ) {
          return back()->withErrors($validator)->withInput();
      } 
     // dd($request['address']);
    $user_address = UserAddress::where('id', $request['address'])->first();
    if($request->bill_address == 1){
      $BillingAddress = new BillingAddress();
      $BillingAddress->name = $user_address->name;
      $BillingAddress->address = $user_address->address;
      $BillingAddress->mobile = $user_address->mobile;
      $BillingAddress->state = $user_address->state;
      $BillingAddress->city = $user_address->city;
      $BillingAddress->pincode = $user_address->pincode;
      $BillingAddress->save();

     $billing_dt = BillingAddress::orderby('id', 'desc')->first();
     $billing_address_id = $billing_dt->id;

    } else{

      $billing_dt1 = BillingAddress::where('id', $request->address_billing)->first();
      if($billing_dt1){
      $billing_address_id = $billing_dt1->id;
    }
    }
    if($billing_address_id == 0){
      $BillingAddress = new BillingAddress();
      $BillingAddress->name = $user_address->name;
      $BillingAddress->address = $user_address->address;
      $BillingAddress->mobile = $user_address->mobile;
      $BillingAddress->state = $user_address->state;
      $BillingAddress->city = $user_address->city;
      $BillingAddress->pincode = $user_address->pincode;
      $BillingAddress->save();
      $billing_dt = BillingAddress::orderby('id', 'desc')->first();
      $billing_address_id = $billing_dt->id;
    }
      $user_address_billing = BillingAddress::where('id', $billing_address_id)->first();
      $shipping_id = $user_address->id;
      $session_id = $_SERVER['HTTP_USER_AGENT'];
      
    if(Auth::id()){
        $cart_items = Cart::where('user_id', $user_id)->get();
        $cart_products = \DB::table('products')
        ->join('carts', 'carts.product_id', '=','products.id')
        ->select('products.name','products.thumbnail', 'products.offer_price', 'carts.quantity', 'products.regular_price', 'carts.id as c_id', 'products.id as pid', 'products.url')
        ->where('products.status', 1)
        ->where('carts.user_id', $user_id)
        ->get();
      } else{
        $cart_items = Cart::where('session_id', $session_id)->get();
        $cart_products = \DB::table('products')
        ->join('carts', 'carts.product_id', '=','products.id')
        ->select('products.name','products.thumbnail', 'products.offer_price', 'carts.quantity', 'products.regular_price', 'carts.id as c_id', 'products.id as pid', 'products.url')
        ->where('products.status', 1)
        ->where('carts.session_id', $session_id)
        ->get();
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
  }

if(isset($cart_products)) {
  foreach($cart_products as $cart_product){
      $s_price = $cart_product->offer_price;
      $sale_price += $s_price*$cart_product->quantity;
      $list_price += $cart_product->regular_price*$cart_product->quantity; 
      $cd_price = cart_case_deal_price_discount($cart_product->c_id, $cart_product->offer_price);
      $case_deal_price += $cd_price*$cart_product->quantity;
  }
}

    $OrderAddress = new OrderAddress();
   // $OrderAddress->order_id = $order_id;
    $OrderAddress->name = $user_address->name;
    $OrderAddress->address = $user_address->address;
    $OrderAddress->mobile = $user_address->mobile;
    $OrderAddress->country = $user_address->country;
    $OrderAddress->company_name = $user_address->company_name;
    $OrderAddress->state = $user_address->state;
    $OrderAddress->city = $user_address->city;
    $OrderAddress->pincode = $user_address->pincode;
    $OrderAddress->save();
    $OrderBilling = new OrderBilling();
    $OrderBilling->name = $user_address_billing->name;
    $OrderBilling->address = $user_address_billing->address;
    $OrderBilling->mobile = $user_address_billing->mobile;
    $OrderBilling->state = $user_address->state;;
    $OrderBilling->city = $user_address->city;
    $OrderBilling->pincode = $user_address_billing->pincode;
    $OrderBilling->save();
    $shipping_add = OrderAddress::where('id', $OrderAddress->id)->first();
    $billing_add = OrderBilling::where('id', $OrderBilling->id)->first();
    $total_pay_amount1 =  $sale_price - $discount;
    $total_pay_amount =  $total_pay_amount1 - $case_deal_price;
    $input['order_from'] = "WEB";
    $input['user_id']       =  $user_id;
    $input['shipping_id']   = $OrderAddress->id;
    $input['billing_id']   = $OrderBilling->id;
    $input['total_price']  = $total_pay_amount;
    $input['pay_later'] = 1;
    
   // $input['card_no']   = $request->card_no;
   // $input['card_holder']  = $request->card_holder;
   // $input['card_type']   = $request->card_type;
   // $input['expiration_month']  = $request->expiration_month;
   // $input['expiration_year']   = $request->expiration_year;
  //  $input['card_security_code']  = $request->card_security_code;
    $input['discount'] = $discount;
    $input['case_deal_price'] = $case_deal_price;
    $input['status'] = 0;
    $input['current_status'] = 7;
    $input['offer_id'] = $offer_id;
    $input['order_nr'] = $this->generateOrderNR();
    $order_id = (new Order)->store($input); 
    OrderAddress::where('id', $OrderAddress->id)
            ->update([
            'order_id' =>  $order_id,
        ]);

    OrderBilling::where('id', $OrderBilling->id)
            ->update([
            'order_id' =>  $order_id,
        ]);
  if(isset($cart_products)) {
  foreach($cart_products as $cart_product){
      $s_price = $cart_product->offer_price;
      $sale_price += $s_price*$cart_product->quantity;
      $cd_price = cart_case_deal_price_discount($cart_product->c_id, $cart_product->offer_price); 
      $OrderProduct = new OrderProduct();
      $OrderProduct->order_id = $order_id;
      $OrderProduct->product_id = $cart_product->pid;
      $OrderProduct->case_deal_discount = $cd_price;
      $OrderProduct->user_id = $user_id;
      $OrderProduct->quantity = $cart_product->quantity;
      $OrderProduct->price = $s_price - $cd_price;
      $OrderProduct->save();
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

        Order::where('id', $order_id)
            ->update([
            'status' =>  0,
            'current_status' => 1,
        ]);
 
        $current_order = Order::where('id', $order_id)->first();  
        $email = $user->email;
        $data['cus_name'] = $user->name;
        $data['cart_items'] =  $cart_products;
        $data['discount'] = $discount;
        $data['sale_price'] = $sale_price;
        $data['shipping_add'] = $shipping_add;
        $data['billing_add'] = $billing_add;
        $data['current_order'] = $current_order;
        $data['total_pay_amount'] = $total_pay_amount;
        $new_order_emails = EmailSetting::where('id', 1)->select('new_order_email')->first();
        $new_order_email = $new_order_emails->new_order_email; 
        $emails = explode (",", $new_order_email); 
        $subject = 'Puka Creations - Order Confirm '. $current_order->order_nr.'';
        foreach ($emails as $key => $value) {
          $value = str_replace(' ', '', $value);
          \Mail::send('email.admin_order_admin_pay_later', $data, function($message) use ($email, $value, $subject){
            $message->from($email);
            $message->to($value);
            $message->subject($subject);
          });
        }

        \Mail::send('email.admin_order', $data, function($message) use ($email, $subject){
          $message->from('no-reply@pukacreations.com');
          $message->to($email);
          $message->subject($subject);
        });

        // $c_o_n =  str_replace("#","", $current_order->order_nr);
        // $message_sms = 'Dear '.$user->name.', Thank you for your Purchase on Puka Creations. Your Order no '.$c_o_n.' has been placed successfully.';
        // $this->sendSms($user->mobile, $message_sms);

        foreach($cart_products as $del_item){
          if(isset($del_item)){
         // $Product_lot = Product::where('id', $del_item->pid)->select('quantity')->first();

         // if($Product_lot->quantity < $del_item->quantity){
          //  $p_quantity = 0;
          //} else {
          //  $p_quantity = $Product_lot->quantity-$del_item->quantity;
          //}
         // Product::where('id', $del_item->pid)
          //  ->update([
         //   'quantity' => $p_quantity,
        //  ]);
           \DB::table('carts')->where('id', $del_item->c_id)->delete();
          }
        }
    }
        return view('frontend.pages.thanku', compact('current_order', 'email', 'shipping_add', 'request'));
  } catch(\Exception $exception){
   // dd($exception);
      return back();
  }

 }

 


function sendSms($mobile, $message)
  {
      try{
          $ch = curl_init('https://www.txtguru.in/imobile/api.php?');
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, "username=abc&password=error@1644&source=ABC&dmobile=91".$mobile."&message=".$message."");
          curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
          $data = curl_exec($ch);

      }
      catch(\Exception $e){
          return back();
      }
  }

}
















