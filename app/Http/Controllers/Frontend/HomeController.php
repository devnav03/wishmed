<?php

namespace App\Http\Controllers\Frontend;
/**
 * :: Homepage Controller ::
 * To manage homepage.
 *
 **/
Use Mail;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Product;
use App\Models\ContentManagement;
use App\Models\Offer;
use App\Models\Cart;
use App\Models\InstructionVideo;
use App\Models\Tradeshow;
use App\Models\Ecatalog;
use App\Models\Contact;
use App\Models\Order;
use App\Models\LoginLog;
use App\Models\Subscriber;
use App\Models\Category;
use App\Models\OrderProduct;
use App\Models\ConfigureProduct;
use App\Models\Form;
use App\Models\Feedback;
use App\Models\SpecialPrice;
use App\Models\Blog;
use App\User;
use Ixudra\Curl\Facades\Curl;
use App\Models\Faqs;
use Session;
use Redirect;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use PDF;
use Auth;
use App\PasswordHash;
// use Stevebauman\Location\Facades\Location;

class HomeController extends Controller{

    public function __construct(Guard $auth, User $registrar)
    {
        $this->auth = $auth;
        //$this->middleware('guest', ['except' => 'getLogout']);
    }

    public function tradeshows(){
      try {
          $date = strtotime("-7 day");
          $today = date('Y-m-d', $date); 
          $tradeshows =  Tradeshow::where('status', 1)->select('name', 'place', 'booth', 'to_date', 'from_date')->where('to_date', '>=', $today)->orderby('from_date', 'ASC')->get();
          $Categorys = Category::where('status', 1)->select('name', 'id', 'url')->get();
          return view('frontend.pages.tradeshow', compact('tradeshows', 'Categorys'));
      } catch (\Exception $exception) {
          return back();
      }
    }

    public function save_cart(Request $request){
      try {

        $user_id = ''; 
        if(Auth::id()){
          $user_id =  Auth::id();
        }
        $request['session_id'] = $_SERVER['HTTP_USER_AGENT'];
        $limit = 0;
        if(isset($request->product_id)) {
          foreach ($request->product_id as $product_id) {
            if($request->quantity[$product_id] != 0){
              $limit++;
              if(Auth::id()){
                $cart = Cart::where('user_id', $user_id)->where('product_id', $product_id)->first();
                if($cart) {
                  $total_unit = $cart->quantity + $request->quantity[$product_id];
                  Cart::where('id', $cart->id)
                      ->update([
                      'quantity' =>  $total_unit,
                  ]);

                } else {

                  Cart::create([
                    'product_id' => $product_id,
                    'quantity'   => $request->quantity[$product_id],
                    'user_id'    => $user_id,
                  ]);

                }

              } else {

                $cart = Cart::where('session_id', $request['session_id'])->where('product_id', $product_id)->first();
                if($cart) {
                  $total_unit = $cart->quantity + $request->quantity[$product_id];
                  Cart::where('id', $cart->id)
                      ->update([
                      'quantity' =>  $total_unit,
                  ]);

                } else {

                  Cart::create([
                    'product_id'    => $product_id,
                    'quantity' => $request->quantity[$product_id],
                    'session_id' => $request['session_id'],
                  ]);
                }
              }
            }
          }
        } 

        if($limit == 0){
          return back()->with('not_added_in_cart', 'not_added_in_cart');
        } else {
          return back()->with('cart_successfully', 'cart_successfully');
        }
      } catch (Exception $e) {
        //dd($e);
        return back();
      }
    }


    public function shop(){
      $Categorys = Category::where('status', 1)->where('parent_id', NULL)->select('name', 'id', 'url')->get();
      $simple_ids = ConfigureProduct::pluck('simple_id')->toarray();
      // dd($simple_ids);
      $products = Product::where('status', 1)->whereNotIn('id', $simple_ids)->select('id', 'name', 'url', 'quantity', 'offer_price', 'regular_price', 'thumbnail', 'featured_image', 'product_type')->orderby('id', 'desc')->paginate(30);


      return view('frontend.pages.shop', compact('Categorys', 'products'));
    }

    public function forms_page(){
        $forms = Form::where('status', 1)->select('title', 'link', 'file')->orderBy('sort', 'ASC')->get();
        return view('frontend.pages.forms_page', compact('forms'));
    }

    public function blogs_details($url){

       

    }


    public function index() {
    try  {

      $simple_ids = ConfigureProduct::pluck('simple_id')->toarray();
      $feature_products = Product::where('status', 1)->where('is_featured', 1)->whereNotIn('id', $simple_ids)->select('id', 'name', 'url', 'quantity', 'offer_price', 'regular_price', 'thumbnail', 'featured_image', 'product_type')->orderby('id', 'desc')->paginate(8);

      $first_category = 7; 
      $medicals = Product::select('featured_image', 'name', 'id', 'url', 'offer_price', 'regular_price', 'status', 'product_type')->whereNotIn('id', $simple_ids)->where(['status' => 1])->whereRaw("(category_id = $first_category or sub_category = $first_category or sub_sub_category = $first_category or four_lavel = $first_category or five_lavel = $first_category or six_lavel = $first_category or seven_lavel = $first_category)")->inRandomOrder()->paginate(16);

      $second_category = 19; 
      $dentals = Product::select('featured_image', 'name', 'id', 'url', 'offer_price', 'regular_price', 'status', 'product_type')->whereNotIn('id', $simple_ids)->where(['status' => 1])->whereRaw("(category_id = $second_category or sub_category = $second_category or sub_sub_category = $second_category or four_lavel = $second_category or five_lavel = $second_category or six_lavel = $second_category or seven_lavel = $second_category)")->inRandomOrder()->paginate(16);

      $third_category = 4; 
      $labs = Product::select('featured_image', 'name', 'id', 'url', 'offer_price', 'regular_price', 'status', 'product_type')->whereNotIn('id', $simple_ids)->where(['status' => 1])->whereRaw("(category_id = $third_category or sub_category = $third_category or sub_sub_category = $third_category or four_lavel = $third_category or five_lavel = $third_category or six_lavel = $third_category or seven_lavel = $third_category)")->inRandomOrder()->paginate(16);

      $feedbacks = Feedback::where('status', 1)->get();
      
      $blogs = Blog::join('blog_categories', 'blog_categories.id', '=', 'blogs.category_id')
      ->select('blogs.url', 'blogs.title', 'blogs.image', 'blogs.created_at', 'blog_categories.name', 'blogs.meta_description')
      ->where('blogs.status', 1)->orderBy('blogs.id', 'DESC')->paginate(3);
      

      return view('frontend.home', compact('feature_products', 'medicals', 'dentals', 'labs', 'feedbacks', 'blogs'));
    } catch (\Exception $exception) {
     //dd($exception);
            return back();
        }
    }

    public function instruction_videos(){
      try{

          $instruction_videos = InstructionVideo::where('status', 1)->select('name', 'iframe_code')->get();
          return view('frontend.pages.instruction_videos', compact('instruction_videos'));

      } catch (\Exception $exception) {
       //dd($exception);
          return back();
      }
    }



    public function subscriberStore(Request $request) {
      try {
        
       // dd($request);
        $data['already_subs'] = "";  
        $data['email_subs'] = "";
        $data['valid_email'] = "";
        $inputs = $request->all(); 
        $validator = (new Subscriber)->validate($inputs);
        if($validator->fails()) {
      //   dd($validator);
          $data['valid_email'] = "Enter a valid email address";
           return $data;
        } 

        $chk_subs = Subscriber::where('email', $request->email)->first();
        if($chk_subs){
          $data['already_subs'] = "You’re Already Subscribed!";
        } else {
          Subscriber::create(['email' => $request->email]);
          $data['email_subs'] = "Thank you for your subscription";
        }

       return $data;

    } catch(\Exception $exception) {
        //dd($exception);
            return back();
    }
    }


      public function postLogin(Request $request) {
        try{
        $credentials = [
            'email' => $request->get('username'),
            'password' => $request->get('password'),
            'status' => 1
        ];

        $credentials1 = [
            'mobile' => $request->get('username'),
            'password' => $request->get('password'),
            'status' => 1
        ];
          
          $ip = $request->getClientIp();
          $inputs = $request->all();
        
            $validator = (new User)->validateLoginUser($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
            }

          $user = User::where('email', $request->username)->where('status', 1)->select('password')->first(); 
          if($user){
          $wp_hasher = new PasswordHash(8, TRUE);
          $plain_password = $request->password; 
          $password_hashed  =  $user->password;

          if($wp_hasher->CheckPassword($plain_password, $password_hashed)) {
            $user = User::where('email', $request->username)->where('status', 1)->first(); 
          } else {
            $user = ''; 
          }
          }

            if (!empty($user))  {

                \Auth::login($user);
                $session_id = $_SERVER['HTTP_USER_AGENT'];
                Cart::where('session_id', $session_id)
                       ->update([
                      'session_id' =>  NULL,
                      'user_id'  => $user->id,
                ]);

                $LoginLog = new LoginLog();
                $LoginLog->username = $request->username;
                $LoginLog->is_login = 1;
                $LoginLog->user_id = $user->id;
                $LoginLog->ip = $ip;
                $LoginLog->save();       
           
                $redirectTo = \Session::get('redirect_url');
                if($redirectTo){
                   return Redirect::to('/'.$redirectTo);
                } else {

                  $SpecialProducts = SpecialPrice::where('user_id', $user->id)->count();
                  if($SpecialProducts == 0){
                    return redirect()->route('home');
                  } else {
                    return redirect()->route('place-order');
                  }

                }

          } else if (Auth::attempt($credentials))  {
       
                $user_data = User::where('email', $request->username)->first();

                \Auth::login($user_data);
                $session_id = $_SERVER['HTTP_USER_AGENT'];
                Cart::where('session_id', $session_id)
                       ->update([
                      'session_id' =>  NULL,
                      'user_id'  => $user_data->id,
                ]);

                $LoginLog = new LoginLog();
                $LoginLog->username = $request->username;
                $LoginLog->is_login = 1;
                $LoginLog->user_id = $user_data->id;
                $LoginLog->ip = $ip;
                $LoginLog->save();       
           
                $redirectTo = \Session::get('redirect_url');
                 if($redirectTo){
                   return Redirect::to('/'.$redirectTo);
                } else {

                  $SpecialProducts = SpecialPrice::where('user_id', $user_data->id)->count();
                  if($SpecialProducts == 0){
                    return redirect()->route('home');
                  } else {
                    return redirect()->route('place-order');
                  }
                }

          } else if(Auth::attempt($credentials1)) {
               $user_data = User::where('mobile', $request->username)->first();

                 \Auth::login($user_data);
                 $session_id = $_SERVER['HTTP_USER_AGENT'];
                Cart::where('session_id', $session_id)
                       ->update([
                      'session_id' =>  NULL,
                      'user_id'  => $user_data->id,
                ]);

                $LoginLog = new LoginLog();
                $LoginLog->username = $request->username;
                $LoginLog->is_login = 1;
                $LoginLog->user_id = $user_data->id;
                $LoginLog->ip = $ip;
                $LoginLog->save();

                $redirectTo = \Session::get('redirect_url');
                if($redirectTo){
                  return Redirect::to('/'.$redirectTo);
                } else {
                    $SpecialProducts = SpecialPrice::where('user_id', $user_data->id)->count();
                  if($SpecialProducts == 0){
                    return redirect()->route('home');
                  } else {
                    return redirect()->route('place-order');
                  }
                }
        } else {
      
          $LoginLog = new LoginLog();
          $LoginLog->username = $request->username;
          $LoginLog->is_login = 0;
          $LoginLog->ip = $ip;
          $LoginLog->save();

          return back()->with('failed_login', 'failed_login');
        }
              
    } catch(\exception $ex){
          // dd($ex);
            return back();
          }
    }

    public function save_cart_and_place_order(Request $request){
      try {

        $user_id =  Auth::id();

        $limit = 0; 
        if(isset($request->product_id)) {
          foreach ($request->product_id as $product_id) {
            if($request->quantity[$product_id] != 0){
              $limit++;
              $cart = Cart::where('user_id', $user_id)->where('product_id', $product_id)->first();
              if($cart) {
                $total_unit = $cart->quantity + $request->quantity[$product_id];
                Cart::where('id', $cart->id)
                    ->update([
                    'quantity' =>  $total_unit,
                ]);
              } else {
                Cart::create([
                  'product_id'    => $product_id,
                  'quantity' => $request->quantity[$product_id],
                  'user_id' => $user_id,
                ]);
              }
            }
          }
        }
        
        if($limit == 0){
          return back()->with('not_added_in_cart', 'not_added_in_cart');
        } else {
          return redirect()->route('checkout');
        }

      } catch(\exception $ex){
          return back();
      }
    }

    public function place_order(){
      try {
          
        $user_id =  Auth::id();

        $products = \DB::table('products')
        ->join('special_prices', 'special_prices.product_id', '=','products.id')
        ->select('products.name', 'products.thumbnail', 'products.url', 'products.id', 'products.sku', 'special_prices.price', 'products.regular_price')
        ->where('products.status', 1)
        ->where('special_prices.user_id', $user_id)
        ->get();


        return view('frontend.pages.place_order', compact('products'));
      } catch(\exception $ex){
            return back();
      }
    }

    public function save_user(Request $request){

      try{
         
          $inputs = $request->all();
          $validator = (new User)->validate_front($inputs);
            if( $validator->fails() ) {
                return back()->withErrors($validator)->withInput();
          }
          $inputs['name'] = $request->first_name .' '.$request->last_name;
          $password = \Hash::make($inputs['password']);
          unset($inputs['password']);
          $inputs['password'] = $password;
          $inputs['user_type'] = 2;
          $inputs['status'] = 0;
          $user_id = (new User)->store($inputs);

            $user_data = User::where('id', $user_id)->first();
            // \Auth::login($user_data);
            $data['id'] = $user_data;
            $data['name'] = $inputs['name'];    
            $data['email']  = $inputs['email'];
            $data['mobile']  = $inputs['mobile']; 
            $email = $inputs['email'];
            \Mail::send('email.user_verify', $data, function($message) use ($email){
                $message->from('no-reply@wishmed.com');
                $message->to($email);
                $message->subject('Register');
            }); 
           // return redirect()->route('home');
            return redirect()->back()->with('message_reg', 'Register Done!');
        } catch(\Exception $ex){
          // dd($ex);
          return back();
        }
    }


    public function Login() {
        return view('frontend.pages.login');
    }

    public function updatePassword($user_id) {
        $user_id = Hashid::decode($user_id);
        return view('frontend.pages.change_password', compact('user_id'));
    }


    public function priceFilter(Request $request) {
    try {
    $request->validate([
    'min' => 'required|max:255',
    'max' => 'required|max:255',
    ]);
    $inputs = $request->all();
   
      $products = \DB::table('products')
      ->select('thumbnail', 'name', 'id', 'url', 'product_type')
      ->where('product_lots.sale_price', '>=', $request->min)
      ->where('product_lots.sale_price', '<=', $request->max)
      ->where('products.status', 1)
      ->get();

      $count = \DB::table('products')
      ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
      ->select('products.name as name')
      ->where('product_lots.sale_price', '>=', $request->min)
      ->where('product_lots.sale_price', '<=', $request->max)
      ->where('products.status', 1)
      ->count();

       $page = $request->page;
        if($request->page == 'Category'){
          $goal = Category::where('id', $request->f_id)->first();
        }
    // dd($products);
       
         return view('frontend.pages.filter.price_range', compact('products', 'request', 'count'));
    }
 catch(Exception $exception) {
       // dd($exception);
     
            return back();
        }

}

public function blogPage(){
   try{
        $blogs = Blog::where('status', 1)->orderby('created_at', 'desc')->paginate(12);
        $blogs_comment = BlogComment::where('status', 1)->orderby('created_at', 'desc')->limit(10);
        $recents = Blog::where('status', 1)->orderby('created_at', 'desc')->paginate(10);
       $CountComments = BlogComment::where('status', 1)->count();
       return view('frontend.pages.blog', compact('blogs', 'recents', 'CountComments', 'blogs_comment'));
   } catch(Exception $exception) {
        //dd($exception);
            return back();
        }
}

public function blogDetailPage($id = null){
    try {
        $blog = Blog::where('slug', $id)->where('status', 1)->first();
        $recents = Blog::where('status', 1)->where('slug', '!=', $id)->orderby('created_at', 'desc')->paginate(5);
        $BlogComments = BlogComment::where('blog_id', $blog->id)->where('status', 1)->paginate(10);
        $CountComments = BlogComment::where('blog_id', $blog->id)->where('status', 1)->count();
        // dd($CountComments);
       return view('frontend.pages.blog-detail', compact('blog', 'recents', 'BlogComments', 'CountComments'));
    } catch(Exception $exception) {
        //dd($exception);
            return back();
        }
}

public function commentSave(Request $request){
    try{
        $inputs = $request->all();
        $validator = (new Blog)->front_validate($inputs);
        if( $validator->fails() ) {
          return back()->withErrors($validator)->withInput();
        } 
    $BlogComment = new BlogComment();
    $BlogComment->name = $request->name;
    $BlogComment->email = $request->email;
    $BlogComment->comment = $request->comment;
    $BlogComment->blog_id = $request->blog_id;
    $BlogComment->status = 0;
    $BlogComment->save();
    return back()->with('comment_sub', lang('messages.created', lang('comment_sub')));
    } catch(Exception $exception) {
        //dd($exception);
            return back();
    }
}

public function contact(){
   try{
       
    $contact = ContentManagement::where('id', 1)->select('contact')->first();

    return view('frontend.pages.contact', compact('contact'));

   } catch(Exception $exception) {
        //dd($exception);
     
            return back();
    }

}

public function contactEnquiry(Request $request){
    try{
        $inputs = $request->all();
        $validator = (new Contact)->front_contact($inputs);
        if( $validator->fails() ) {
          return back()->withErrors($validator)->withInput();
        } 
 
        (new Contact)->store($inputs);
        $email = $inputs['email'];
        $data['mail_data'] = $inputs;
         
        \Mail::send('email.enquiry', $data, function($message) use ($email){
            $message->from($email);
            $message->to('test@test.com');
            $message->subject('Enquiry');
        });

        return back()->with('enquiry_sub', lang('messages.created', lang('comment_sub')));

    }catch(Exception $exception) {
       // dd($exception);
     
            return back();
    }
}


public function searchProduct(Request $request) {
      try{
        if($request->q){
          $search_id = 0;
          $simple_ids = ConfigureProduct::pluck('simple_id')->toarray();


          $products = Product::where('status', 1)->whereNotIn('id', $simple_ids)->where('name', 'LIKE', '%' . $request->q . '%')->Orwhere('sku', 'LIKE', '%' . $request->q . '%')->select('name', 'url', 'id', 'thumbnail', 'regular_price', 'offer_price')->orderby('id', 'DESC')->paginate(28);

          $data1 = Product::select('id')->whereIn('id', $simple_ids)->where('status', 1)->whereRaw("(name = '$query' or sku = '$query')")->orderBy('id', 'desc')->get();

          $row = '';

          foreach($data1 as $row1){
            $group_id = ConfigureProduct::where('simple_id', $row1->id)->select('group_id')->first();
            $row = Product::where('id', $group_id->group_id)->select('name', 'url', 'id', 'thumbnail', 'regular_price', 'offer_price')->first();
          }


          $counts = Product::where('status', 1)->whereNotIn('id', $simple_ids)->where('name', 'LIKE', '%' . $request->q . '%')->Orwhere('sku', 'LIKE', '%' . $request->q . '%')->count();
       

          $search_key = $request->q;
          $Categorys = Category::where('status', 1)->where('parent_id', NULL)->select('name', 'id', 'url')->Orderby('name')->get();
    
          return view('frontend.pages.search', compact('counts', 'Categorys', 'search_key', 'products', 'search_id', 'row'));

        }  else{
            return back();
        }
      } catch(\Exception $e){
        return back();
      }
    }

 
public function terms_and_conditions(){
    try{
        $terms_and_conditions = ContentManagement::where('id', 1)->select('terms_conditions')->first(); 
       return view('frontend.pages.term-condition', compact('terms_and_conditions'));
    } catch(\Exception $ex){
         
         //dd($ex);
        return back();
    }
 } 

  public function privacy_policy(){
    try {
          $privacy = ContentManagement::where('id', 1)->select('privacy')->first(); 
          return view('frontend.pages.privacy_policy', compact('privacy'));
        }
        catch (\Exception $exception) {
            return back();
        }
  }
  
    public function refund_return(){
    try {
          $refund_return = ContentManagement::where('id', 1)->select('refund_return')->first(); 
          return view('frontend.pages.refund_return', compact('refund_return'));
        }
        catch (\Exception $exception) {
            return back();
        }
  }



  public function AboutUs(){
    try {
          $about = ContentManagement::where('id', 1)->select('about')->first(); 
          return view('frontend.pages.about', compact('about'));
        }
        catch (\Exception $exception) {
            return back();
        }
  }


function action1(Request $request) {
     if($request->ajax()) {
      $output = '';
      $query = $request->get('query');
      // $category_id = $request->category_id;

      $simple_ids = ConfigureProduct::pluck('simple_id')->toarray();

      if($query != '') {

      // if($category_id == 0){

      $data =  Product::select('name', 'url', 'thumbnail')
    ->whereNotIn('id', $simple_ids)->where('status', 1)->whereRaw("(name = '$query' or sku = '$query')")->orderBy('id', 'desc')->get();

      // } else {

      //   $data =  Product::where('name', 'like', '%'.$query.'%')->Orwhere('sku', 'LIKE', '%' . $request->q . '%')->select('name', 'url', 'thumbnail')->whereNotIn('id', $simple_ids)
      //   ->where('name', 'like', '%'.$query.'%')->where('category_id', $category_id)
      //   ->orderBy('products.id', 'desc')
      //   ->get();

      // }

      }

      foreach($data as $row){
        $output .= '
        <li><a href="'.route('productDetail', $row->url).'"><img style="width:50px;margin-right: 6px;" src="'.asset($row->thumbnail).'">
            '.$row->name.'</a>
        </li>';
      }


      $data1 = Product::select('id')->whereIn('id', $simple_ids)->where('status', 1)->whereRaw("(name = '$query' or sku = '$query')")->orderBy('id', 'desc')->get();
      foreach($data1 as $row1){
        $group_id = ConfigureProduct::where('simple_id', $row1->id)->select('group_id')->first();
        $row = Product::where('id', $group_id->group_id)->select('name', 'url', 'thumbnail')->first();

        $output .= '
        <li><a href="'.route('productDetail', $row->url).'"><img style="width:50px;margin-right: 6px;" src="'.asset($row->thumbnail).'">
            '.$row->name.'</a>
        </li>';

      }


      $data = array(
       'table_data'  => $output,
      );
      echo json_encode($data);
     }
    }

}