<?php

namespace App\Http\Controllers;
/**
 * :: Order Controller ::
 * 
 *
 **/
use Auth;
use Files;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;
use App\Models\Order;
use App\User;
use App\Models\Offer;
use App\Models\OrderProduct;
use App\Models\OrderAddress;
use App\Models\OrderStatus;
use App\Models\Cart;
use App\Models\OrderProductStatus;
use Illuminate\Http\Request;
use NumberToWords\NumberToWords;
use App\Models\UserAddress;
use App\Models\Product;
use App\Models\UserDevice;
use App\Models\Notification;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\OrderHistory;
use App\Models\OrderProductsHistory;
use App\Models\TaxAmount;
use PDF;

class OrderController extends  Controller{
  
    public function  index() {
        $Customer = (new User)->getCustomerList();
        $OrderStatus = (new OrderStatus)->getOrderStatusService();
      if(((\Auth::user()->user_type)) == 1 || ((\Auth::user()->user_type)) == 3){
          return view('admin.order.index', compact('OrderStatus', 'Customer'));
      } else {
        echo "Wrong Url";
      }
    }
  
    public function  create() {
        return view('admin.order.create');
    }

    public function  store(Request $request)
    {
        
        $inputs = $request->all();
       // dd($request);
        try {
            $validator = (new Order)->validate($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }
            
            $status = $inputs['status'];
            $inputs = $inputs + [
                    'status'    => !empty($status)?$status:0,
                    'created_by' => Auth::id(),
                ];  
          
            (new Order)->store($inputs);
     
            return redirect()->route('order.index')
                ->with('success', lang('messages.created', lang('order.order')));
        } catch (\Exception $exception) {
       
            return redirect()->route('order.create')
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
    }

  
    public function update(Request $request, $id = null) {
        $result = (new Order)->find($id);
        if (!$result) {
            abort(401);
        }

        $inputs = $request->all();

        try {
            //\DB::beginTransaction();
            
            $status = $inputs['status'];
            $inputs = $inputs + [
                    'status'    => !empty($status)?$status:0,
                    'updated_by' => Auth::id(),
                ];   
            
            (new Order)->store($inputs, $id);
            //\DB::commit();
            return redirect()->route('order.index')
                ->with('success', lang('messages.updated', lang('order.order')));

        } catch (\Exception $exception) {
            //\DB::rollBack();
            /*return validationResponse(false, 207, lang('messages.server_error'));*/
            return redirect()->route('order.edit', [$id])
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }


    public function orderUpdate(Request $request){
        try {
            
            $order = Order::where('id', $request->order_id)->select('total_price', 'shipping_tax', 'shipping_price')->first();

            $user_id =  Auth::id();

            OrderHistory::create([
                'order_id'    => $request->order_id,
                'last_price'  => $order->total_price,
                'updated_by'  => $user_id,
            ]);
            
            $OrderHistory = OrderHistory::select('id')->orderBy('id', 'DESC')->first();
            $TaxAmount = TaxAmount::where('id', 1)->select('shipping_tax', 'product_tax')->first();
            $product_tax = $TaxAmount->product_tax;
            
            $new_pro_price = 0;
            $product_tax_price = 0;

            if(isset($request->product_id)){
                foreach ($request->product_id as $product_id) {

                    $pro_history = OrderProduct::where('id', $product_id)->select('quantity', 'product_id', 'price')->first();

                    OrderProductsHistory::create([
                        'order_history_id'    => $OrderHistory->id,
                        'order_product_id'  => $product_id,
                        'qty'  => $pro_history->quantity,
                    ]);

                    OrderProduct::where('id', $product_id)->update([
                        'quantity' => $request->quantity[$product_id],
                    ]);

                    $new_pro_price += $request->quantity[$product_id]*$pro_history->price;
                    $product = Product::where('id', $pro_history->product_id)->select('tax')->first();

                    if($product){
                        if($product->tax == 1){
                            $p_price = $request->quantity[$product_id]*$pro_history->price;
                            $product_tax_price += ($p_price/100)*$product_tax;
                        }
                    }
                }
            }

    $total_price = $product_tax_price+$new_pro_price+$order->shipping_tax+$order->shipping_price; 
        
        Order::where('id', $request->order_id)->update([
            'total_price' => $total_price,
            'product_tax' => $product_tax_price,
        ]);


        return back()->with('order_edit', 'order_edit');

        } catch (\Exception $exception) {
          // dd($exception);
            return back();
        }
    }


    public function edit($id = null) {
        $result = (new Order)->find($id);
        if (!$result) {
            abort(401);
        }
        $user_name = User::where('id', $result->user_id)->first();
        $shipping = OrderAddress::where('id', $result->shipping_id)->first();
        $offer = Offer::where('id', $result->offer_id)->first();
        $OrderProduct = \DB::table('order_products')
        ->join('products', 'order_products.product_id', '=', 'products.id')
        ->select('order_products.*', 'products.name', 'products.thumbnail', 'products.sku', 'products.regular_price')
        ->where('order_products.order_id', $result->id)
        ->get();
      
        $orderStatus = \DB::table('order_product_statuses')
            ->join('order_statuses', 'order_product_statuses.status', '=', 'order_statuses.id')
            ->select('order_product_statuses.status as order_status','order_product_statuses.created_at as date', 'order_statuses.type as type')
            ->where('order_product_statuses.order_id', $id)
            ->get();
        
        $statusType = OrderStatus::where('status', 1)->get();

        $order_history = OrderHistory::where('order_id', $id)->select('id', 'last_price', 'created_at')->orderBy('id', 'desc')->get(); 

        return view('admin.order.create', compact('result','user_name','shipping','offer','OrderProduct', 'id', 'statusType', 'orderStatus', 'order_history'));
   
    }

 
    public function orderPaginate(Request $request, $pageNumber = null)
    {

        if (!\Request::isMethod('post') && !\Request::ajax()) { //
            return lang('messages.server_error');
        }

        $user_type = \Auth::user()->user_type;
        $user_id =  Auth::id();

        $inputs = $request->all();
        $page = 1;
        if (isset($inputs['page']) && (int)$inputs['page'] > 0) {
            $page = $inputs['page'];
        }

        $perPage = 100;
        if (isset($inputs['perpage']) && (int)$inputs['perpage'] > 0) {
            $perPage = $inputs['perpage'];
        }

        $start = ($page - 1) * $perPage;
        if (isset($inputs['form-search']) && $inputs['form-search'] != '') {
            $inputs = array_filter($inputs);
            unset($inputs['_token']);

            $data = (new Order)->getOrder($inputs, $start, $perPage, $user_type, $user_id);
            $totalOrder = (new Order)->totalOrder($inputs, $user_type, $user_id);
            $total = $totalOrder->total;
        } else {

            $data = (new Order)->getOrder($inputs, $start, $perPage, $user_type, $user_id);
            $totalOrder = (new Order)->totalOrder('', $user_type, $user_id);
            $total = $totalOrder->total;
        }

        $statusType = OrderStatus::where('status', 1)->get();

       // dd($statusType);

        return view('admin.order.load_data', compact('inputs', 'data', 'total', 'page', 'perPage', 'statusType'));
    }


    public function orderToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $user_id = Auth::id();
            
            Order::where('id', $id)
            ->update([
            'updated_by' =>  $user_id,
            ]);

            $game = Order::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('order.order')));
        }

        $game->update(['status' => !$game->status]);

        

        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
        // return json response
        return json_encode($response);
    }


    public function orderAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('order.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('order.order'))));
        }

        $ids = '';
        foreach ($inputs['tick'] as $key => $value) {
            $ids .= $value . ',';
        }

        $ids = rtrim($ids, ',');
        $status = 0;
        if (isset($inputs['active'])) {
            $status = 1;
        }

        Order::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('order.index')
            ->with('success', lang('messages.updated', lang('order.order')));
    }


    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new Order)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new Order)->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('order.order_in_use')];
             }
             else {
                 (new Order)->tempDelete($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('order.order'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }

    public function orderStatus($id = null)
    {
        $result = (new Order)->find($id);
        if (!$result) {
            abort(401);
        }

        $orderStatus = \DB::table('order_product_statuses')
            ->join('order_statuses', 'order_product_statuses.status', '=', 'order_statuses.id')
            ->select('order_product_statuses.status as order_status','order_product_statuses.created_at as date', 'order_statuses.type as type')
            ->where('order_product_statuses.order_id', $id)
            ->get();
        
        $statusType = OrderStatus::where('status', 1)->get();

        return view('admin.order.order_status', compact('result','orderStatus', 'statusType', 'id'));
    }

     public function  orderProductStatus(Request $request)
    {
      try {  

            $id = $request['order_id'];
            $noti_text = "";
            $order = Order::where('id', $id)->select('user_id', 'order_nr')->first();

            if($request['status'] == 4 || $request['status'] == 5) {
              if($request->transaction_id){
              
              // $user = User::where('id', $order->user_id)->select('name')->first();
              // $phone = OrderAddress::where('order_id', $id)->select('mobile')->first();
                OrderProductStatus::create([
                'order_id' =>  $request['order_id'],
                'status' =>  $request['status']
               ]);
               
              $otp = rand(100000, 999999);
                Order::where('id', $id)
                    ->update([
                    'current_status' =>  $request['status'],
                    'transaction_id' =>  $request['transaction_id'],
                    // 'delivery_boy_asign_by' =>  Auth::id(),
                    // 'delivery_otp' =>  $otp,
                ]);
                // $customer = User::where('id', ) 
                // $mobile = $phone->mobile; 
                // $message = 'Dear '.$user->name.', Your order is out for delivery.  '.$boy->name.' ('.$boy->unique_id.') is getting your order soon to you. You can call '.$boy->name.' at '.$boy->mobile.'. At the time of receving the order kindly share the  OTP '.$otp;
                // sendSms($mobile, $message); 
                //$c_o_n =  str_replace("#","", $order->order_nr);
                // $deliver_mobile =  $boy->mobile;
                // $message = 'Dear '.$boy->name.', you have been asigned order no '.$c_o_n.' to be delivered.';
                // sendSms($deliver_mobile, $message); 
              }  else {
                 return back()->with('transaction', 'transaction');
              }
            } else {
                OrderProductStatus::create([
                'order_id' =>  $request['order_id'],
                'status' =>  $request['status']
               ]);
                Order::where('id', $id)
                    ->update([
                    'current_status' =>  $request['status']
                ]);
            }

            if($request['status'] == 2){
                $noti_text = 'Your order no. '.$order->order_nr.' has been shipped.'; 
            }
            if($request['status'] == 5){
                $noti_text = 'Your order no. '.$order->order_nr.' is delivered successfully.';
            }
            if($request['status'] == 4){
                $noti_text = 'Your order no. '.$order->order_nr.' is out for delivery.';
            }
            if($request['status'] == 3){
                $noti_text = 'Your order no. '.$order->order_nr.' is in transit.';
            }
            if($request['status'] == 8){
                $noti_text = 'Your order no. '.$order->order_nr.' is Cancelled.';
            }
            if($request['status'] == 7){
                $noti_text = 'Your order no. '.$order->order_nr.' is Failed.';
            }
            if($request['status'] == 6){
                $noti_text = 'Your order no. '.$order->order_nr.' is Declined.';
            }

            $o_pro = OrderProduct::where('order_id', $id)->select('product_id')->first(); 
            $parent_pro = Product::where('id', $o_pro->product_id)->select('thumbnail')->first(); 
            $image_pro = @$parent_pro->thumbnail;
            if($noti_text){
                $Notification = new Notification();
                $Notification->message = $noti_text;
                $Notification->user_id = $order->user_id;
                $Notification->image   = $image_pro;
                $Notification->type    = "Order";
                $Notification->type_id = $id;
                $Notification->save();
            }


     return redirect()->route('order.index');
   }   catch (Exception $exception) {
         //  dd($exception);
             return back();
        }
    }


  function sendGCM($message, $id) {

    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array (
            'registration_ids' => array (
                    $id
            ),
          
            'notification' => array(
                "body" => $message,
                "title" => "title for notification",
        )
    );
    $fields = json_encode ( $fields );

    $headers = array (
            'Authorization: key=' . "AAAAjXmMp4Q:APA91bEp9EUeurq1YEckd4Dqk_X6bvr6Toz16OLRITScYI2TOt4bQuSuwxHCjeTMu0ebQJ-8n6OI6kFh7vUCgDq7qgR7G4rFGu85S9oHqM1fff2ntfr-L9Tuwcezpul6ppAz73HoDuot",
            'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
    $result = curl_exec ( $ch );
   //dd($result);
    curl_close ( $ch );
  }  

  public function orderRecord(request $request){

    try {
     
      $inputs = $request->all();

      $validator = (new Order)->recordvalidate($inputs);
          if( $validator->fails() ) {
              return back()->withErrors($validator)->withInput();
          }
          // dd($request['to']);
          $to = date('Y-m-d', strtotime($request['to']));
          $from = date('Y-m-d', strtotime($request['from']));
          // dd($to);

          // $data['orders'] =  \DB::table('orders')
          //   ->join('users', 'users.id', '=', 'orders.user_id')
          //   ->join('order_statuses', 'order_statuses.id', '=', 'orders.current_status')
          //   ->whereRaw('date_format(orders.created_at,"%Y-%m-%d")'.">='".$from . "'")
          //   ->whereRaw('date_format(orders.created_at,"%Y-%m-%d")'."<='".$to . "'")
          //   ->select('orders.created_at as created_at','orders.order_nr as order_nr', 'users.name as name', 'orders.total_price', 'order_statuses.type', 'orders.payment_method')
          //   ->get();
          // $pdf = \PDF::loadView('pdf.order', $data);
          // return $pdf->download('order.pdf'); 


            $orders =  \DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('order_statuses', 'order_statuses.id', '=', 'orders.current_status')
            ->whereRaw('date_format(orders.created_at,"%Y-%m-%d")'.">='".$from . "'")
            ->whereRaw('date_format(orders.created_at,"%Y-%m-%d")'."<='".$to . "'")
            ->select('orders.created_at as created_at','orders.order_nr as order_nr', 'users.name as name', 'orders.total_price', 'order_statuses.type', 'orders.transaction_id', 'orders.card_no', 'orders.card_holder', 'orders.card_type', 'orders.expiration_month', 'orders.expiration_year', 'orders.card_security_code')
            ->get();

            \Excel::create('orders', function($excel) use($orders) {
            $excel->sheet('order', function($sheet) use($orders) {
                $excelData = [];
                $excelData[] = [
                'Name',
                'Order No',
                'Order Date',
                'Amount',
                'Order Status',
                
                'Card No',
                'Card Holder',
                'Card Type',
                'Expiration date',
                'Card Security Code',

                ];
                foreach ($orders as $key => $value) {
                $excelData[] = [
                $value->name,
                $value->order_nr,
                date("M d Y", strtotime($value->created_at)),
                $value->total_price,
                $value->type,
                $value->card_no,
                $value->card_holder,
                $value->card_type,
                $value->expiration_month.',' .$value->expiration_year,
                $value->card_security_code,

                ]; 
                }
                $sheet->fromArray($excelData, null, 'A1', true, false);
            });
            })->download('xlsx');



        }

        catch (Exception $exception) {
           // dd($exception);
             return back();
        }

      // dd($orders);

  }


  public function bill_generate($id){

    try {
          $user_id =  Auth::id();

          $data['order'] = \DB::table('orders')
          ->join('order_statuses', 'order_statuses.id', '=', 'orders.current_status')
          ->join('order_addresses', 'order_addresses.id', '=', 'orders.shipping_id')
          ->join('order_billings', 'order_billings.id', '=', 'orders.billing_id')
          ->select('orders.id','orders.order_nr', 'orders.total_price', 'orders.wallet_paid', 'order_statuses.type', 'order_addresses.name', 'order_addresses.address', 'order_addresses.state', 'order_addresses.city', 'order_addresses.pincode', 'orders.tax', 'orders.shipping_charges', 'orders.discount', 'orders.payment_method', 'orders.created_at', 'order_billings.name as billing_name', 'order_billings.address as billing_address', 'order_billings.mobile as billing_mobile', 'order_billings.state as billing_state', 'order_billings.city as billing_city', 'order_billings.pincode as billing_pincode')
          ->where('orders.id', $id)
          ->first();
      
        
        //dd($data);

          $pdf = \PDF::loadView('pdf.order_invoice', $data);
          return $pdf->download('order_invoice.pdf'); 


        } catch(\Exception $exception){

     // dd($exception);
        return back();
      }

  }




}
