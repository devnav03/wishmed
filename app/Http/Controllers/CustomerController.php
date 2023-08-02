<?php

namespace App\Http\Controllers;

//use App\Http\Controllers\Controller;
use App\User;
use App\Models\DataEntry;
use Illuminate\Http\Request;
use App\Models\UserType;
use App\Models\Contact;
use App\Models\BillingAddress;
use App\Models\UserAddress;
use App\Models\CaseDeal;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\OrderAddress;
use App\Models\Category;
use App\Models\Order;
use App\Models\ConfigureProduct;
use App\Models\SpecialPrice;
use League\Flysystem\Exception;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller {
   
    public function index() {
        if((\Auth::user()->user_type) == 1){
            return view('admin.customer.index');
        } else {
            \Auth::logout();
            \Session::flush();
            return redirect()->route('admin');
        }
    }

    public function admin_users() {
      
        return view('admin.customer.admin');

    }
    

    public function customerdataentry() {
  
        return view('admin.customer.data_entry');
    }

    public function create() {
        return view('admin.customer.create');
    }

    public function ImportProducts($user_id = null){

        $user = User::where('id', $user_id)->select('name', 'email')->first();

        $products = Product::where('status', 1)->where('product_type', 1)->select('id', 'name', 'sku', 'offer_price', 'regular_price')->get();

        return view('admin.customer.customer_product', compact('user', 'products', 'user_id'));
    }

    public function updateProducts(Request $request){
        try {
            $up = 0;
            if(isset($request->product_id)){
                foreach ($request->product_id as $product_id) {
                    if($request->price[$product_id]){
                        $check_exist = SpecialPrice::where('user_id', $request->user_id)->where('product_id', $product_id)->select('id')->first();
                        if($check_exist){
                            $up++;
                            SpecialPrice::where('id', $check_exist->id)
                                ->update([
                                'price' => $request->price[$product_id],
                            ]);
                        } else {
                            SpecialPrice::create([
                                'product_id' => $product_id,
                                'price'      => $request->price[$product_id],
                                'user_id'    => $request->user_id,
                            ]);
                        }
                    }
                }
            }

            if($up == 0){
               return redirect()->route('customer')->with('success', lang('messages.created', lang('customer.customer')));
            } else {
                return redirect()->route('customer')->with('success', lang('messages.updated', lang('customer.customer')));
            }

        } catch (Exception $exception) {
            return back();
        }
    }

    public function store( Request $request ){

        $request['unique_id'] = mt_rand(100000,999999);
        $inputs = $request->all(); 

        $validator = (new User)->validate($inputs);
        if ($validator->fails()) {
            return redirect()->route('customer.create')
            ->withInput()->withErrors($validator);
        }            
        
        try{

            $data['password'] = random_int(100000, 999999);

            $inputs['password'] = $data['password'];

            $pwd = $inputs['password'];
            $password = \Hash::make($inputs['password']);
            unset($inputs['password']);
            $inputs = $inputs + ['password' => $password];
            // Generating API key

            $name = $request->first_name .' '. $request->last_name;
            $data['name'] = $name;
            $email = $inputs['email'];
            $data['email'] = $email;

            $remember_token = $this->generateTokenKey();
            $inputs = $inputs + [
                        'remember_token'  => $remember_token,
                        'name'  => $name,
                        'created_by'  => authUserId()
                    ];

            $user_id = (new User)->store($inputs); 

            // \Mail::send('email.user_reg', $data, function($message) use ($email){
            //     $message->from('navjot@shailersolutions.com');
            //     $message->to($email);
            //     $message->subject('New Account Created With Wishmed');
            // }); 

            
    $postdata = http_build_query(
        array(
            'name' => $name,
            'email' => $email,
            'sent_to' => $email,
            'password' => $data['password'],
        )
    );

    $opts = array('http' =>
        array(
          'method'  => 'POST',
          'header'  => 'Content-Type: application/x-www-form-urlencoded',
          'content' => $postdata
        )
    );
    $context  = stream_context_create($opts);
    $result = file_get_contents('https://sspl20.com/email-api/api/new-user', false, $context);


            if($request->user_type == 2) {
                return redirect()->route('customer.products', $user_id);
            } 

            if($request->user_type == 3) {
                return view('admin.customer.admin')->with('success', lang('messages.created', lang('customer.customer')));
            }  
        } catch (Exception $exception) {
         //   dd($exception);
            return redirect()->route('customer.create')
                ->withInput()
                ->with('error', lang('messages.server_error'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id = null)
    {
        $result = User::find($id);
        $user_type = $result->user_type;

        if (!$result) {

            return redirect()->route('customer.index')
                ->with('error', lang('messages.invalid_id', string_manip(lang('customer.customer'))));
        }

        $inputs = $request->all();
        $validator = (new User)->validate_update($inputs, $id);
        if ($validator->fails()) {
            return redirect()->route('customer.edit',[$id])
            ->withInput()->withErrors($validator);
        } 

        try {
             
             $name = $request->first_name .' '. $request->last_name;
             $inputs = $inputs + [
                'name'  => $name,
                'updated_by'=> authUserId()
              ];
          
            (new User)->store($inputs, $id); 

        if($request->user_type == 2) {
            return redirect()->route('customer.products', $id);
        }

        if($request->user_type == 3) {
          return redirect()->route('admin_users')
                ->with('success', lang('messages.updated', lang('customer.customer')));
        }
      
        } catch (\Exception $exception) {

        //  dd($exception);

            return redirect()->route('customer.edit',[$id])
                ->with('error', lang('messages.server_error'));
 
        }
    }
    

    private function generateTokenKey() {
        return md5(uniqid(rand(), true));
    }

    public function edit($id = null)
    {
        $result = User::find($id);
        if (!$result) {
            abort(404);
        }
   
      
      // $user_address = UserAddress::where('user_id', $id)->get();

      $Orders = \DB::table('orders')
        ->join('order_statuses', 'orders.current_status', '=', 'order_statuses.id')
        ->select('orders.id','orders.user_id', 'orders.order_nr','orders.total_price', 'order_statuses.type', 'orders.status')->where('orders.user_id', $id)->Orderby('id', 'DESC')->limit(10)->get(); 


       $user_address = \DB::table('user_addresses')
       ->select('user_addresses.*')
       ->where('user_addresses.user_id', $id)
       ->get();

     //   $UserType = UserType::where('id', $result->user_type)->first()->type;

       if(((\Auth::user()->user_type)) == 1 || ((\Auth::user()->user_type)) == 3){
         return view('admin.customer.create', compact('result', 'Orders', 'user_address'));
      } else {
        echo "Wrong Url";
      }

    }


    public function drop($id) {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new User)->find($id);
        if (!$result) {
            // use ajax return response not abort because ajaz request abort not works
            abort(401);
        }

        try {

            $result = (new User)->find($id);
          
            // (new User)->tempDelete($id);
            
            \DB::table('users')->where('id', $id)->delete();

            $response = ['status' => 1, 'message' => lang('messages.deleted', lang('User'))];
             
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }

    
    public function getUserDetail() {
        try {
            if(\Auth::check()) {

                $user =User::where('id',\Auth::user()->id)->first();
                if( $user){
                    
                    return apiResponse(true, 200 , null, [], $user);
                }
                return apiResponse(false, 404, lang('messages.not_found', lang('user.user')));
            }
            else {
                return apiResponse(false, 404, lang('auth.customer_not_accessible'));
            }
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

 
    public function changePwd(Request $request) {
        try {
            $id=\Auth::user()->id;
            \DB::beginTransaction();
            /* FIND WHETHER THE USER EXISTS OR NOT */
            $user = User::find($id);
            if(!$user) {
                return apiResponse(false, 404, lang('messages.not_found', lang('user.user')));
            }
            $inputs = $request->all();
            $rules = [
                    'password' => 'required',
                    'new_password'=>'required|min:6'
                    ];
            $validator=\Validator::make($inputs, $rules);
            if ($validator->fails()) {
                return apiResponse(false, 406, "", errorMessages($validator->messages()));
            }
      
                if (!\Hash::check($inputs['password'], \Auth::user()->password) ){
                    return apiResponse(false, 406,lang('user.password_not_match'));
                }

                $password = \Hash::make($inputs['new_password']);
                unset($inputs['password']);
                $inputs = $inputs + ['password' => $password];
                
                (new User)->store($inputs, $id);
                \DB::commit();
                return apiResponse(true, 200, lang('messages.updated', lang('user.user')));
           
        } catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

 
    public function customerPaginate(Request $request, $id, $pageNumber = null)
    {

        if (!\Request::isMethod('post') && !\Request::ajax()) { //
            return lang('messages.server_error');
        }

        $inputs = $request->all();
        $page = 1;
        if (isset($inputs['page']) && (int)$inputs['page'] > 0) {
            $page = $inputs['page'];
        }

        $perPage = 20;
        if (isset($inputs['perpage']) && (int)$inputs['perpage'] > 0) {
            $perPage = $inputs['perpage'];
        }

        $start = ($page - 1) * $perPage;
        if (isset($inputs['form-search']) && $inputs['form-search'] != '') {
            $inputs = array_filter($inputs);
            unset($inputs['_token']);

            $data = (new User)->getCustomer($inputs, $start, $perPage);
            $totalGameMaster = (new User)->totalCustomer($inputs);
            $total = $totalGameMaster->total;
        } else {

            $data = (new User)->getCustomer($inputs, $start, $perPage, $id);
            $totalGameMaster = (new User)->totalCustomer();
            $total = $totalGameMaster->total;
        }

        return view('admin.customer.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

    // Dealer Pagination Start

    public function customerPaginate_dealer(Request $request, $id, $pageNumber = null)
    {

        if (!\Request::isMethod('post') && !\Request::ajax()) { //
            return lang('messages.server_error');
        }

        $inputs = $request->all();
        $page = 1;
        if (isset($inputs['page']) && (int)$inputs['page'] > 0) {
            $page = $inputs['page'];
        }

        $perPage = 20;
        if (isset($inputs['perpage']) && (int)$inputs['perpage'] > 0) {
            $perPage = $inputs['perpage'];
        }

        $start = ($page - 1) * $perPage;
        if (isset($inputs['form-search']) && $inputs['form-search'] != '') {
            $inputs = array_filter($inputs);
            unset($inputs['_token']);

            $data = (new Dealer)->getCustomer($inputs, $start, $perPage);
            $totalGameMaster = (new Dealer)->totalCustomer($inputs);
            $total = $totalGameMaster->total;
        } else {

            $data = (new Dealer)->getCustomer($inputs, $start, $perPage, $id);
            $totalGameMaster = (new Dealer)->totalCustomer();
            $total = $totalGameMaster->total;
        }

        return view('admin.customer.load_data1', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

    // Dealer Pagination End


    // Data Entry Pagination Start

    public function customerPaginate_data_entry(Request $request, $id, $pageNumber = null)
    {

        if (!\Request::isMethod('post') && !\Request::ajax()) { //
            return lang('messages.server_error');
        }

        $inputs = $request->all();
        $page = 1;
        if (isset($inputs['page']) && (int)$inputs['page'] > 0) {
            $page = $inputs['page'];
        }

        $perPage = 20;
        if (isset($inputs['perpage']) && (int)$inputs['perpage'] > 0) {
            $perPage = $inputs['perpage'];
        }

        $start = ($page - 1) * $perPage;
        if (isset($inputs['form-search']) && $inputs['form-search'] != '') {
            $inputs = array_filter($inputs);
            unset($inputs['_token']);

            $data = (new User)->getAdmin($inputs, $start, $perPage);
            $totalGameMaster = (new User)->totalAdmin($inputs);
            $total = $totalGameMaster->total;
        } else {

            $data = (new User)->getAdmin($inputs, $start, $perPage, $id);
            $totalGameMaster = (new User)->totalAdmin();
            $total = $totalGameMaster->total;
        }

        return view('admin.customer.load_data1', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

    // Data Entry Pagination End



    /**
     * code for toggle - financial year status
     * @param null $id
     * @return string
     */

    public function customerToggle($id = null)
    {
         if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $game = User::find($id);
            //dd($game);



        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('Order')));
        }

        $game->update(['status' => !$game->status]);
        $response = ['status' => 1, 'data' => (int)$game->status . '.gif'];
 

        // return json response
        return json_encode($response);
    }

    /**
     * Method is used to update status of group enable/disable
     *
     * @return \Illuminate\Http\Response
     */
    public function customerAction(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            // return redirect()->route('customer.index')
             return view('admin.customer.index')->with('error', lang('messages.atleast_one', string_manip(lang('customer.customer'))));
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

        User::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('customer.index')
            ->with('success', lang('messages.updated', lang('game_master.game')));
    }


    public function customerAction_data_entry(Request $request)
    {
        $inputs = $request->all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            // return redirect()->route('customer.index')
             return view('admin.customer.admin')->with('error', lang('messages.atleast_one', string_manip(lang('customer.customer'))));
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

        User::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('admin_users')
            ->with('success', lang('messages.updated', lang('game_master.game')));
    }
    

     public function customerRecord(request $request){

    try {
     
      $inputs = $request->all();
      $validator = (new User)->recordvalidate($inputs);
          if( $validator->fails() ) {
              return back()->withErrors($validator)->withInput();
          }
          $to = date('Y-m-d', strtotime($request['to']));
          $from = date('Y-m-d', strtotime($request['from']));

         // $data['orders'] =  User::whereRaw('date_format(users.created_at,"%Y-%m-%d")'.">='".$from . "'")
         //    ->whereRaw('date_format(users.created_at,"%Y-%m-%d")'."<='".$to . "'")->select('name', 'email', 'mobile')->get();
         // $pdf = \PDF::loadView('pdf.user', $data);
         //  return $pdf->download('user.pdf'); 

          $users =  User::whereRaw('date_format(users.created_at,"%Y-%m-%d")'.">='".$from . "'")
            ->whereRaw('date_format(users.created_at,"%Y-%m-%d")'."<='".$to . "'")->select('name', 'email', 'mobile', 'status', 'created_at')->get();

          \Excel::create('users', function($excel) use($users) {
            $excel->sheet('customer', function($sheet) use($users) {
                $excelData = [];
                $excelData[] = [
                'Name',
                'Email',
                'Mobile',
                'Status',
                'Created At',
                ];
                foreach ($users as $key => $value) {
                $excelData[] = [
                $value->name,
                $value->email,
                $value->mobile,
                $value->status  == 1 ? 'Active' : 'Inactive',
                date("M d Y", strtotime($value->created_at)),
                ]; 
                }
                $sheet->fromArray($excelData, null, 'A1', true, false);
            });
            })->download('xlsx');

        }
        catch (Exception $exception) {
           // dd($exception);
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }
  }


  public function export_users(){
     
     try{
          $users = User::Orderby('created_at', 'desc')->where('id', '!=', 1)->get();
        

            \Excel::create('users', function($excel) use($users) {
            $excel->sheet('customer', function($sheet) use($users) {
                $excelData = [];
                $excelData[] = [
                'Name',
                'Email',
                'Mobile',
                'Status',
                'Created At',
                ];
                foreach ($users as $key => $value) {
                $excelData[] = [
                $value->name,
                $value->email,
                $value->mobile,
                $value->status  == 1 ? 'Active' : 'Inactive',
                date("M d Y", strtotime($value->created_at)),
                ]; 
                }
                $sheet->fromArray($excelData, null, 'A1', true, false);
            });
            })->download('xlsx');

     } catch(Exception $exc){
     // dd($exc);

       $response = ['status' => 0, 'message' => lang('messages.server_error')];

     }

 }


 public function export_tax(){
     
     try{
          $users = Tax::Orderby('created_at', 'desc')->get();
        

            \Excel::create('tax', function($excel) use($users) {
            $excel->sheet('contact', function($sheet) use($users) {
                $excelData = [];
                $excelData[] = [
                'Name',
                'Value',
                'Created At',
                ];
                foreach ($users as $key => $value) {
                $excelData[] = [
                $value->name,
                $value->value,
                date("M d Y", strtotime($value->created_at)),
                ]; 
                }
                $sheet->fromArray($excelData, null, 'A1', true, false);
            });
            })->download('xlsx');

     } catch(Exception $exc){
     // dd($exc);

       $response = ['status' => 0, 'message' => lang('messages.server_error')];

     }

 }

 public function export_brand(){
 
     try{
          $users = Brand::Orderby('created_at', 'desc')->get();
        

            \Excel::create('brand', function($excel) use($users) {
            $excel->sheet('contact', function($sheet) use($users) {
                $excelData = [];
                $excelData[] = [
                'Name',
                'Created At',
                ];
                foreach ($users as $key => $value) {
                $excelData[] = [
                $value->name,
                date("M d Y", strtotime($value->created_at)),
                ]; 
                }
                $sheet->fromArray($excelData, null, 'A1', true, false);
            });
            })->download('xlsx');

     } catch(Exception $exc){
     // dd($exc);

       $response = ['status' => 0, 'message' => lang('messages.server_error')];

     }


 }

public function export_size(){
 
     try{
          $users = Size::Orderby('created_at', 'desc')->get();
        

            \Excel::create('size', function($excel) use($users) {
            $excel->sheet('contact', function($sheet) use($users) {
                $excelData = [];
                $excelData[] = [
                'Size',
                'Created At',
                ];
                foreach ($users as $key => $value) {
                $excelData[] = [
                $value->size,
                date("M d Y", strtotime($value->created_at)),
                ]; 
                }
                $sheet->fromArray($excelData, null, 'A1', true, false);
            });
            })->download('xlsx');

     } catch(Exception $exc){
     // dd($exc);

       $response = ['status' => 0, 'message' => lang('messages.server_error')];

     }

 }


public function export_category(){
 
     try{
          $users = Category::Orderby('created_at', 'desc')->get();
        

            \Excel::create('category', function($excel) use($users) {
            $excel->sheet('contact', function($sheet) use($users) {
                $excelData = [];
                $excelData[] = [
                'Name',
                'Created At',
                ];
                foreach ($users as $key => $value) {
                $excelData[] = [
                $value->name,
                date("M d Y", strtotime($value->created_at)),
                ]; 
                }
                $sheet->fromArray($excelData, null, 'A1', true, false);
            });
            })->download('xlsx');

     } catch(Exception $exc){
     // dd($exc);

       $response = ['status' => 0, 'message' => lang('messages.server_error')];

     }

 }
 


public function export_style(){
 
     try{
          $users = Style::Orderby('created_at', 'desc')->get();
        

            \Excel::create('style', function($excel) use($users) {
            $excel->sheet('contact', function($sheet) use($users) {
                $excelData = [];
                $excelData[] = [
                'Name',
                'Created At',
                ];
                foreach ($users as $key => $value) {
                $excelData[] = [
                $value->style,
                date("M d Y", strtotime($value->created_at)),
                ]; 
                }
                $sheet->fromArray($excelData, null, 'A1', true, false);
            });
            })->download('xlsx');

     } catch(Exception $exc){
     // dd($exc);

       $response = ['status' => 0, 'message' => lang('messages.server_error')];

     }

 }

 

public function export_order(){
 
     try{
          $users = \DB::table('orders')
            ->join('order_statuses', 'order_statuses.id' ,'=', 'orders.current_status')
            ->join('users', 'users.id' ,'=', 'orders.user_id')
            ->join('order_addresses', 'order_addresses.id' ,'=', 'orders.shipping_id')
            ->join('countries', 'countries.id' ,'=', 'order_addresses.country')
            ->select('orders.id','orders.user_id', 'orders.order_nr', 'orders.total_price',
             'orders.payment_method','orders.current_status', 'orders.status',
            'order_statuses.type as current_status','users.name as user_name', 'users.email', 'orders.created_at', 'orders.order_from', 'orders.pay_later',
            'order_addresses.name as shipping_name', 'order_addresses.address as shipping_address', 'order_addresses.mobile as shipping_mobile', 'countries.country_name as shipping_country'
            , 'order_addresses.company_name as shipping_company', 'order_addresses.state as shipping_state', 'order_addresses.city as shipping_city', 
            'order_addresses.pincode as shipping_pincode')
            ->Orderby('orders.created_at', 'desc')
            ->get();
        

            \Excel::create('orders', function($excel) use($users) {
            $excel->sheet('contact', function($sheet) use($users) {
                $excelData = [];
                $excelData[] = [
                'Customer Name',
                'Customer Email',
                'Order No.',
                'Price',
                'Status',
                'Payment Method',
                'Order Date',
                'Shipping Name',
                'Shipping Address',
                'Shipping Mobile',
                'Shipping Company',
                'Shipping Country',
                'Shipping State',
                'Shipping City',
                'Shipping Postcode',
                
                
                ];
                foreach ($users as $key => $value) {
                $Payment_Method = '';    
                if($value->pay_later == 1){ 
                    $Payment_Method = 'Pay Later';
                } 
                if($value->pay_later == 0){ 
                    $Payment_Method = 'Credit Card';
                }
                if($value->pay_later == 2){ 
                    $Payment_Method = 'Pay with Paypal Offline';
                }
                if($value->pay_later == 3){ 
                    $Payment_Method = 'Other';
                }
                $excelData[] = [
                $value->user_name,
                $value->email,
                $value->order_nr,
                $value->total_price,
                $value->current_status,
                $Payment_Method,
                date("M d Y", strtotime($value->created_at)),
                $value->shipping_name,
                $value->shipping_address,
                $value->shipping_mobile,
                $value->shipping_company,
                $value->shipping_country,
                $value->shipping_state,
                $value->shipping_city,
                $value->shipping_pincode,
                
                
                ]; 
                }
                $sheet->fromArray($excelData, null, 'A1', true, false);
            });
            })->download('xlsx');

     } catch(Exception $exc){
     // dd($exc);

       $response = ['status' => 0, 'message' => lang('messages.server_error')];

     }

 }



public function export_manufacture(){
 
     try{
          $users = Manufacture::Orderby('created_at', 'desc')->get();
        

            \Excel::create('manufacture', function($excel) use($users) {
            $excel->sheet('contact', function($sheet) use($users) {
                $excelData = [];
                $excelData[] = [
                'Name',
                'Created At',
                ];
                foreach ($users as $key => $value) {
                $excelData[] = [
                $value->name,
                date("M d Y", strtotime($value->created_at)),
                ]; 
                }
                $sheet->fromArray($excelData, null, 'A1', true, false);
            });
            })->download('xlsx');

     } catch(Exception $exc){
     // dd($exc);

       $response = ['status' => 0, 'message' => lang('messages.server_error')];

     }

 }



public function export_reserve(){
 
     try{

          $users= \DB::table('reserves')
          ->join('products', 'reserves.product_id', '=','products.id')
          ->select('products.name as product','reserves.*')
          ->get();
        

            \Excel::create('reserve', function($excel) use($users) {
            $excel->sheet('contact', function($sheet) use($users) {
                $excelData = [];
                $excelData[] = [
                'Name',
                'Product',
                'Email',
                'Phone',
                'Reserve Id',
                'Created At',
                ];
                foreach ($users as $key => $value) {
                $excelData[] = [
                $value->name,
                $value->product,
                $value->email,
                $value->mobile,
                $value->reserve_id,
                date("M d Y", strtotime($value->created_at)),
                ]; 
                }
                $sheet->fromArray($excelData, null, 'A1', true, false);
            });
            })->download('xlsx');

     } catch(Exception $exc){
     // dd($exc);

       $response = ['status' => 0, 'message' => lang('messages.server_error')];

     }

 }



public function export_color(){
 
     try{
          $users = Color::Orderby('created_at', 'desc')->get();
        

            \Excel::create('color', function($excel) use($users) {
            $excel->sheet('contact', function($sheet) use($users) {
                $excelData = [];
                $excelData[] = [
                'Name',
                'Created At',
                ];
                foreach ($users as $key => $value) {
                $excelData[] = [
                $value->name,
                date("M d Y", strtotime($value->created_at)),
                ]; 
                }
                $sheet->fromArray($excelData, null, 'A1', true, false);
            });
            })->download('xlsx');

     } catch(Exception $exc){
     // dd($exc);

       $response = ['status' => 0, 'message' => lang('messages.server_error')];

     }

 }



 public function export_enquiry(){
     
     try{
          $users = Contact::Orderby('created_at', 'desc')->get();
        

            \Excel::create('enquiry', function($excel) use($users) {
            $excel->sheet('contact', function($sheet) use($users) {
                $excelData = [];
                $excelData[] = [
                'Name',
                'Email',
                'Mobile',
                'Subject',
                'Message',
                'Created At',
                ];
                foreach ($users as $key => $value) {
                $excelData[] = [
                $value->first_name,
                $value->email,
                $value->phone,
                $value->subject,
                $value->message,
                date("M d Y", strtotime($value->created_at)),
                ]; 
                }
                $sheet->fromArray($excelData, null, 'A1', true, false);
            });
            })->download('xlsx');

     } catch(Exception $exc){
     // dd($exc);

       $response = ['status' => 0, 'message' => lang('messages.server_error')];

     }

 }

public function exportSubscribe(){

   try{
          $users = Subscriber::Orderby('created_at', 'desc')->get();
        

            \Excel::create('subscribe', function($excel) use($users) {
            $excel->sheet('contact', function($sheet) use($users) {
                $excelData = [];
                $excelData[] = [
                'Email',
                'Created At',
                ];
                foreach ($users as $key => $value) {
                $excelData[] = [
                $value->email,
                date("M d Y", strtotime($value->created_at)),
                ]; 
                }
                $sheet->fromArray($excelData, null, 'A1', true, false);
            });
            })->download('xlsx');

     } catch(Exception $exc){
     // dd($exc);

       $response = ['status' => 0, 'message' => lang('messages.server_error')];

     }

}

public function ImportCustomer(Request $request){
try{

if($request->has('file') && isset($request->file))
{

$excelFile = $request->file('file');
if(!in_array($excelFile->getClientOriginalExtension(), ['xls','xlsx'])){
return redirect()->route('customer.create')
->withInput()
->withErrors(['File must be in excel format']);
}

if($request->hasFile('file')){
$errorReport = [];

ini_set('max_execution_time', '500');
ini_set('memory_limit','-1');
\Excel::load($excelFile, function($reader) use ($errorReport) {
$excelData = [];
$insertdata = [];

$excelData = $reader->all();
$firstrow = $reader->first()->toArray();


if(!empty($errorReport)){

return redirect()->route('customer')->withErrors($errorReport);

}
else{
 
foreach ($excelData->toArray() as $key => $value) {

if(!empty($value['first_name'])) {
 
 $p_code = User::where('email', $value['user_email'])->first();
if($p_code){
} 
else{

    $pwd = $value['user_pass'];
   // $password = \Hash::make($value['password']);
    //unset($value['password']);
    $password = $pwd;
    $email = $value['user_email'];
    
$insertdata = [
'id'   => $value['customer_id'],    
'name'   => $value['first_name'] .' '. $value['last_name'],
'email'   => $value['user_email'],
'first_name'   => $value['first_name'],
'last_name'   => $value['last_name'],
'created_at'   => $value['user_registered'],
'password' => $password,
'user_type' => 2,
'status' => 1,
'created_by' => authUserId(),
];

$user_id = (new User)->store($insertdata);

if($value['shipping_address_1']){
$UserAddress = new UserAddress();
$UserAddress->name = $value['shipping_first_name'] .' '. $value['shipping_last_name'];
$UserAddress->address = $value['shipping_address_1'];
$UserAddress->mobile = $value['shipping_phone'];
$UserAddress->company_name = $value['shipping_company'];
$UserAddress->country = $value['shipping_country'];
$UserAddress->state = $value['shipping_state'];
$UserAddress->city = $value['shipping_city'];
//$UserAddress->pincode = $value['pincode'];
$UserAddress->pincode = $value['shipping_postcode'];
$UserAddress->user_id = $user_id;
$UserAddress->save();
}
if($value['billing_address_1']){
$BillingAddress = new BillingAddress();
$BillingAddress->name = $value['billing_first_name'] .' '. $value['billing_last_name'];
$BillingAddress->address = $value['billing_address_1'];
$BillingAddress->mobile = $value['billing_phone'];
$BillingAddress->billing_email = $value['billing_email'];
$BillingAddress->billing_company = $value['billing_company'];
$UserAddress->country = $value['billing_country'];
$BillingAddress->city = $value['billing_city'];
$BillingAddress->state = $value['billing_state'];
$BillingAddress->pincode = $value['billing_postcode'];
//$BillingAddress->gst_no = $value['gst_no'];
//$BillingAddress->pan_card_no = $value['pan_card_no'];
$BillingAddress->user_id = $user_id;
$BillingAddress->save();
}
}
}

}
}
});
}

 return redirect()->route('customer')
    ->with('success', lang('messages.created', lang('Customer')));
}

}
catch(\Exception $e){
//dd($e);
return back();
}
} 
   public function upload_customer(){
        return view('admin.customer.upload_customer');
    } 
    
public function ImportCategory(Request $request){
try{
if($request->has('file') && isset($request->file)) {
$excelFile = $request->file('file');
if(!in_array($excelFile->getClientOriginalExtension(), ['xls','xlsx'])){
return redirect()->route('category.create')
->withInput()
->withErrors(['File must be in excel format']);
}
if($request->hasFile('file')){
$errorReport = [];
ini_set('max_execution_time', '500');
ini_set('memory_limit','-1');
\Excel::load($excelFile, function($reader) use ($errorReport) {
$excelData = [];
$insertdata = [];
$excelData = $reader->all();
$firstrow = $reader->first()->toArray();
if(!empty($errorReport)){
    return redirect()->route('category.index')->withErrors($errorReport);
}
else{
foreach ($excelData->toArray() as $key => $value) {
if($value['parent'] == 0) {
 $category = Category::where('name', $value['name'])->first();
if($category){
} 
else{
    
$insertdata = [   
'name'   => $value['name'],
'url'   => $value['slug'],
'created_by' => authUserId(),
];

$category_id = (new Category)->store($insertdata);

}
}

}
}
});
}
 return redirect()->route('category.index')
    ->with('success', lang('messages.created', lang('Category')));
}

}
catch(\Exception $e){
//dd($e);
return back();
}
} 

    public function upload_category(){
        return view('admin.category.upload_category');
    }     
    
public function ImportProduct(Request $request){
try{
$update = 0;
$create = 0;
$dupli_count = [];
$dupli = [];
ini_set('max_execution_time', '500');
ini_set('memory_limit','-1');
if($request->has('file') && isset($request->file)) {
$excelFile = $request->file('file');
if(!in_array($excelFile->getClientOriginalExtension(), ['xls','xlsx'])){
    
    
   $csv = $request->file('file');
      $insert_data=[];
      $handle = fopen($csv,"r");
      $count=0;
      while (($row = fgetcsv($handle, 10000, ",")) != FALSE) //get row vales
      {
         // dd($row);
          
        if($count!=0){
            
        // if($row['11'] != ''){    
        //     $url = $row['11'];
        //     $info = pathinfo($url);
        //     $contents1 = @file_get_contents($url);
        //     if($contents1 === FALSE) {
        //     } else{
        //     $img = public_path('/uploads/featured_images/') . $info['basename'];
        //     file_put_contents($img, $contents1);
        //     }
        // }
              
            // $img_sort_name = str_replace("https://www.pukacreations.com/media/","",$row['11']);
            // $image = '/uploads/featured_images/'.$img_sort_name;
            
            $category = explode(",", $row['12']);
          //  dd($category);
        if(isset($category[0])){
            $cat_no = trim($category[0]);
            $cat_no = str_replace("\xA0", "", $cat_no);
           // $cat_info = Category::where('name', $cat_no)->select('id')->first();
            $cat_info = \DB::table('categories')->where('name', $cat_no)->select('id')->first();

           if($cat_info){
              $cat_1 = $cat_info->id;   
           } else {
            
            $cat_url = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $cat_no))); 

            $cat_no = trim($cat_no);  
            $cat_no = str_replace("\xA0", "", $cat_no);     
            $AddCategory = new Category;
            $AddCategory->name = $cat_no;
            $AddCategory->url = $cat_url;
            $AddCategory->image = '/uploads/category_images/no_img.jpg';
            $AddCategory->status = 1;
            $AddCategory->save();
    		$cat_1 =  $AddCategory->id;   
               
    //         return redirect()->route('product.index')
    // ->with('error', ''.$cat_no.' is not a valid category'); 

           }
       } else {
           return redirect()->route('product.index')->with('error', 'Kindly add a category for '.$row['0'].'.');
       }

       if(isset($category[1])){
           $cat_no = trim($category[1]);
           $cat_no = str_replace("\xA0", "", $cat_no);
            $cat_info = \DB::table('categories')->where('name', $cat_no)->select('id')->first();
           if($cat_info){
              $cat_2 = $cat_info->id;   
           } else {
               
            $cat_url = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $cat_no)));   
            $cat_no = trim($cat_no); 
            $cat_no = str_replace("\xA0", "", $cat_no);
            $AddCategory = new Category;
            $AddCategory->name = $cat_no;
            $AddCategory->url = $cat_url;
            $AddCategory->image = '/uploads/category_images/no_img.jpg';
            $AddCategory->status = 1;
            $AddCategory->save();
    		$cat_2 =  $AddCategory->id; 
               
           }
       } else {
           $cat_2 = 0;
       }
       if(isset($category[2])){
           $cat_no = trim($category[2]);
           $cat_no = str_replace("\xA0", "", $cat_no);
            $cat_info = \DB::table('categories')->where('name', $cat_no)->select('id')->first();
           if($cat_info){
              $cat_3 = $cat_info->id;   
           } else {
            $cat_url = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $cat_no)));   
            $cat_no = trim($cat_no); 
            $cat_no = str_replace("\xA0", "", $cat_no);
            $AddCategory = new Category;
            $AddCategory->name = $cat_no;
            $AddCategory->url = $cat_url;
            $AddCategory->image = '/uploads/category_images/no_img.jpg';
            $AddCategory->status = 1;
            $AddCategory->save();
    		$cat_3 =  $AddCategory->id;   
           }
       } else {
           $cat_3 = 0;
       }
       if(isset($category[3])){
           $cat_no = trim($category[3]);
           $cat_no = str_replace("\xA0", "", $cat_no);
            $cat_info = \DB::table('categories')->where('name', $cat_no)->select('id')->first();
           if($cat_info){
              $cat_4 = $cat_info->id;   
           } else {
            $cat_url = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $cat_no)));   
            $cat_no = trim($cat_no); 
            $cat_no = str_replace("\xA0", "", $cat_no);
            $AddCategory = new Category;
            $AddCategory->name = $cat_no;
            $AddCategory->url = $cat_url;
            $AddCategory->image = '/uploads/category_images/no_img.jpg';
            $AddCategory->status = 1;
            $AddCategory->save();
    		$cat_4 =  $AddCategory->id;   
           }
       } else {
           $cat_4 = 0;
       }
       if(isset($category[4])){
           $cat_no = trim($category[4]);
           $cat_no = str_replace("\xA0", "", $cat_no);
           $cat_info = \DB::table('categories')->where('name', $cat_no)->select('id')->first();
           if($cat_info){
              $cat_5 = $cat_info->id;   
           } else {
              $cat_url = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $cat_no)));   
              $cat_no = trim($cat_no); 
              $cat_no = str_replace("\xA0", "", $cat_no);
            $AddCategory = new Category;
            $AddCategory->name = $cat_no;
            $AddCategory->url = $cat_url;
            $AddCategory->image = '/uploads/category_images/no_img.jpg';
            $AddCategory->status = 1;
            $AddCategory->save();
    		$cat_5 =  $AddCategory->id;   
           }
        } else {
           $cat_5 = 0;
        }       
          
              if($row['0']!=''){
             
                $sku = $row['2'];
                $sku = str_replace("\xA0", "", $sku);

                $name =$row['0'];
                
                if('Publish' == $row['11']){
                   $status = 1; 
                } else {
                    $status = 0;
                }
                
                if($row['1'] == 'None'){
                    $tax = 0;
                } else {
                    $tax = 1;
                }

                if($row['5'] == 'Simple'){
                    $product_type = 1;
                } else {
                    $product_type = 2;
                }

                if($row['6'] == 'Yes'){
                    $is_featured = 1;
                } else {
                    $is_featured = 0;
                }

                $description = $row['7'];

                
                $description = str_replace("\xB0C", "", $description);

                $product_description = $row['8'];
                $product_description = str_replace("\xB0C", "", $product_description);
                $product_description = str_replace("\x96", "", $product_description);
                $product_description = str_replace("\x94", "", $product_description);
                $product_description = str_replace("\x99", "", $product_description);
                $product_description = str_replace("\xAE", "", $product_description);
                $product_description = str_replace("\xD7100", "", $product_description);
            

                $featured_image = '/uploads/featured_images/'.$sku.'.jpg';
                $thumbnail = '/uploads/featured_images/'.$sku.'.jpg';

                $group_name = $row['13'];
                
                $offer_price = $row['3']; 
                $offer_price = str_replace("\xA0\xA0\xA0\xA0", "", $offer_price);
                

                $regular_price = $row['4'];
                
                $category_id = $cat_1;
                $sub_category = $cat_2;
                $sub_sub_category = $cat_3;
                $four_lavel  = $cat_4;
                $five_lavel  = $cat_5;
                
                $meta_title = $row['9'];
                $meta_title = str_replace("\x92s", "", $meta_title);
                $meta_title = str_replace("\x99", "", $meta_title);
                $meta_title = str_replace("\xAE", "", $meta_title); 
                $meta_title = str_replace("\xD7100", "", $meta_title); 
                
                 
                
                

                $meta_description = $row['10'];

                $meta_description = str_replace("\x94", "", $meta_description);

                $created_by = \Auth::user()->id;
                $user_id=\Auth::user()->id;
                
                // if(in_array($row['3'], $dupli_count)){
                //   $dupli[] = $row['3'];
                // } else {
                //   $dupli_count[] = $row['3']; 
                // }
                
                $product = Product::where('sku', $sku)->select('id', 'name')->first();
                
                // dd($product);

                if($product){
                    
                    Product::where('id', $product->id)
                        ->update([
                        'sku'              =>  $sku,
                        'status'           =>  $status,
                        'tax'              =>  $tax,
                        'product_type'     => $product_type, 
                        'description'      =>  $description,
                        'product_description' =>  $product_description,
                        'offer_price'      =>  $offer_price,
                        'regular_price'    =>  $regular_price,
                        'category_id'      =>  $category_id,
                        'sub_category'     =>  $sub_category,
                        'sub_sub_category' =>  $sub_sub_category,
                        'four_lavel'       =>  $four_lavel,
                        'five_lavel'       =>  $five_lavel,
                        'is_featured'      =>  $is_featured,
                        'thumbnail'        =>  $thumbnail,
                        'featured_image'   =>  $featured_image,
                        'meta_title'       =>  $meta_title,
                        'meta_description' =>  $meta_description,
                        'created_by'       =>  $created_by,
                        
                    ]);

                    if($product_type == 1){
                        \DB::table('configure_products')->where('simple_id', $product->id)->delete(); 
                        if($row['13']) {
                            $group_product = Product::where('name', $group_name)->select('id')->first();
                            if($group_product){
                                ConfigureProduct::create([
                                  'group_id'  =>  $group_product->id,
                                  'simple_id'  => $product->id,
                                ]);
                            }
                        } 
                    }
                    
                    // if(!empty($case_discount) && !empty($case_qty)) {
                    //     $check_case = CaseDeal::where('quantity', $case_qty)->where('product_id', $product->id)->where('discount', $case_discount)->first();
                    //     if(empty($check_case)){
                    //           $case_deal = new CaseDeal();
                    //           $case_deal->quantity = $case_qty;
                    //           $case_deal->product_id =  $product->id;
                    //           $case_deal->discount =  $case_discount;
                    //           $case_deal->created_by = $user_id;
                    //           $case_deal->max_quantity = 100;
                    //           $case_deal->save();
                    //     }
                    // }
                    
                    $update++;
                    
                } else {
                    $create++;
                    $slug_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));    
                  
                  $check_sku = Product::where('sku', $sku)->select('name')->first();  
                  if($check_sku){
                    return redirect()->route('product.index')->with('error', ''.$check_sku->name.' already have same '.$sku.' SKU');  
                }
                    
                $Product = new Product();
                $Product->name = $name;
                $Product->sku =  $sku;
                $Product->url = $slug_name;
                $Product->status = $status;
                $Product->product_type = $product_type;
                $Product->offer_price = $offer_price;
                $Product->regular_price = $regular_price;
                $Product->category_id = $category_id;
                $Product->sub_category = $sub_category;
                $Product->sub_sub_category = $sub_sub_category;
                $Product->four_lavel = $four_lavel;
                $Product->five_lavel = $five_lavel;
                $Product->tax = $tax;
                $Product->is_featured = $is_featured;
                $Product->meta_title = $meta_title;
                $Product->meta_description = $meta_description;
                $Product->created_by = $created_by;
                $Product->description =  $description;
                $Product->product_description =  $product_description;
                $Product->thumbnail =  $thumbnail;
                $Product->featured_image =  $featured_image;
                $Product->save();
                $id = $Product->id;


                if($product_type == 1){
                    if($row['13']) {
                        $group_product = Product::where('name', $group_name)->select('id')->first();
                        if($group_product){
                            ConfigureProduct::create([
                              'group_id'  =>  $group_product->id,
                              'simple_id'  => $id,
                            ]);
                        }
                    } 
                }


                  
                   // if(!empty($case_discount) && !empty($case_qty)) {
                   //      $check_case = CaseDeal::where('quantity', $case_qty)->where('product_id', $id)->where('discount', $case_discount)->first();
                   //      if(empty($check_case)){
                   //            $case_deal = new CaseDeal();
                   //            $case_deal->quantity = $case_qty;
                   //            $case_deal->product_id =  $id;
                   //            $case_deal->discount =  $case_discount;
                   //             $case_deal->created_by = $user_id;
                              
                   //            $case_deal->max_quantity = 100;
                   //            $case_deal->save();
                   //      }
                   //  }
                    
                }
              }
             
              

          }
          $count++;

    } 
   // dd($dupli);
     return redirect()->route('product.index')
    ->with('success', ''.$update.' old products are updated and '.$create.' products are new created.');
// return redirect()->route('product.create')
// ->withInput()
// ->withErrors(['File must be in excel format']);


}
if($request->hasFile('file')){
$errorReport = [];

\Excel::load($excelFile, function($reader) use ($errorReport) {
$excelData = [];
$insertdata = [];
$excelData = $reader->all();
$firstrow = $reader->first()->toArray();
if(!empty($errorReport)){
    return redirect()->route('product.index')->withErrors($errorReport);
}
else{
foreach ($excelData->toArray() as $key => $value) {
  if($value['name']) {
 $product = Product::where('name', $value['name'])->first();
if($product){
    $id = $product->id;
     $category = explode(",", $value['category']);
       if(isset($category[0])){
           $cat_info = Category::where('name', $category[0])->select('id')->first();
           if($cat_info){
              $cat_1 = $cat_info->id;   
           } else {
              $cat_1 = 0;  
           }
       } else {
           $cat_1 = 0;
       }

       if(isset($category[1])){
           $cat_info = Category::where('name', $category[1])->select('id')->first();
           if($cat_info){
              $cat_2 = $cat_info->id;   
           } else {
              $cat_2 = 0;  
           }
       } else {
           $cat_2 = 0;
       }
       if(isset($category[2])){
           $cat_info = Category::where('name', $category[2])->select('id')->first();
           if($cat_info){
              $cat_3 = $cat_info->id;   
           } else {
              $cat_3 = 0;  
           }
       } else {
           $cat_3 = 0;
       }
       if(isset($category[3])){
           $cat_info = Category::where('name', $category[3])->select('id')->first();
           if($cat_info){
              $cat_4 = $cat_info->id;   
           } else {
              $cat_4 = 0;  
           }
       } else {
           $cat_4 = 0;
       }
       if(isset($category[4])){
           $cat_info = Category::where('name', $category[4])->select('id')->first();
           if($cat_info){
              $cat_5 = $cat_info->id;   
           } else {
              $cat_5 = 0;  
           }
       } else {
           $cat_5 = 0;
       }
       
if($value['status'] == 'publish'){
   $status = 1;
} else {
   $status = 0;
}
$sale_price = $value['offer_price'];
if(empty($sale_price)){
    $sale_price = $value['regular_price'];
}
$slug_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $value['name'])));
$insertdata = [   
'name'   => $value['name'],
'url'    =>  $slug_name,
'sku'    =>  $value['sku'],
'status' => $status,
'offer_price'   => $sale_price,
'regular_price' => $value['regular_price'],
'srp' => $value['srp'],
'category_id'   => $cat_1,
'sub_category'  => $cat_2,
'sub_sub_category'  => $cat_3,
'four_lavel'  => $cat_4,
'five_lavel'  => $cat_5,
'description' => $value['short_description'],
'product_description' => $value['detail_description'],
'meta_title'     => $value['name'],
'meta_description'  => $value['name'],
'created_by' => authUserId(),
];
$product_id = (new Product)->store($insertdata, $id);
    
    
} 
else{
       
       $category = explode(",", $value['category']);
       if(isset($category[0])){
           $cat_info = Category::where('name', $category[0])->select('id')->first();
           if($cat_info){
              $cat_1 = $cat_info->id;   
           } else {
              $cat_1 = 0;  
           }
       } else {
           $cat_1 = 0;
       }

       if(isset($category[1])){
           $cat_info = Category::where('name', $category[1])->select('id')->first();
           if($cat_info){
              $cat_2 = $cat_info->id;   
           } else {
              $cat_2 = 0;  
           }
       } else {
           $cat_2 = 0;
       }
       if(isset($category[2])){
           $cat_info = Category::where('name', $category[2])->select('id')->first();
           if($cat_info){
              $cat_3 = $cat_info->id;   
           } else {
              $cat_3 = 0;  
           }
       } else {
           $cat_3 = 0;
       }
       if(isset($category[3])){
           $cat_info = Category::where('name', $category[3])->select('id')->first();
           if($cat_info){
              $cat_4 = $cat_info->id;   
           } else {
              $cat_4 = 0;  
           }
       } else {
           $cat_4 = 0;
       }
       if(isset($category[4])){
           $cat_info = Category::where('name', $category[4])->select('id')->first();
           if($cat_info){
              $cat_5 = $cat_info->id;   
           } else {
              $cat_5 = 0;  
           }
       } else {
           $cat_5 = 0;
       }
if($value['status'] == 'publish'){
   $status = 1;
} else {
   $status = 0;
}
$sale_price = $value['offer_price'];
if(empty($sale_price)){
    $sale_price = $value['regular_price'];
}
$slug_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $value['name'])));
$insertdata = [   
'name'   => $value['name'],
'url'    =>  $slug_name,
'sku'    =>  $value['sku'],
'status' => $status,
'offer_price'   => $sale_price,
'regular_price' => $value['regular_price'],
'srp' => $value['srp'],
'category_id'   => $cat_1,
'sub_category'  => $cat_2,
'sub_sub_category'  => $cat_3,
'four_lavel'  => $cat_4,
'five_lavel'  => $cat_5,
'description' => $value['short_description'],
'product_description' => $value['detail_description'],
'meta_title'     => $value['name'],
'meta_description'  => $value['name'],
'created_by' => authUserId(),
];
$product_id = (new Product)->store($insertdata);
}
}
}
}
});
}
 return redirect()->route('product.index')
    ->with('success', lang('messages.created', lang('Products')));
}

}
catch(\Exception $e){
 // dd($e);
return back();
}
}

  
    
 public function ImportProduct_from_WP(Request $request){
 try{
 if($request->has('file') && isset($request->file)) {
 $excelFile = $request->file('file');
 if(!in_array($excelFile->getClientOriginalExtension(), ['xls','xlsx'])){
 return redirect()->route('product.create')
 ->withInput()
 ->withErrors(['File must be in excel format']);
 }
 if($request->hasFile('file')){
 $errorReport = [];
 ini_set('max_execution_time', '500');
 ini_set('memory_limit','-1');
 \Excel::load($excelFile, function($reader) use ($errorReport) {
 $excelData = [];
 $insertdata = [];
 $excelData = $reader->all();
 $firstrow = $reader->first()->toArray();
 if(!empty($errorReport)){
     return redirect()->route('product.index')->withErrors($errorReport);
 }
 else{
 foreach ($excelData->toArray() as $key => $value) {
  $product = Product::where('name', $value['name'])->first();
 if($product){
 } 
 else{
   // dd($value);
    
       $string = $value['product_cat'];
       $category = strtok($string, '|');
        if(str_contains($string, 'Closeouts')) { 
          $sub_cat = 'Closeouts';
        } else {
          $sub_cat = '';
         }
         if($category == $sub_cat){
           $sub_cat = NULL;
         } else {
          $sub_cat = 13;
         }
        
        $img = $value['images'];
        $pattern = "/.jpg/i";
        $img_exist = preg_match_all($pattern, $img); 
        if($img_exist == 1){
           $arr = explode(".jpg", $img, 2);
          $img_url = $arr[0];
          $img_sort_name = str_replace("https://www.pukacreations.com/media/","",$img_url);
          $image = '/uploads/featured_images/'.$img_sort_name.'.jpg';

         } else {
           $image = '/uploads/category_images/no_img.jpg';
        }


 if($value['post_status'] == 'publish'){
   $status = 1;
 } else {
  $status = 0;
 }

 $sale_price = $value['sale_price'];
 if(empty($sale_price)){
    $sale_price = $value['regular_price'];
 }
 $cat_info = Category::where('name', $category)->select('id')->first();
 if($cat_info){
   $category_id = $cat_info->id;
 } else {
   $category_id = 0;
 }
    
 $insertdata = [   
 'name'   => $value['name'],
 'url'    =>  $value['post_name'],
 'sku'    =>  $value['sku'],
 'status' => $status,
 //'created_at'    => $value['post_date'],
 'quantity'      => 100,
 'offer_price'   => $sale_price,
 'regular_price' => $value['regular_price'],
 'category_id'   => $category_id,
 'sub_category'  => $sub_cat,
 'featured_image'=> $image,
 'thumbnail'     => $image,
 'meta_title'     => $value['name'],
 'meta_description'  => $value['name'],
 'created_by' => authUserId(),
 ];

 $product_id = (new Product)->store($insertdata);

 $pattern = "/X-Case Deals > Case Deal by/i";
 $case_deal_exist = preg_match_all($pattern, $string); 
 if($case_deal_exist == 1){
 preg_match_all("/\d+/", $string, $quantity);
 $min_quantity = (int) end($quantity[0]);

 $inputs =[
 'product_id'  => $product_id,
 'quantity'    =>  $min_quantity,
 'max_quantity'=>  100,
 'discount'    =>  25,
 'status'      =>  1,
 'created_by'  =>  authUserId(),
 ];
 
  (new CaseDeal)->store($inputs);

 }
 }
 }
 }
 });
 }
  return redirect()->route('product.index')
     ->with('success', lang('messages.created', lang('Products')));
 }

 }
 catch(\Exception $e){
    // dd($e);
 return back();
 }
 } 

    public function upload_product(){
        return view('admin.product.upload_product');
    }     
    
    
  public function ImportOrder(Request $request){
try{
if($request->has('file') && isset($request->file)) {
$excelFile = $request->file('file');
if(!in_array($excelFile->getClientOriginalExtension(), ['xls','xlsx'])){
return redirect()->route('order.index')
->withInput()
->withErrors(['File must be in excel format']);
}
if($request->hasFile('file')){
$errorReport = [];
ini_set('max_execution_time', '5000');
ini_set('memory_limit','-1');
\Excel::load($excelFile, function($reader) use ($errorReport) {
$excelData = [];
$insertdata = [];
$excelData = $reader->all();
$firstrow = $reader->first()->toArray();
if(!empty($errorReport)){
    return redirect()->route('order.index')->withErrors($errorReport);
}
else{
foreach ($excelData->toArray() as $key => $value) {
  
 //$order = order::where('order_nr', $value['order_number'])->first();
 $oid = '#'.$value['order_number'];
 $order_item = Order::where('order_nr', $oid)->select('id')->first();
 if(empty($order_item)){
 
 $user = User::where('email', $value['customer_email'])->select('id')->first();

if($user){
 $user_id = $user->id;
 
 $billing_name = $value['billing_first_name'].' '.$value['billing_last_name']; 
 $billing = BillingAddress::where('name', $billing_name)->where('user_id', $user_id)->where('billing_email', $value['billing_email'])->where('mobile', $value['billing_phone'])
 ->where('address', $value['billing_address_1'])->where('state', $value['billing_state'])->where('city', $value['billing_city'])
 ->where('pincode', $value['billing_postcode'])->select('id')->first();
if($billing){
   $billing_id = $billing->id;
} else {
   $BillingAddress = new BillingAddress;
   $BillingAddress->name = $billing_name;
   $BillingAddress->user_id = $user_id;
   $BillingAddress->billing_email = $value['billing_email'];
   $BillingAddress->billing_company = $value['billing_company'];
   $BillingAddress->mobile = $value['billing_phone'];
   $BillingAddress->address = $value['billing_address_1'];
   $BillingAddress->state = $value['billing_state'];
   $BillingAddress->city = $value['billing_city'];
   $BillingAddress->pincode = $value['billing_postcode'];
   $BillingAddress->save();
   $billing_id = $BillingAddress->id;
}
   
   
   $shipping_name = $value['shipping_first_name'].' '.$value['shipping_last_name'];
   $country = \DB::table('countries')->where('country_code', $value['shipping_country'])->select('id')->first();
   
   $OrderAddress = new OrderAddress;
   $OrderAddress->name = $shipping_name;
   $OrderAddress->address = $value['shipping_address_1'];
   $OrderAddress->mobile = $value['shipping_phone'];
   $OrderAddress->country = $country->id;
   $OrderAddress->company_name = $value['shipping_company'];
   $OrderAddress->state = $value['shipping_state'];
   $OrderAddress->city = $value['shipping_city'];
   $OrderAddress->pincode = $value['shipping_postcode'];
   $OrderAddress->save();
   $shipping_id = $OrderAddress->id;
   
    $order_status = 1;
    if($value['status'] == 'cancelled'){
       $order_status = 8;
    }
    if($value['status'] == 'pending'){
       $order_status = 10;
    }
    if($value['status'] == 'completed'){
       $order_status = 5;
    }
    if($value['status'] == 'on-hold'){
       $order_status = 9;
    }
   
    $pay_later = 0;
    if($value['payment_method_title'] == 'Pay with Paypal Offline'){
       $pay_later = 2;
    }
    

$insertdata = [   
'billing_id'   => $billing_id,
'shipping_id'    => $shipping_id,
'user_id'    =>  $user_id,
'order_nr' => '#'.$value['order_number'],
'total_price'  => $value['order_total'],
'created_at'    => $value['order_date'],
'paid_date' => $value['paid_date'],
'order_key' => $value['order_key'],
'current_status' => $order_status,
'order_currency' => $value['order_currency'],
'pay_later' => $pay_later,
'transaction_id' => $value['transaction_id'],
'shipping_method' => $value['shipping_method'],
'order_from' => 'WEB',

];

$order_id = (new Order)->store($insertdata);

 OrderAddress::where('id', $shipping_id)
        ->update([
        'order_id' =>  $order_id,
]);
      
      
$line_item_1 = explode("|", $value['line_item_1']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_2']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_3']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_4']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_5']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_6']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_7']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_8']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_9']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_10']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_11']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_12']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_13']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}


$line_item_1 = explode("|", $value['line_item_14']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_15']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_16']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_17']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_18']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_19']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_20']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_21']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_22']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_23']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_24']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_25']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_26']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_27']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_28']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_29']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_30']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_31']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_32']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_33']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_34']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_35']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_36']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_37']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_38']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_39']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_40']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_41']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_42']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_43']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_44']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_45']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_46']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_47']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_48']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_49']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_50']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_51']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_52']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_53']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_54']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_55']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_56']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_57']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_58']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_59']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_60']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_61']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_62']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_63']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_64']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_65']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_66']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_67']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_68']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_69']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_70']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_71']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_72']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_73']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_74']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_75']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_76']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_77']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_78']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_79']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_80']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_81']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_82']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_83']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_84']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_85']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_86']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_87']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_88']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_89']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_90']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_91']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_92']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_93']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_94']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_95']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_96']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_97']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_98']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_99']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_100']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_101']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_102']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_103']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_104']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_105']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_106']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_107']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_108']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_109']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_110']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_111']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_112']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}
$line_item_1 = explode("|", $value['line_item_113']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_114']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_115']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}

$line_item_1 = explode("|", $value['line_item_116']); 
if(count($line_item_1) > 0){
$p_name = str_replace("name:","",$line_item_1[0]);
$product = Product::where('name', $p_name)->select('id')->first();
if($product){
$product_id = $product->id;
$quantity = str_replace("quantity:","",$line_item_1[3]);
$price = str_replace("total:","",$line_item_1[4]);   
$inputs =[
'product_id'=> $product_id,
'order_id'  => $order_id,
'user_id'   => $user_id,
'quantity'  => $quantity,
'price'     => $price,
];
 (new OrderProduct)->store($inputs);
}
}



}
}
}
}
});
}
 return redirect()->route('order.index')
    ->with('success', lang('messages.created', lang('Order')));
}

}
catch(\Exception $e){
dd($e);
return back();
}
} 
    public function upload_order(){
        return view('admin.order.upload_order');
    }  
    
    
    
}