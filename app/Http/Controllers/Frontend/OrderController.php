<?php

namespace App\Http\Controllers\Frontend;
/**
 * :: Order Controller ::
 * 
 *
 **/
use Intervention\Image\ImageManagerStatic as Image;
use Auth;
use Files;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\User;
use App\Models\Offer;
use App\Models\OrderProduct;
use App\Models\OrderAddress;
use App\Models\OrderStatus;
use App\Models\DefaultAddress;
use App\Models\ReturnProduct;
use App\Models\ReturnRequest;
use App\Models\Cart;
use App\Models\ReturnReasons;
use App\Models\OrderProductStatus;
use App\Models\Wallet;
use Illuminate\Http\Request;
use NumberToWords\NumberToWords;
use App\Models\UserAddress;
use App\Models\Product;
use App\Models\UserDevice;
use App\Models\Notification;
use App\Models\ProductLot;
use App\Models\Category;
use App\Models\CancelReason;
use App\Models\CancelOrder;
use PDF;
use App\Models\ProductImage;

class OrderController extends  Controller{

    public function  index()
    {
      try {
          $user_id =  Auth::id();

          $orders = \DB::table('orders')
          ->join('order_statuses', 'order_statuses.id', '=', 'orders.current_status')
          ->select('orders.id','orders.order_nr', 'orders.total_price', 'order_statuses.type', 'order_statuses.id as type_id', 'orders.current_status', 'orders.created_at', 'orders.ship_different_address', 'orders.shipping_first_name', 'orders.shipping_last_name', 'orders.billing_first_name', 'orders.billing_last_name')
          ->where('orders.user_id', $user_id)
          ->orderby('orders.created_at', 'desc') 
          ->paginate(10);

        return view('frontend.pages.my-order', compact('orders'));

    } catch(\Exception $exception){

       dd($exception);
        return back();
      }
    }


    public function  orderDetail($id = null)
    {
      try {
          $user_id =  Auth::id();
          
        $order = \DB::table('orders')
          ->join('order_statuses', 'order_statuses.id', '=', 'orders.current_status')
          ->select('orders.*', 'order_statuses.type')
          ->where('orders.user_id', $user_id)
          ->where('orders.id', $id)
          ->first();


        return view('frontend.pages.order-detail', compact('order'));

    } catch(\Exception $exception){

       dd($exception);
        return back();
      }
    }

    public function orderInvoice($id){
       try {
          $user_id =  Auth::id();
    
          $data['order'] = \DB::table('orders')
          ->join('order_statuses', 'order_statuses.id', '=', 'orders.current_status')
          ->join('order_addresses', 'order_addresses.id', '=', 'orders.shipping_id')
          ->join('order_billings', 'order_billings.id', '=', 'orders.billing_id')
          ->select('orders.id','orders.order_nr', 'orders.total_price', 'orders.wallet_paid', 'order_statuses.type', 'order_addresses.name', 'order_addresses.address', 'order_addresses.state', 'order_addresses.city', 'order_addresses.pincode', 'orders.shipping_charges', 'orders.discount', 'orders.payment_method', 'orders.created_at', 'order_billings.name as billing_name', 'order_billings.address as billing_address', 'order_billings.mobile as billing_mobile', 'order_billings.state as billing_state', 'order_billings.city as billing_city', 'order_billings.pincode as billing_pincode')
          ->where('orders.user_id', $user_id)
          ->where('orders.id', $id)
          ->first();

        
      //  dd($data);

          $pdf = \PDF::loadView('pdf.order_invoice', $data);
          return $pdf->download('order_invoice.pdf'); 


        } catch(\Exception $exception){

       //dd($exception);
        return back();
      }

    }
public function retutnRequest($product_id, $order_id){

    try{
       
      $user_id =  Auth::id(); 
      $ReturnReasons = ReturnReasons::where('status', 1)->get();
      $order = Order::where('user_id', $user_id)->where('id', $order_id)->first();
      if($order){

      $lot = ProductLot::where('id', $product_id)->first();
      $product = Product::where('id', $lot->product_id)->select('thumbnail')->first();
   
  

       return view('frontend.pages.retutn-request', compact('ReturnReasons', 'order_id', 'product_id', 'product'));
      }

    } catch(\Exception $exception){

     // dd($exception);

        return back();
      }
}

public function return_orders(){

    try{

        $user_id =  Auth::id(); 

        $orders = ReturnRequest::join('orders', 'orders.id', '=', 'return_requests.order_id')
        ->join('order_addresses', 'order_addresses.order_id','=', 'orders.id')
        ->select('return_requests.reason', 'return_requests.boy_pickup', 'return_requests.pickup_date', 'return_requests.pickup_slot', 'orders.order_nr', 'order_addresses.name', 'order_addresses.address', 'order_addresses.mobile', 'order_addresses.state', 'order_addresses.city', 'order_addresses.pincode', 'return_requests.id')
        ->where('return_requests.delivery_boy_id', $user_id)
        ->get();
         
      return view('frontend.pages.retutn-orders', compact('orders'));

    } catch(\Exception $exception){

     // dd($exception);
        return back();
      }

}

public function cancel_order($id = null){

  try{

      $return_cancel = CancelReason::where('status', 1)->get();
      $order_id = $id;

      return view('frontend.pages.cancel-order', compact('return_cancel', 'order_id'));
  } catch(\Exception $exception){

     // dd($exception);
        return back();
  }

}


public function cancel_order_submit(Request $request){

  try{
     
     $order = Order::where('id', $request->order_id)->select('order_nr', 'current_status', 'user_id', 'total_price')->first();
      $user = User::where('id', $order->user_id)->select('name', 'mobile', 'email', 'wallet_valance')->first();

      if($order->current_status < 5){

      $cancel_reason = new CancelOrder();
      $cancel_reason->order_id = $request->order_id;
      $cancel_reason->reason_id = $request->reason;
      $cancel_reason->save();

      Order::where('id', $request->order_id)
          ->update([
            'current_status' =>  8,
      ]);

      OrderProductStatus::create([
          'order_id' =>  $request->order_id,
          'status' =>  8
      ]);

      $amount = $user->wallet_valance+$order->total_price;

        User::where('id', $order->user_id)
          ->update([
            'wallet_valance' =>  $amount,
        ]);


        $wallet = new Wallet();
        $wallet->user_id = $order->user_id;
        $wallet->amount = $order->total_price;
        $wallet->created_by = Auth::id();
        $wallet->save();

        $data['name'] = $user->name;
        $data['order_nr'] = $order->order_nr;
        $data['total_price'] = $order->total_price;

        $c_o_n =  str_replace("#","", $order->order_nr);
        $message_sms = 'Dear '.$user->name.', Your Order no '.$c_o_n.' has been cancelled successfully. Amount of RS. '.$order->total_price.' has been credit in your wallet.';
        $this->sendSms($user->mobile, $message_sms);

        $email = $user->email;

        // \Mail::send('email.cancel_order_wallet', $data, function($message) use ($email){
        //   $message->from('info@frugr.com');
        //   $message->to($email);
        //   $message->subject('Frugr - Order Cancel');
        // });
    return redirect()->route('my-orders')
                ->with('success1', 'Your order no '.$order->order_nr. ' has been cancelled successfully');
     }
    return redirect()->route('my-orders');

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
          curl_setopt($ch, CURLOPT_POSTFIELDS, "username=frugr&password=error@1644&source=FRUGRR&dmobile=91".$mobile."&message=".$message."");
          curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
          $data = curl_exec($ch);

      }
      catch(\Exception $e){
          return back();
      }
  }


public function returnProduct(Request $request){

  try{
     $user_id =  Auth::id(); 
     $inputs = $request->all();
     $validator = (new ReturnRequest)->front_validate($inputs);
      if( $validator->fails() ) {
          return back()->withErrors($validator)->withInput();
      }
    $return_id = 0; 
    $ReturnRequest = ReturnRequest::where('order_id', $request->order_id)->first();

    if(empty($ReturnRequest)){
    $ReturnRequest = new ReturnRequest();
    $ReturnRequest->user_id = $user_id;
    $ReturnRequest->order_id = $request->order_id;
    $ReturnRequest->reason = $request->reason;
    $ReturnRequest->status = 0;
    $ReturnRequest->created_by = $user_id;
    $ReturnRequest->save();
    }
    $return_id = $ReturnRequest->id;

   
    $already = ReturnProduct::where('return_id', $return_id)->where('product_id', $request->product_id)->first();
   if($already){
      return back()->with('already_return', lang('messages.created', lang('delete')));
   } else{

    if(isset($inputs['image']) or !empty($inputs['image']))
            {

                $image_name = rand(100000, 999999);
                $fileName = '';

                if($file = $request->hasFile('image')) 
                {
                    $file = $request->file('image') ;
                    $img_name = $file->getClientOriginalName();
                    $image_resize = Image::make($file->getRealPath()); 
                    $image_resize->resize(512, 512);

                    $fileName = $image_name.$img_name;
                    $image_resize->save(public_path('/uploads/return_images/' .$fileName));                      
                }

                $fname ='/uploads/return_images/';
                $image = $fname.$fileName;
       
            }

            $ReturnProduct = new ReturnProduct();
            $ReturnProduct->return_id = $return_id;
            $ReturnProduct->product_id = $request->product_id;
            $ReturnProduct->order_id = $request->order_id;
            $ReturnProduct->image = $image;
            $ReturnProduct->save();

       return back()->with('return', lang('messages.created', lang('return')));      

   }


  } catch(\Exception $exception){

     // dd($exception);
        return back();
      }

}

  public function default_address($id){
     
      try{
        $user_id =  Auth::id();

        $DefaultAddress = new DefaultAddress();
        $DefaultAddress->user_id = $user_id;
        $DefaultAddress->address_id = $id;
        $DefaultAddress->save();
      
        return back();

      } catch(\Exception $exception){

    // dd($exception);
        return back();
      }


  }


}
