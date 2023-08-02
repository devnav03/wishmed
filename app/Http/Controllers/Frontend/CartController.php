<?php

namespace App\Http\Controllers\Frontend;
/**
 * :: Brand Controller ::
 * To manage homepage.
 *
 **/
Use Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Goal;
use App\Models\Cart;
use App\Models\Offer;
use App\Models\Tax;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\CaseDeal;
use App\Models\ProductLot;
use App\Models\ProductQuantitie;
use App\Models\Wishlist;
use App\Models\Brand;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use PDF;
use Auth;

class CartController extends Controller{
  

public function buy_it_again($id = null){

  try{

      $user_id =  Auth::id();
      $products = OrderProduct::where('order_id', $id)->select('product_id', 'quantity')->get();

      if($products){
      foreach ($products as $product) {
  
        $check_cart = Cart::where('user_id', $user_id)->where('product_id', $product->product_id)->select('id', 'quantity')->first();

        if($check_cart){
          $my_warehouse = \Session::get('my_warehouse');
          $product_lot = ProductLot::where('id', $product->product_id)->select('code')->first();
          $qty =  ProductQuantitie::where('warehouse_id', $my_warehouse)->where('product_code', $product_lot->code)->select('qty')->first();


          $p_qty = $product->quantity + $check_cart->quantity;
          if($qty){

            if($p_qty >= $qty->$qty){
              Cart::where('id', $check_cart->id)
                  ->update([
                  'quantity' =>  $product->quantity + $check_cart->quantity,
              ]);
            }
        
          }
            

        } else {

          $my_warehouse = \Session::get('my_warehouse');
          $product_lot = ProductLot::where('id', $product->product_id)->select('code')->first();
          $qty =  ProductQuantitie::where('warehouse_id', $my_warehouse)->where('product_code', $product_lot->code)->select('qty')->first();
          if($qty){
            if($product->quantity >= $qty->$qty){ 
            Cart::create([
                  'product_id' => $product->product_id,
                  'quantity'   => $product->quantity,
                  'user_id'    =>  $user_id,
            ]);
            }
          }

        }

      }

      return redirect()->route('cartDetail');

      }
    }  catch(\Exception $exception) {
   // dd($exception);
        return back();
  }

}

    public function addToCart(Request $request){
      try{
        if($request->quantity){
           $q_val = $request->quantity;

        } else {
           $q_val = 1;
        }

        $user_id = 0;
        if(Auth::id()){
          $user_id =  Auth::id();
        }

        $request['session_id'] = $_SERVER['HTTP_USER_AGENT'];
        
        

        if($request->product_id) {
          $product = Product::where('id', $request->product_id) ->select('id', 'name', 'quantity',
            'regular_price', 'offer_price')->first();
           // dd($product);
          if($product){
            if(Auth::id()){
              $cart = Cart::where('user_id', $user_id)->where('product_id', $product->id)->first();
            } else {
              $cart = Cart::where('session_id', $request['session_id'])->where('product_id', $product->id)->first();
            }
            
            
            if($cart){
            $total_unit = $q_val + $cart->quantity;    
                
             if($total_unit > $product->quantity){

              $add_cart ='<div class="alert alert-success" id="success-alert" style="padding: 0px; border: 0px;margin-bottom:0px;background-color: transparent;">
                <div class="row">
                <div class="col-12" style="padding: 0px;">
                    <p style="text-align: center; margin-bottom: 0px; padding: 8px 0; margin-top: 0px; background: #DE6867; color: #fff;">We have '.$product->quantity.' items in stock currently. You can add only '.$product->quantity.' items to the cart.</p>
                </div>
                </div>
                </div>';

               
              $data['add_cart1'] = $add_cart;
              return $data;


              } else {     
                
              
              if($total_unit > $product->quantity){
                $total_unit = $product->quantity;
              }
    
            Cart::where('id', $cart->id)
                ->update([
                'quantity' =>  $total_unit,
            ]);

              $id = Cart::where('id', $cart->id)->first();
              
              }  

            } else {

            if(Auth::id()){
              $id = Cart::create([
                'product_id'    => $product->id,
                'quantity' => $q_val,
                'user_id' => $user_id,
              ]);

              $total_unit = $q_val;

            } else {

              $id = Cart::create([
                'session_id'  =>  $request['session_id'],
                'product_id'    => $product->id,
                'quantity' => $q_val,
              ]); 

              $total_unit = $q_val; 

            }

            }

            if(Auth::id()){
              $count = Cart::where('user_id', $user_id)->count();
            } else {
              $count = Cart::where('session_id', $request['session_id'])->count();
            }


             $add_cart ="<div class='alert alert-success' id='success-alert'>
                <button type='button' class='close' data-dismiss='alert'>x</button>
                <span class='pull-left'><img src='".asset('assets/frontend/images/right_cart.png')."' class='img-fluid d-inline-block'></span>Successfully added ".$product->name." to the <a href='".route('cartDetail')."'>Cart</a>
              </div>";

              // $findCaseDeal = CaseDeal::where('status', 1)->where('product_id', $product->id)->where('quantity', '<=', $total_unit)->where('max_quantity', '>=', $total_unit)->select('discount')->orderby('discount', 'desc')->first();

                $selected_product_price ='<i class="fa fa-dollar"></i>'.$product->offer_price.' <del><i class="fa fa-dollar"></i>'.$product->regular_price.'</del>';
             
                    
        
              if(Auth::id()){
              $cart_products = \DB::table('products')
              ->join('carts', 'carts.product_id', '=','products.id')
              ->select('products.name','products.thumbnail', 'products.offer_price', 'carts.quantity', 'products.regular_price', 'products.id')
              ->where('carts.user_id', $user_id)
              ->get();

            } else {
              $cart_products = \DB::table('products')
              ->join('carts', 'carts.product_id', '=','products.id')
              ->select('products.name','products.thumbnail', 'products.offer_price', 'carts.quantity', 'products.regular_price', 'products.id')
              ->where('carts.session_id', $request['session_id'])
              ->get();
            }   
    
 

        $total_price = 0; 
        $item_cart =[];
        foreach($cart_products as $cart_item){
    
          $c_price = $cart_item->offer_price;

          $total_price += ($cart_item->quantity * $c_price);
          $ca_img = $cart_item->thumbnail;


                $item_cart[] ='<div class="item">
                        <div class="row">
                          <div class="col-3">
                            <div class="img midl">
                              <img src="'.asset($ca_img).'" class="img-fluid mx-auto d-block" alt="">
                            </div>
                          </div>
                          <div class="col-9">
                            <div class="txt">
                              <h6>'.$cart_item->name.'</h6>
                              <p class="price">QTY:'.$cart_item->quantity.'<span class="float-right"><del><i class="fa fa-inr"></i> '.$cart_item->regular_price.'</del> <i class="fa fa-inr"></i> '.$c_price.'</span></p>
                            </div>
                          </div>
                        </div>
                      </div>';
                    }
                    $item_cart[] .='<div class="col-12">
                        <div class="total mt-3">
                          <div class="row">
                            <div class="col-6">
                              <h6><b>Total :</b></h6>
                            </div>
                            <div class="col-6 pr-0">
                              <p class="text-right"><i class="fa fa-inr"></i> '.$total_price.'</p>
                            </div>
                          </div>
                        </div>
                        <div class="buton text-center mt-4">
                          <a href="'.route('cartDetail').'">View Cart</a>
                        </div>
                      </div>';
         
          $c_count = 0;
          if(Auth::id()){
            $cart_product_count = Cart::where('user_id', $user_id)->get();
          } else {
            $cart_product_count = Cart::where('session_id', $request['session_id'])->get(); 
          }
          if($cart_product_count) {
          foreach($cart_product_count as $cart_product_c){
            
            $c_count += $cart_product_c->quantity;

          }
        }

          $data['selected_product_price'] = $selected_product_price;
          $data['add_cart'] = $add_cart;
          $data['add_cart1'] = '';
          $data['item_cart'] = $item_cart;
          $data['cart_product_count'] = $c_count;
          return $data;
             // return view('frontend.pages.add-cart', compact('product', 'id', 'count'));


          }
        }
      }
       catch(\Exception $exception) {
      // dd($exception);
            return back();
    }

    }


public function cartDetail()
{
  try{

    if(Auth::id()){
    $user_id = Auth::id();
  }

    $session_id = $_SERVER['HTTP_USER_AGENT'];
     if(Auth::id()){
     $cart_items = Cart::where('user_id', $user_id)->get();
  } else {
    $cart_items = Cart::where('session_id', $session_id)->get();
  } 
 

    if(Auth::id()){
      $cart_products = \DB::table('products')
          ->join('carts', 'carts.product_id', '=','products.id')
          ->select('products.name','products.thumbnail', 'products.offer_price', 'carts.quantity', 'products.regular_price', 'carts.id as c_id', 'products.id', 'products.url', 'products.quantity as p_quantity')
          ->where('products.status', 1)
          ->where('carts.user_id', $user_id)
          ->get();
    } else{
      $cart_products = \DB::table('products')
          ->join('carts', 'carts.product_id', '=','products.id')
         ->select('products.name','products.thumbnail', 'products.offer_price', 'carts.quantity', 'products.regular_price', 'carts.id as c_id', 'products.id', 'products.url', 'products.quantity as p_quantity')
          ->where('products.status', 1)
          ->where('carts.session_id', $session_id)
          ->get();
    }

    $today = date('Y-m-d');
    $get_one = []; 
    $product_based = []; 
    $categories_based = []; 
    $brand_based = []; 
    $offers = Offer::where('valid_from', '<=', $today)->where('valid_to', '>=', $today)->where('status', 1)->where('type_id', '!=', 1)->where('type_id', '!=', 2)->get();
    if($offers){
      foreach ($offers as $key => $offer) {
        if($offer->type_id == 3){
           $product_based[] = \DB::table('products')
          ->join('offers', 'offers.product_id', '=','products.id')
          ->select('products.name','products.thumbnail', 'offers.title', 'offers.off_amount', 'offers.off_percentage', 'offers.discount_type', 'offers.id', 'products.url')
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
          ->select('products.name','products.thumbnail', 'offers.title', 'offers.sub_product', 'offers.id', 'offers.product_id', 'products.url')
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
    
    return view('frontend.pages.cart', compact('cart_products', 'offers', 'get_one', 'product_based', 'categories_based', 'brand_based'));
 }
   catch(\Exception $exception) {
         // dd($exception);
            return back();
    }
}


public function paymentMethod() {
 try{

      if(Auth::id()){
    $user_id = Auth::id();
  } 
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
          ->join('carts', 'carts.product_id', '=','products.id')
          ->select('products.name','products.featured_image', 'product_lots.sale_price', 'carts.quantity', 'product_lots.list_price', 'carts.id as c_id', 'products.id as pid', 'product_lots.quantity as p_quantity')
          ->where('products.id', $cart_item->product_id)
          ->where('products.status', 1)
          ->first();
      }
    }
    
    return view('frontend.pages.payment-method', compact('cart_products'));
 }
   catch(\Exception $exception) {
        // dd($exception);
            return back();
    }

}

public function deleteCart($id = NULL)
{
  try{

      if(Auth::id()){
        $user_id = Auth::id();
      }

      $session_id = $_SERVER['HTTP_USER_AGENT'];
      if(Auth::id()){
        $find = Cart::where('user_id', $user_id)->where('id', $id)->first();
      } else {
        $find = Cart::where('session_id', $session_id)->where('id', $id)->first();
      }
        if($find){
            \DB::table('carts')->where('id', $id)->delete();
        return back()->with('cart_delete', lang('messages.created', lang('delete')));
        } else {
          return back();
        }
  }
  catch(\Exception $exception) {
      // dd($exception);
      return back();
  }

}

  public function addQuantityCart(Request $request)
  {

    try{

      $discount = 0;
      $session_id = $_SERVER['HTTP_USER_AGENT'];
      $cart_id = $request->cart_id;
      $cart_item = Cart::where('id', $cart_id)->first();
      $product = Product::where('id', $cart_item->product_id) ->select('id', 'name', 'quantity',
            'regular_price', 'offer_price')->first();

    //   if($cart_item->quantity < $product->quantity){
    
        $current_quan = $cart_item->quantity+1;
        
    //   } else {
    //     $current_quan = $cart_item->quantity;
    //   }

      if($current_quan > $product->quantity){
              $add_cart ='<div id="cart_modal" class="cart_modal py-3 px-2" style="border: 0px;">
                <div class="row">
                <div class="col-12">
                    <p style="text-align: center; margin-bottom: 0px; padding: 8px 0; margin-top: 0px; background: #DE6867; color: #fff;">We have '.$product->quantity.' items in stock currently. You can add only '.$product->quantity.' items to the cart.</p>
                </div>
                </div>
                </div>';
              $data['add_cart'] = $add_cart;
              return $data;
      } else {

      Cart::where('id', $cart_id)
        ->update([
        'quantity' =>  $current_quan,
      ]);

     }
      
     if(Auth::id()){
        $user_id = Auth::id();
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


      $quantity= '<input type="text" value="'.$current_quan.'" class="form-control d-inline-block" readonly="">';

      $total_sale_price = 0;
      $total_list_price = 0;
      $total_case_deal_price = 0;
      $total_tax = 0;
      // $total_carts = Cart::where('session_id', $session_id)->get();

      if(Auth::id()){
        $total_carts = \DB::table('products')
        ->join('carts', 'carts.product_id', '=','products.id')
        ->select('carts.*')
        ->where('carts.user_id', $user_id)
        ->get();
      } else {

      $total_carts = \DB::table('products')
        ->join('carts', 'carts.product_id', '=','products.id')
        ->select('carts.*')
        ->where('carts.session_id', $session_id)
        ->get();

      }
      
      //dd($total_carts);

      if($total_carts){
      foreach ($total_carts as $key => $total_cart) {
        $lot_price[$key] = \DB::table('products')
          ->join('carts', 'carts.product_id', '=', 'products.id')
          ->select('products.id', 'products.offer_price', 'carts.quantity', 'carts.id as cart_id', 'products.regular_price')
          ->where('products.id', $total_cart->product_id)
          ->where('carts.id', $total_cart->id)
          ->first();

         // dd($lot_price[$key]);
          $s_price = $lot_price[$key]->offer_price;

          $total_list_price += $lot_price[$key]->regular_price*$lot_price[$key]->quantity;
          $total_sale_price += $s_price*$lot_price[$key]->quantity;

         // dd($lot_price[$key]->regular_price*$lot_price[$key]->quantity);

          $cd_price = 0;
          $total_case_deal_price += $cd_price*$lot_price[$key]->quantity;

          $current_product = $s_price*$lot_price[$key]->quantity;
 
      }

      }

     // dd($total_sale_price);

      $total_saving = $discount+$total_list_price+$total_case_deal_price-$total_sale_price;
      $grand_total__ = $total_sale_price-$discount;
      $grand_total_ = $grand_total__-$total_case_deal_price; 
      
      $total_sale_price__ = $total_sale_price-$total_case_deal_price;
      $total_sale_price_ = $total_sale_price__ - $discount;
      $total_list_price_ = $total_list_price;

      $s_price1 = $product->offer_price;
      // $cd_price = cart_case_deal_price_discount($cart_item->id, $product->offer_price);
      // $total_case_deal_price = $cd_price*$current_quan;


      $cart_product_count = 0;
      $ipc = $_SERVER['HTTP_USER_AGENT'];
       if(Auth::id()){
        $cart_product_count = user_cart_count($user_id);
      } else {
        $cart_product_count = cart_count($ipc);
      }
        
      $data['sale_price1'] = $s_price1;

      $sale_price =  ''.$s_price1*$current_quan.'';
      $list_price = '<i class="fa fa-dollar"></i>'.$s_price1*$current_quan. '';
      $data['cart_product_count'] = $cart_product_count;
      $data['sale_price'] = $sale_price - $total_case_deal_price;
      if($total_case_deal_price != 0){
      $data['without_sale_price'] = '<del><i class="fa fa-dollar"></i><span>'.$sale_price.'</span></del>';    
      } else {
      $data['without_sale_price'] = '';    
      }
      if($cd_price != 0){
      $data['widthout_sale_price1'] = '<del><i class="fa fa-dollar"></i><span>'.$s_price1.'</span></del>';    
      } else {
      $data['widthout_sale_price1'] = '';    
      }
      
      $data['list_price'] = $list_price;
      $data['quantity'] = $quantity;
      $data['cart_id'] = $cart_id;
      $data['discount'] = '<i class="fa fa-dollar"></i>'.$discount.'';
      $data['grand_total'] = '<i class="fa fa-dollar"></i>'.$grand_total_.'';
      $data['total_sale_price'] = '<i class="fa fa-dollar"></i>'.$total_sale_price_.'';
      $data['total_list_price'] = '<i class="fa fa-dollar"></i>'.$total_list_price_.'';
      $data['total_tax'] = 0;
      $data['total_saving_'] = 'Your Total Savings is <i class="fa fa-dollar"></i>'.$total_saving.'';
      $data['total_saving_1'] = '<i class="fa fa-dollar"></i>'.$total_saving.'';
      if($current_quan > 1){
       $data['remove_q'] = '<button value="'.$cart_id.'" class="d-inline-block btn-inc" onclick="removeQuantityCart(this.value)">-</button>';
      }
      else{
       $data['remove_q'] = '<a class="btn-inc d-inline-block"></a>';
      }

      return $data;

    } catch(\Exception $exception) {

      // dd($exception);
      return back();
  }

  }
     
 public function removeQuantityCart(Request $request)
  {

    try{
      
      if(Auth::id()){
        $user_id = Auth::id();
      }
      $discount = 0;
      $session_id = $_SERVER['HTTP_USER_AGENT'];
      $cart_id = $request->cart_id;
      $cart_item = Cart::where('id', $cart_id)->first();

      if($cart_item->quantity > 1){
      Cart::where('id', $cart_id)
        ->update([
        'quantity' =>  $cart_item->quantity-1,
      ]);

        $current_quan =  $cart_item->quantity-1;

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

      $quantity= '<input type="text" value="'.$current_quan.'" class="form-control d-inline-block" readonly="">';

      $price = Product::where('id', $cart_item->product_id)->select('offer_price', 'regular_price')->first(); 
    
      $total_sale_price = 0;
      $total_list_price = 0;
      $total_case_deal_price = 0;
      $total_tax = 0;

      if(Auth::id()){
        $total_carts = \DB::table('products')
        ->join('carts', 'carts.product_id', '=','products.id')
        ->select('carts.*')
        ->where('carts.user_id', $user_id)
        ->get();
      } else {
        $total_carts = \DB::table('products')
        ->join('carts', 'carts.product_id', '=','products.id')
        ->select('carts.*')
        ->where('carts.session_id', $session_id)
        ->get();
      } 
      
       // dd($total_carts);
      if($total_carts){
      foreach ($total_carts as $key => $total_cart) {
        $lot_price[$key] = \DB::table('products')
          ->join('carts', 'carts.product_id', '=','products.id')
          ->select('products.id', 'products.offer_price', 'carts.quantity', 'carts.id as cart_id', 'products.regular_price')
          ->where('products.id', $total_cart->product_id)
          ->where('carts.id', $total_cart->id)
          ->first();

        $s_price = $lot_price[$key]->offer_price;

        $total_list_price += $lot_price[$key]->regular_price*$lot_price[$key]->quantity;
        $total_sale_price += $s_price*$lot_price[$key]->quantity;
        $current_product = $s_price*$lot_price[$key]->quantity;
         
        $cd_price = cart_case_deal_price_discount($lot_price[$key]->cart_id, $lot_price[$key]->offer_price);
        $total_case_deal_price += $cd_price*$lot_price[$key]->quantity;

      }

      }
      $total_saving = $total_case_deal_price+$discount+$total_list_price-$total_sale_price;
      $grand_total__ = $total_sale_price-$discount;
      $grand_total_ = $grand_total__-$total_case_deal_price;
      $total_sale_price__ = $total_sale_price - $total_case_deal_price;
      $total_sale_price_ = $total_sale_price__ - $discount;

      $total_list_price_ = $total_list_price;

      $s_price1 = $price->offer_price;
      
      $cd_price = cart_case_deal_price_discount($cart_id, $price->offer_price);
      $total_case_deal_price = $cd_price*$current_quan;

 
        $cart_product_count = 0;
        $ipc = $_SERVER['HTTP_USER_AGENT'];
        if(Auth::id()){
          $cart_product_count = user_cart_count($user_id);
        } else {
          $cart_product_count = cart_count($ipc);
        }


      $sale_price =  ''.$s_price1*$current_quan.'';
      $list_price = '<i class="fa fa-dollar"></i>'.$price->list_price*$current_quan. '';
      $data['sale_price1'] = $s_price1 - $cd_price;
      $data['sale_price'] = $sale_price - $total_case_deal_price;
      if($total_case_deal_price != 0){
      $data['without_sale_price'] = '<del><i class="fa fa-dollar"></i><span>'.$sale_price.'</span></del>';    
      } else {
      $data['without_sale_price'] = '';    
      }
      if($cd_price != 0){
      $data['widthout_sale_price1'] = '<del><i class="fa fa-dollar"></i><span>'.$s_price1.'</span></del>';    
      } else {
      $data['widthout_sale_price1'] = '';    
      }
      $data['cart_product_count'] = $cart_product_count;
      $data['list_price'] = $list_price;
      $data['quantity'] = $quantity;
      $data['cart_id'] = $cart_id;
      $data['discount'] = '<i class="fa fa-dollar"></i>'.$discount.'';
      $data['grand_total'] = '<i class="fa fa-dollar"></i>'.$grand_total_.'';
      $data['total_sale_price'] = '<i class="fa fa-dollar"></i>'.$total_sale_price_.'';
      $data['total_list_price'] = '<i class="fa fa-dollar"></i>'.$total_list_price_.'';
      $data['total_tax'] = 0;
      $data['total_saving_'] = 'Your Total Savings is <i class="fa fa-dollar"></i>'.$total_saving.'';
      $data['total_saving_1'] = '<i class="fa fa-dollar"></i>'.$total_saving.'';
      if($current_quan > 1){
       $data['remove_q'] = '<button value="'.$cart_id.'" class="d-inline-block btn-inc" onclick="removeQuantityCart(this.value)">-</button>';
      }
      else{
       $data['remove_q'] = '<a class="minus d-inline-block"></a>';
      }
       
       //dd($data);

      return $data;

    }catch(\Exception $exception) {
 //   dd($exception);
      return back();
  }

  }

  public function buyNow($id = null){

   try{

    if($id){
    $user_id =  Auth::id();

    $product = Product::where('id', $id)->where('status', 1)->first();

    if($product){

     $request['session_id'] = $_SERVER['HTTP_USER_AGENT'];
     if(Auth::id()){
        $cart = Cart::where('user_id', $user_id)->where('product_id', $product->id)->first();
     } else{
        $cart = Cart::where('session_id', $request['session_id'])->where('product_id', $product->id)->first();
     }
              
          if($cart){
               Cart::where('id', $cart->id)
                ->update([
                'quantity' =>  1 + $cart->quantity,
            ]);
              $id = Cart::where('id', $cart->id)->first();
            } else {

            if(Auth::id()){
              $id = Cart::create([
                'user_id'  =>  $user_id,
                'product_id'    => $product->id,
                'quantity' => 1,
              ]);
            } else {
              $id = Cart::create([
                'session_id'  =>  $request['session_id'],
                'product_id'    => $product->id,
                'quantity' => 1,
              ]);
            }


            }

      return redirect()->route('checkout');
    }

   }

   } catch(\Exception $exception){

  //  dd($exception);
      return back();
   }


  }

}
