<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductType;
use App\Models\ProductAttribute;
use App\Models\Product;
use App\Models\CaseDeal;
use App\Models\ProductQuantitie;
use App\Models\ProductImage;
use App\Models\Wishlist;
use App\Models\OrderProduct;
use App\Models\Category;
use App\Models\UserAddress;
use App\Models\CategoryProducts;
use App\User;
use Auth;

class CartController extends Controller
{
    	//Add to Cart
    public function addToCart(Request $request){
		try{    
          if($request->api_key){
            $user = User::where('api_key', $request->api_key)->select('id')->first();
            if($user){ 
            $check_cart = Cart::where('user_id', $user->id)->where('product_id', $request->product_id)->select('id')->first();  
            if($check_cart){
                Cart::where('id', $check_cart->id)
                    ->update([
                        'quantity' => $request->quantity,
                ]);
                $data['product_id'] = $request->product_id;
                $data['quantity'] = (int) $request->quantity;
            } else{
                $cart = new Cart();
                $cart->user_id = $user->id;
                $cart->product_id = $request->product_id;
                $cart->quantity = $request->quantity;
                $cart->save(); 
                $data['product_id'] = $request->product_id;
                $data['quantity'] = (int) $request->quantity;
            }
            
            $message = "Product successfully added to cart";
               // return apiResponseApp(true, 200, $message, null, $data);
                return apiResponseAppmsg(true, 200, $message, null, $data);
                
                
            } else {
                $data['message'] = "Authentication Required";
                return apiResponseApp(false, 201, null, null, $data);
            }
          }
      	} catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'), null, null);
      	}
    }

    public function deleteToCart(Request $request){
        try{
          if($request->api_key){
            $user = User::where('api_key', $request->api_key)->select('id')->first();
            if($user){  
            \DB::table('carts')->where('user_id', $user->id)->where('product_id', $request->product_id)->delete();
            $data['message'] = "Product deleted from Cart";
            return apiResponseApp(true, 200, null, null, $data);
        } else {
                $data['message'] = "Authentication Required";
                return apiResponseApp(false, 200, null, null, $data);
            }
        }
      }
        catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'), null, null);
        }
    }


    public function myCart(Request $request){
		try{   
	    $data = [];
        $cart_pro = array();
        $url = route('home'); 
        $total_amount = 0;
        $total_case_deal = 0;
        if($request->api_key){
        $user = User::where('api_key', $request->api_key)->select('id', 'name')->first();
        if($user){ 
        $cart_products = \DB::table('carts')->where('user_id', $user->id)->select('carts.id as c_id')->get();

          if($cart_products){
            foreach ($cart_products as $key => $cart_value) {
               $pro = \DB::table('carts')
                ->join('products', 'products.id', '=','carts.product_id')
                ->select('products.name','products.thumbnail', 'products.quantity as product_quantity', 'products.offer_price', 'carts.quantity', 'products.regular_price', 'carts.id as cart_id', 'products.id as product_id', 'products.sku')
                ->where('products.status', 1)
                ->where('carts.user_id', $user->id)
                ->where('carts.id', $cart_value->c_id)
                ->first();

              
            if(isset($pro->thumbnail)){

               // $findCaseDeal = CaseDeal::where('status', 1)->where('product_id', $pro->product_id)->where('quantity', '<=', $pro->quantity)->where('max_quantity', '>=', $pro->quantity)->select('discount')->orderby('discount', 'desc')->first();

                $products['thumbnail'] = $url.$pro->thumbnail;
                $products['name']      = $pro->name; 
                $products['offer_price'] = (int) $pro->offer_price;
                $products['regular_price']  = (int) $pro->regular_price;
                $products['product_id'] = $pro->product_id; 
                $products['cart_unit']  = (int) $pro->quantity;
                $products['quantity'] = (int) $pro->product_quantity;
                $total_amount += $pro->offer_price*$pro->quantity;
               // if($findCaseDeal){
                //  $products['case_deal'] =  round(($pro->offer_price*$pro->quantity/100)*$findCaseDeal->discount);
              //  } else {
                //  $products['case_deal'] = 0;
                //}
               // $products['unit_price'] = $pro->quantity*$pro->offer_price-$products['case_deal'];
                $products['unit_price'] = $pro->quantity*$pro->offer_price;

                //$total_case_deal += $products['case_deal'];
                $cart_pro[] = $products;
            }
            }
           $total_amount = $total_amount - $total_case_deal;

           $data = $cart_pro;

            } else{
             $data['message'] = "No Products Added In Cart";
            }
 
            
           return apiResponseAppcart(true, 200, null, null, $data, $total_amount);
        } else {
                $data['message'] = "Authentication Required";
                return apiResponseApp(false, 200, null, null, $data);
            }
          }

      	} catch(Exception $e){
           // dd($e);
           return apiResponse(false, 500, lang('messages.server_error'), null, null);
      	}
    }

    public function myCartCount(Request $request){
        try{    
            
          if($request->api_key){
            $user = User::where('api_key', $request->api_key)->select('id')->first();
                if($user){ 
                    $cart_count = 0;
                    $data_cart = Cart::where('user_id', $user->id)->select('quantity')->get();
                    if($data_cart){
                        foreach ($data_cart as $data_car) {
                            $cart_count += $data_car->quantity;
                        }
                    }
                    $data['count'] = $cart_count;
                    return apiResponseApp(true, 200, null, null, $data);
                } else {
                $data['message'] = "Authentication Required";
                return apiResponseApp(false, 200, null, null, $data);
            }
          }

        return apiResponseApp(true, 200, null, null, $data);

        }catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'), null, null);
        }
    }


}
