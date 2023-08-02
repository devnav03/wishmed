<?php

namespace App\Http\Controllers\Frontend;
/**
 * :: Goal Controller ::
 * To manage homepage.
 *
 **/
Use Mail;
Use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Category;
use App\Models\Goal;
use App\Models\Brand;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use PDF;

class GoalController extends Controller{
    /**
     * Display a listing of resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function goalDetails($url = null)
    {

try {

  $user_id =  Auth::id();
    if($user_id){
     if((\Auth::user()->user_type) == 3){
        $show_off = 3;
     } else if((\Auth::user()->user_type) == 4){
       $show_off = 2;

     } else {
       $show_off = 3;
     }

    } else {
      $show_off = 3;
    }

    $goal = Goal::where('url', $url)->where('status', 1)->first();
    $goals_p = \DB::table('products')
        ->join('goal_products', 'goal_products.product_id', '=','products.id')
        ->select('products.*')
        ->where('products.status', 1)
        ->where('products.show_off', '!=', $show_off)
        ->where('products.show_off', '!=', 0)
        ->where('goal_products.goal_id', $goal->id)
        ->get();

    $goals = [];
    foreach($goals_p as $key => $goal_p){
        $goals[$key] = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->select('products.*', 'product_lots.quantity as p_quantity', 'product_lots.list_price as list_price', 'product_lots.sale_price as sale_price', 'product_lots.quantity as quantity')
        ->where('products.id', $goal_p->id)
        ->where('products.show_off', '!=', $show_off)
        ->where('products.show_off', '!=', 0)
        ->where('product_lots.quantity', '!=', 0)
        ->first();
        if(empty($goals[$key])){
        $goals[$key] = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->select('products.*', 'product_lots.quantity as p_quantity', 'product_lots.list_price as list_price', 'product_lots.sale_price as sale_price', 'product_lots.quantity as quantity')
        ->where('products.id', $goal_p->id)
        ->where('products.show_off', '!=', $show_off)
        ->where('products.show_off', '!=', 0)
        ->orderBy('product_lots.created_at', 'desc')
        ->first();
        }
        
    }
       $Categorys = Category::where('status', 1)->where('parent_id', 0)->get();
       $Brands = Brand::where('status', 1)->get();
       $Goals = Goal::where('status', 1)->get();


        return view('frontend.pages.goal_products', compact('goals', 'goal', 'Categorys', 'Brands', 'Goals'));
    }
    catch (\Exception $exception) {
        //dd($exception);
     
            return back();
        }

    }
    
      public function getGoal(Request $request)
    {
      try{
        $user_id =  Auth::id();
        if($user_id){
     if((\Auth::user()->user_type) == 3){
        $show_off = 3;
     } else if((\Auth::user()->user_type) == 4){
       $show_off = 2;

     } else {
       $show_off = 3;
     }

    } else {
      $show_off = 3;
    }

      $goal_id = $request->goal_id;
      $cat_address = Goal::where('id', $goal_id)->first();
      $filter_type = 'Goal';

    $category_p = \DB::table('products')
        ->join('goal_products', 'goal_products.product_id', '=','products.id')
        ->select('products.*')
        ->where('products.status', 1)
        ->where('products.show_off', '!=', $show_off)
        ->where('products.show_off', '!=', 0)
        ->where('goal_products.goal_id', $goal_id)
        ->get();


    $products = [];
    foreach($category_p as $key => $category){
        $products[$key] = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->select('products.*', 'product_lots.quantity as p_quantity', 'product_lots.list_price as list_price', 'product_lots.sale_price as sale_price', 'product_lots.quantity as quantity')
        ->where('products.id', $category->id)
        ->where('product_lots.quantity', '!=', 0)
        ->first();
        if(empty($products[$key])){
         $products[$key] = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->select('products.*', 'product_lots.quantity as p_quantity', 'product_lots.list_price as list_price', 'product_lots.sale_price as sale_price', 'product_lots.quantity as quantity')
        ->where('products.id', $category->id)
        ->orderBy('product_lots.created_at', 'desc')
        ->first();
        }
    }
   // dd($products);
    $goal = Goal::where('id', $goal_id)->first();
    $page = 'Goal';
       
         return view('frontend.pages.filter.filter', compact('products', 'cat_address', 'filter_type','goal', 'page'));

    } catch (\Exception $exception) {

            //dd($exception);
             return redirect()->route('home')
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
}
  
  public function getSortByGoal(Request $request)
  {

    try{

        if($user_id){
     if((\Auth::user()->user_type) == 3){
        $show_off = 3;
     } else if((\Auth::user()->user_type) == 4){
       $show_off = 2;

     } else {
       $show_off = 3;
     }

    } else {
      $show_off = 3;
    }

       $str = $request->sort_by;
       $arr = explode('route',trim($str));
      if($arr[0] == 'New Arrivals'){
       $goals_p = \DB::table('products')
        ->join('goal_products', 'goal_products.product_id', '=','products.id')
        ->select('products.*')
        ->where('products.status', 1)
        ->where('products.show_off', '!=', $show_off)
        ->where('products.show_off', '!=', 0)
        ->where('goal_products.goal_id', $arr[1])
        ->orderBy('products.id', 'DESC')->get();
      }
      if($arr[0] == 'High Price to Low'){
       $goals_p = \DB::table('products')
        ->join('goal_products', 'goal_products.product_id', '=','products.id')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->select('products.*')
        ->where('products.status', 1)
        ->where('products.show_off', '!=', $show_off)
        ->where('products.show_off', '!=', 0)
        ->where('goal_products.goal_id', $arr[1])
        ->orderBy('product_lots.sale_price', 'DESC')->get();
      }
      if($arr[0] == 'Low Price to High'){
       $goals_p = \DB::table('products')
        ->join('goal_products', 'goal_products.product_id', '=','products.id')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->select('products.*')
        ->where('products.status', 1)
        ->where('products.show_off', '!=', $show_off)
        ->where('products.show_off', '!=', 0)
        ->where('goal_products.goal_id', $arr[1])
        ->orderBy('product_lots.sale_price', 'ASC')->get();
      }
      if($arr[0] == 'Trending'){
       $goals_p = \DB::table('products')
        ->join('goal_products', 'goal_products.product_id', '=','products.id')
        ->select('products.*')
        ->where('products.status', 1)
        ->where('products.show_off', '!=', $show_off)
        ->where('products.show_off', '!=', 0)
        ->where('products.trending', 1)
        ->where('goal_products.goal_id', $arr[1])
        ->get();
      }
      if($arr[0] == 'Best Sellers'){
       $goals_p = \DB::table('products')
        ->join('goal_products', 'goal_products.product_id', '=','products.id')
        ->select('products.*')
        ->where('products.status', 1)
        ->where('products.show_off', '!=', $show_off)
        ->where('products.show_off', '!=', 0)
        ->where('products.best_sellers', 1)
        ->where('goal_products.goal_id', $arr[1])
        ->get();
      }
      
      $products = [];
      foreach($goals_p as $key => $goal_p){
        $products[$key] = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->select('products.*', 'product_lots.quantity as p_quantity', 'product_lots.list_price as list_price', 'product_lots.sale_price as sale_price', 'product_lots.quantity as quantity')
        ->where('products.id', $goal_p->id)
        ->where('product_lots.quantity', '!=', 0)
        ->first();
        if(empty($products[$key])){
         $products[$key] = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->select('products.*', 'product_lots.quantity as p_quantity', 'product_lots.list_price as list_price', 'product_lots.sale_price as sale_price', 'product_lots.quantity as quantity')
        ->where('products.id', $goal_p->id)
        ->orderBy('product_lots.created_at', 'desc')
        ->first();
        }
    }
      $goal = Goal::where('id', $arr[1])->first();
      $filter_type = $arr[0];
      $page = 'Goal';
     return view('frontend.pages.filter.sortby', compact('products', 'filter_type', 'goal', 'page'));

    } catch(\Exception $exception) {
            return back();
    }

  } 
    
   
}
