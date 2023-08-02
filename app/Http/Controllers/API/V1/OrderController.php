<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderStatus;
use App\Models\OrderProduct;
use App\Models\UserAddress;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Offer;
use App\User;
use App\Models\UserDevice;
use App\Models\Notification;
use App\Models\OrderProductStatus;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\CaseDeal;
use App\Models\EmailSetting;
use App\Models\BillingAddress;
use NumberToWords\NumberToWords;
use App\Models\CategoryProducts;
use App\Models\OrderBilling;
use Carbon\Carbon;

class OrderController extends Controller
{

  public function myOrders(Request $request){
    try{
      if($request->api_key){
      $user = User::where('api_key', $request->api_key)->select('id')->first();
      if($user){  
        $data = [];
        $orders = array();
        $order_pro = array();
        $url = route('home'); 
          $o_data = Order::where('user_id', $user->id)->select('id')->orderBy('created_at', 'desc')->get();
          if (count($o_data) != 0) {
            foreach ($o_data as $od) {
              $ord = Order::where('id', $od->id)->select('order_nr', 'total_price', 'current_status', 'created_at')->first();
              $order['order_id'] = $od->id;
              $order['order_date'] = date_format($ord->created_at, 'd F Y, h:i:s A');
              $order['order_nr'] =   $ord->order_nr;
              $order['total_price'] = (int) $ord->total_price;
              $order_status = OrderStatus::where('id', $ord->current_status)->select('type')->first();
              $order['status'] = $order_status->type;
              $items = 0;
              $orde_product = OrderProduct::where('order_id', $od->id)->select('quantity', 'product_id')->first(); 
                $items += $orde_product->quantity;
                $pro = Product::where('id', $orde_product->product_id)->select('id', 'thumbnail', 'name')->first();
                $products['product_id'] = $pro->id;
                $products['name'] = $pro->name;
                $products['thumbnail'] = $url.$pro->thumbnail;
            
              $order['items_count'] = $items;
              $order['products'] = $products;
              $orders[] = $order;
            }
              $data = $orders;
            return apiResponseApp(true, 200, null, null, $data);
          }
        $message = "No Orders found";
        return apiResponseAppmsg(false, 200, $message, null, null);
      }
    }
      } catch(Exception $e){
      return apiResponseApp(false, 500, lang('messages.server_error'));
      }
    }

    public function order_status(Request $request){
      try{
          if($request->api_key){
            $data = [];
            $user = User::where('api_key', $request->api_key)->select('id')->first();
            if($user){
            $order = Order::where('id', $request->order_id)->where('user_id', $user->id)->select('created_at')->first();
            if($order){
                    $o_status['status'] = 'Order Place';
                    $o_status['created_at'] = $order->created_at->toDateTimeString();
                    $data[] = $o_status; 
                $orders = OrderProductStatus::where('order_id', $request->order_id)->select('status', 'created_at')->get();
                if($orders){
                  foreach ($orders as  $ord) {
                    $OrderStatus = OrderStatus::where('id', $ord->status)->select('type')->first();
                    $o_status['status'] = $OrderStatus->type;
                    $o_status['created_at'] = $ord->created_at->toDateTimeString();
                    $data[] = $o_status; 
                  }    
                }

              return apiResponseApp(true, 200, null, null, $data);
            }

            }
        }
      } catch(Exception $e){
        return apiResponseApp(false, 500, lang('messages.server_error'));
      }
    }

 
    public function orderDetail(Request $request){
      try{
        
        if($request->api_key){
        $user = User::where('api_key', $request->api_key)->select('id')->first();
        if($user){  
            $order_pro = []; 
            $url = route('home'); 
            $ord = Order::where('id', $request->order_id)->select('order_nr', 'total_price', 'discount', 'transaction_id', 'current_status', 'created_at', 'card_no', 'card_holder', 'card_type',
             'expiration_month', 'expiration_year', 'card_security_code')->first();
              $data['order_id'] = $request->order_id;
              $data['order_date'] =  date_format($ord->created_at, 'd F Y, h:i:s A');
              $data['order_nr'] =   $ord->order_nr;
              $data['total_price'] = (int) $ord->total_price;
              $data['discount'] = (int) $ord->discount;
              $data['transaction_id'] = $ord->transaction_id;
              $data['card_no'] =   $ord->card_no;
              $data['card_holder'] = $ord->card_holder;
              $data['card_type'] = $ord->card_type;
              $data['expiration_month'] = $ord->expiration_month;
              $data['expiration_year'] = $ord->expiration_year;
              $data['card_security_code'] = $ord->card_security_code;

              $order_status = OrderStatus::where('id', $ord->current_status)->select('type')->first();
              $data['status'] = $order_status->type;

              $items = 0;
              $orde_products = OrderProduct::where('order_id', $request->order_id)->select('quantity', 'product_id', 'price', 'case_deal_discount')->get();  

              foreach ($orde_products as  $orde_product) {
                $items += $orde_product->quantity;
                
                $pro = Product::where('id', $orde_product->product_id)->select('id', 'thumbnail', 'name')->first();
                $products['product_id'] = $pro->id;
                $products['name'] = $pro->name;
                $products['quantity'] = (int) $orde_product->quantity;
                $products['price'] = (int) $orde_product->price;
                $products['case_deal_discount'] = (int) $orde_product->case_deal_discount;
                $products['thumbnail'] = $url.$pro->thumbnail;
                $order_pro[] = $products;
              }

              $data['items_count'] = $items;
              $shipping_address = OrderAddress::where('order_id', $request->order_id)->select('name', 'mobile', 'address', 'pincode', 'city', 'state', 'country')->first();

              if($shipping_address){
                $country = \DB::table('countries')->select('country_name')->where('id', $shipping_address->country)->first();
                $data['shipping_name']    = $shipping_address->name;
                $data['shipping_mobile']  = $shipping_address->mobile;
                $data['shipping_address'] = $shipping_address->address;
                $data['shipping_pincode'] = $shipping_address->pincode; 
                $data['shipping_city']    = $shipping_address->city;
                $data['shipping_state']   = $shipping_address->state;
                $data['country']   = $country->country_name;
              }
         
              $data['order_products'] = $order_pro;
             return apiResponseApp(true, 200, null, null, $data);
      }
      }
      } catch(Exception $e){
          return apiResponseApp(false, 500, lang('messages.server_error'));
      }
    }


    public function notifications(Request $request) {
      try {

        if($request->api_key){

        $user = User::where('api_key', $request->api_key)->select('id')->first();
        if($user){ 
        $data = [];
        $notifications = Notification::where('user_id', $user->id)->orwhere('user_id', 0)->select('message', 'image', 'type', 'type_id', 'created_at')->orderby('id', 'desc')->get();
         $url = route('home'); 
          if($notifications){
             foreach ($notifications as $key => $notification) {
                $slide['message'] = $notification->message;
                $slide['image'] = $url.$notification->image;
                $slide['type'] = $notification->type;
                $slide['type_id'] = $notification->type_id;
                $slide['created_at'] = $notification->created_at;
              $data[] = $slide;
             }
          }
        return apiResponseApp(true, 200, null, null, $data);
        }
      } 
      } catch(Exception $e){
          return apiResponseApp(false, 500, lang('messages.server_error'));
        }
    }


  public function payWallet(Request $request){
    try{

      if($request->api_key){
      $user = User::where('api_key', $request->api_key)->first();
      if($user){ 
      $sale_price = 0;
      $inputs = $request->all();
      $list_price = 0;
      $case_deal_price = 0;
      $billing_address_id = 0;

        $user_address = UserAddress::where('id', $request['address_id'])->first();
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
        $shipping_add = OrderAddress::where('id', $OrderAddress->id)->first();
        $user_address_billing = BillingAddress::where('id', $billing_address_id)->first();
        $shipping_id = $user_address->id;  
        $user_id =  $user->id;
        $cart_products = \DB::table('products')
        ->join('carts', 'carts.product_id', '=','products.id')
        ->select('products.name','products.thumbnail', 'products.offer_price', 'carts.quantity', 'products.regular_price', 'carts.id as c_id', 'products.id as pid', 'products.url')
        ->where('products.status', 1)
        ->where('carts.user_id', $user_id)
        ->get();

        if(isset($cart_products)) {
          foreach($cart_products as $cart_product){
            if(isset($cart_product->offer_price)){
              $s_price = $cart_product->offer_price;
              $sale_price += $s_price*$cart_product->quantity;
              $list_price += $cart_product->regular_price*$cart_product->quantity; 
              $cd_price = cart_case_deal_price_discount($cart_product->c_id, $cart_product->offer_price);
              $case_deal_price += $cd_price*$cart_product->quantity;
            }
          }
        }

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
        
    $total_pay_amount = $sale_price - $case_deal_price;

    $input['user_id']       =  $user_id;
    $input['shipping_id']   = $OrderAddress->id;
    $input['billing_id']    = $OrderBilling->id;
    $input['total_price']   = $total_pay_amount;
    $input['card_no']   = $request->card_no;
    $input['card_holder']  = $request->card_holder;
    $input['card_type']   = $request->card_type;
    $input['expiration_month']  = $request->expiration_month;
    $input['expiration_year']   = $request->expiration_year;
    $input['card_security_code']  = $request->card_security_code;
    $input['order_from'] = "APP";
    $input['status'] = 0;
    $input['current_status'] = 1;
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
          if(isset($cart_product->offer_price)){
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
      }

        $current_order = Order::where('id', $order_id)->first();  
        $email = $user->email;
        $data['cus_name'] =  $user->name;
        $data['cart_items'] =  $cart_products;
        $data['sale_price'] = $sale_price;
        $data['shipping_add'] = $shipping_add;
        $data['billing_add'] = $billing_add;
        $data['current_order'] = $current_order;
        $data['total_pay_amount'] = $total_pay_amount;
        $new_order_emails = EmailSetting::where('id', 1)->select('new_order_email')->first();
        $new_order_email = $new_order_emails->new_order_email; 
        $emails = explode (",", $new_order_email); 
        $subject = 'Puka Creations - Order Confirm '. $input['order_nr']. '';
        foreach ($emails as $key => $value) {
          $value = str_replace(' ', '', $value);
          \Mail::send('email.admin_order_admin', $data, function($message) use ($email, $value, $subject){
            $message->from('no-reply@pukacreations.com');
            $message->to($value);
            $message->subject($subject);
          });
        }

        \Mail::send('email.admin_order', $data, function($message) use ($email, $subject){
          $message->from('no-reply@pukacreations.com');
          $message->to($email);
          $message->subject($subject);
        });

        foreach($cart_products as $del_item){
          if(isset($del_item)){
          $Product_lot = Product::where('id', $del_item->pid)->select('quantity')->first();
          if($Product_lot->quantity < $del_item->quantity){
            $p_quantity = 0;
          } else {
            $p_quantity = $Product_lot->quantity-$del_item->quantity;
          }
          Product::where('id', $del_item->pid)
            ->update([
            'quantity' => $p_quantity,
          ]);
           \DB::table('carts')->where('id', $del_item->c_id)->delete();
          }
        }

        $order_data['order_no'] = $input['order_nr']; 
        $order_data['customer_name'] = $user->name;  
        $order_data['customer_email'] = $user->email;  

        return apiResponseApp(true, 200, null, null, $order_data);
      }

      }

    } catch(Exception $e){
              return apiResponseApp(false, 500, lang('messages.server_error'));
      }

  }


public function pay_later(Request $request){
    try{

      if($request->api_key){
      $user = User::where('api_key', $request->api_key)->first();
      if($user->premium == 1){ 
      $sale_price = 0;
      $inputs = $request->all();
      $list_price = 0;
      $case_deal_price = 0;
      $billing_address_id = 0;

        $user_address = UserAddress::where('id', $request['address_id'])->first();
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
        $shipping_add = OrderAddress::where('id', $OrderAddress->id)->first();
        $user_address_billing = BillingAddress::where('id', $billing_address_id)->first();
        $shipping_id = $user_address->id;  
        $user_id =  $user->id;
        $cart_products = \DB::table('products')
        ->join('carts', 'carts.product_id', '=','products.id')
        ->select('products.name','products.thumbnail', 'products.offer_price', 'carts.quantity', 'products.regular_price', 'carts.id as c_id', 'products.id as pid', 'products.url')
        ->where('products.status', 1)
        ->where('carts.user_id', $user_id)
        ->get();

        if(isset($cart_products)) {
          foreach($cart_products as $cart_product){
            if(isset($cart_product->offer_price)){
              $s_price = $cart_product->offer_price;
              $sale_price += $s_price*$cart_product->quantity;
              $list_price += $cart_product->regular_price*$cart_product->quantity; 
              $cd_price = cart_case_deal_price_discount($cart_product->c_id, $cart_product->offer_price);
              $case_deal_price += $cd_price*$cart_product->quantity;
            }
          }
        }

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
        
    $total_pay_amount = $sale_price - $case_deal_price;

    $input['user_id']       =  $user_id;
    $input['shipping_id']   = $OrderAddress->id;
    $input['billing_id']    = $OrderBilling->id;
    $input['total_price']   = $total_pay_amount;
    $input['pay_later'] = 1;
    // $input['card_no']   = $request->card_no;
    // $input['card_holder']  = $request->card_holder;
    // $input['card_type']   = $request->card_type;
    // $input['expiration_month']  = $request->expiration_month;
    // $input['expiration_year']   = $request->expiration_year;
    // $input['card_security_code']  = $request->card_security_code;
    $input['order_from'] = "APP";
    $input['status'] = 0;
    $input['current_status'] = 1;
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
          if(isset($cart_product->offer_price)){
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
      }

        $current_order = Order::where('id', $order_id)->first();  
        $email = $user->email;
        $data['cus_name'] =  $user->name;
        $data['cart_items'] =  $cart_products;
        $data['sale_price'] = $sale_price;
        $data['shipping_add'] = $shipping_add;
        $data['billing_add'] = $billing_add;
        $data['current_order'] = $current_order;
        $data['total_pay_amount'] = $total_pay_amount;
        $new_order_emails = EmailSetting::where('id', 1)->select('new_order_email')->first();
        $new_order_email = $new_order_emails->new_order_email; 
        $emails = explode (",", $new_order_email); 
        $subject = 'Puka Creations - Order Confirm '. $input['order_nr']. '';
        foreach ($emails as $key => $value) {
          $value = str_replace(' ', '', $value);
          \Mail::send('email.admin_order_admin_pay_later', $data, function($message) use ($email, $value, $subject){
            $message->from('no-reply@pukacreations.com');
            $message->to($value);
            $message->subject($subject);
          });
        }

        \Mail::send('email.admin_order', $data, function($message) use ($email, $subject){
          $message->from('no-reply@pukacreations.com');
          $message->to($email);
          $message->subject($subject);
        });

        foreach($cart_products as $del_item){
          if(isset($del_item)){
          $Product_lot = Product::where('id', $del_item->pid)->select('quantity')->first();
          if($Product_lot->quantity < $del_item->quantity){
            $p_quantity = 0;
          } else {
            $p_quantity = $Product_lot->quantity-$del_item->quantity;
          }
          Product::where('id', $del_item->pid)
            ->update([
            'quantity' => $p_quantity,
          ]);
           \DB::table('carts')->where('id', $del_item->c_id)->delete();
          }
        }

        $order_data['order_no'] = $input['order_nr']; 
        $order_data['customer_name'] = $user->name;  
        $order_data['customer_email'] = $user->email;  

        return apiResponseApp(true, 200, null, null, $order_data);
      }

      }

    } catch(Exception $e){
              return apiResponseApp(false, 500, lang('messages.server_error'));
      }

  }


  // function sendSms($mobile, $message){
  //     try{
  //         $ch = curl_init('https://www.txtguru.in/imobile/api.php?');
  //         curl_setopt($ch, CURLOPT_POST, 1);
  //        curl_setopt($ch, CURLOPT_POSTFIELDS, "username=abc&password=error@1644&source=abc&dmobile=91".$mobile."&message=".$message."");
  //         curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  //         $data = curl_exec($ch);
  //     }
  //     catch(\Exception $e){
  //         return back();
  //     }
  // }


  public function checkout(Request $request){
    try {

      $sale_price = 0;
      $list_price = 0;
      $total_case_deal = 0;
      $tax = 0;
      $total_amount = 0;
      $data = [];
      $cart_pro = array();
      $url = route('home'); 
      $total_saving =  0;
      $discount = 0;
      if($request->api_key){
      $user = User::where('api_key', $request->api_key)->select('id')->first();
        if($user){ 
          $user_id = $user->id;

         $cart_products = \DB::table('products')
        ->join('carts', 'carts.product_id', '=','products.id')
        ->select('products.name','products.thumbnail', 'products.offer_price', 'carts.quantity', 'products.regular_price', 'carts.id as cart_id', 'products.id as product_id', 'products.quantity as product_quantity')
        ->where('products.status', 1)
        ->where('carts.user_id', $user->id)
        ->get();

        if(isset($cart_products)) {
        foreach($cart_products as $pro){
          if(isset($pro->offer_price)){

            $findCaseDeal = CaseDeal::where('status', 1)->where('product_id', $pro->product_id)->where('quantity', '<=', $pro->quantity)->where('max_quantity', '>=', $pro->quantity)->select('discount')->orderby('discount', 'desc')->first();

                $products['thumbnail'] = $url.$pro->thumbnail;
                $products['name']      = $pro->name; 
                $products['offer_price'] = (int) $pro->offer_price;
                $products['regular_price']  = (int) $pro->regular_price;
                $products['product_id'] = $pro->product_id; 
                $products['cart_unit']  = (int) $pro->quantity;
                $products['quantity'] = (int) $pro->product_quantity;
                $total_amount += $pro->offer_price*$pro->quantity;
                if($findCaseDeal){
                  $products['case_deal'] =  round(($pro->offer_price*$pro->quantity/100)*$findCaseDeal->discount);
                } else {
                  $products['case_deal'] = 0;
                }
                $products['unit_price'] = $pro->quantity*$pro->offer_price-$products['case_deal'];

                $total_case_deal += $products['case_deal'];
                $cart_pro[] = $products;

            }
          }

          $total_amount = $total_amount - $total_case_deal;
          $data['products'] = $cart_pro;

        }
         
        $address = \DB::table('user_addresses')
        ->join('countries', 'countries.id', '=','user_addresses.country')
        ->select('user_addresses.name','user_addresses.mobile', 'user_addresses.address', 'user_addresses.company_name', 'user_addresses.state', 'user_addresses.city', 'user_addresses.pincode as zip_code', 'countries.country_name')
        ->where('user_addresses.id', $request->address_id)
        ->where('user_addresses.user_id', $user_id)
        ->get();

        $data['address'] = $address;

        }
         
      return apiResponseAppcart(true, 200, null, null, $data, $total_amount);
    }

      }  catch(Exception $e){
              return apiResponseApp(false, 500, lang('messages.server_error'));
      }

  }





    public function generateOrderNR()
    {
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

    //My Orders

    public function order_products(Request $request){

    try{
        $user = User::where('api_key', $request->api_key)->select('id')->first();
        if($user){
        
        $order_pro = [];

        $o_products = OrderProduct::where('order_id', $request->id)->select('product_id')->get();

        foreach ($o_products as $o_product) {

        $product_order = OrderProduct::where('order_id', $request->id)->where('product_id', $o_product->product_id)->select('quantity', 'price')->first();

        $products['quantity'] = $product_order->quantity;
        $products['price'] = $product_order->price; 
        $product_order = ProductLot::where('id', $o_product->product_id)->select('unit', 'weight', 'product_id')->first();
        $products['weight'] = $product_order->weight .' '. $product_order->unit; 
        $p_pro = Product::where('id', $product_order->product_id)->select('name', 'thumbnail', 'brand_id', 'id', 'return_applicable', 'return_days')->first();
        $products['name'] = $p_pro->name;
        $products['thumbnail'] = $p_pro->thumbnail;
        $products['pro_id'] = $p_pro->id;
        $products['child_id'] = $o_product->product_id;
        $brand = Brand::where('id', $p_pro->brand_id)->select('name')->first();
        $products['brand_name'] = $brand->name;
        
        $order =  Order::where('id', $request->id)->select('deliverd_date', 'current_status')->first();
          
        if($p_pro->return_applicable == 1){
          if($order->deliverd_date){
            $today = date('Y-m-d');
            $purchase_day = date('Y-m-d', strtotime($order->deliverd_date));
            $now = strtotime($today);
            $your_date = strtotime($purchase_day);
            $datediff = $now - $your_date;
            $total_day = round($datediff / (60 * 60 * 24));
            if($total_day <= $p_pro->return_days){
            if($order->current_status == 5){
               $products['return_applicable'] = 1;
            } else {
              $products['return_applicable'] = 0;
            }
            } else {
              $products['return_applicable'] = 0; 
            }
          } else {
          $products['return_applicable'] = 0; 
          }
        } else {
          $products['return_applicable'] = 0;
        }
         $order_pro[] = $products;
        }

        $dl_review = Order::where('id', $request->id)->select('order_review')->first(); 
        $data['order_review'] = $dl_review->order_review; 
        $data['order_pro'] = $order_pro;
      
      return apiResponseApp(true, 200, null, null, $data);
      }
    } catch(Exception $e){

      return apiResponseApp(false, 500, lang('messages.server_error'));
      }

    }



}
