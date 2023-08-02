<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductType;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\OrderProduct;
use App\Models\Search;
use App\Models\Cart;
use App\Models\Ecatalog;
use App\Models\TradeshowsImage;
use App\Models\Offer;
use App\Models\Tradeshow;
use App\Models\CaseDeal;
use App\Models\OrderProductStatus;
use App\Models\Order;
use App\Models\Review;
use App\Models\CategoryProducts;
use App\Models\Slider;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use DB;

class ProductController extends Controller
{

    public function sliders(){
      try{
          $data = [];
          $sliders = Slider::where('status', 1)->where('show_in_app', 1)->select('id', 'title', 'app_slider as image', 'link')->orderby('order', 'asc')->get();
          $url = route('home'); 
          if($sliders){
             foreach ($sliders as $key => $slider) {
                $slide['id'] = $slider->id;
                $slide['title'] = $slider->title;
                $slide['image'] = $url.$slider->image;
                $slide['link'] = $slider->link;
              $data[] = $slide;
             }
          }
          return apiResponseApp(true, 200, null, null, $data);

        } catch(Exception $e){
            return apiResponseApp(false, 500, lang('messages.server_error'));
        }

    }

    public function categoryData(){
      try{
          $data = [];
          $categories = Category::where('status', 1)->where('parent_id', NULL)->select('id', 'name', 'image')->orderby('order', 'asc')->get();
          $url = route('home'); 
           if($categories){
             foreach ($categories as $key => $category) {
                $cat['id'] = $category->id;
                $cat['name'] = $category->name;
                $cat['image'] = $url.$category->image;
              $data[] = $cat;
             }
          }
          return apiResponseApp(true, 200, null, null, $data);

        } catch(Exception $e){
            return apiResponseApp(false, 500, lang('messages.server_error'));
        }

    }

    public function subcategoryData(Request $request){
      try{
           $data = [];
          $categories = Category::where('status', 1)->where('parent_id', $request->category_id)->select('id', 'name', 'image')->orderby('order', 'asc')->get();
          $url = route('home'); 
           if($categories){
             foreach ($categories as $key => $category) {
                $cat['id'] = $category->id;
                $cat['name'] = $category->name;
                $cat['image'] = $url.$category->image;
              $data[] = $cat;
             }
          }

          return apiResponseApp(true, 200, null, null, $data);

        } catch(Exception $e){
            return apiResponseApp(false, 500, lang('messages.server_error'));
        }

    }

    public function product_by_category(Request $request){
      try{
          $user = User::where('api_key', $request->api_key)->select('id')->first(); 
          if($request->category_id){
          $category_pro = array();
          $url = route('home'); 
          $category_products = Product::where('category_id', $request->category_id)
            ->orwhere('sub_category', $request->category_id)
            ->orwhere('sub_sub_category', $request->category_id)
            ->orwhere('four_lavel', $request->category_id)
            ->orwhere('five_lavel', $request->category_id)
            ->orwhere('six_lavel', $request->category_id)
            ->orwhere('seven_lavel', $request->category_id)
            ->where('status', 1)->select('id')
            ->get();

          if($category_products) {
            foreach ($category_products as $key => $product) {
              $pro = Product::where('id', $product->id)->select('id', 'thumbnail', 'name', 'sku', 'quantity', 'offer_price', 'regular_price', 'srp')->first();

              $products['product_id'] = $pro->id;
              $products['name'] = $pro->name;
              $products['thumbnail'] = $url.$pro->thumbnail;
              $products['sku'] = $pro->sku;
              $products['offer_price'] = (int) $pro->offer_price;
              $products['regular_price'] = (int) $pro->regular_price;
              $category_pro[] = $products;
            }

          }

          $data = $category_pro;
          return apiResponseApp(true, 200, null, null, $data);
        } else {
          $data['message'] = "Category required";
          return apiResponseApp(false, 200, null, null, $data);
        }

      } catch(Exception $e){
            return apiResponseApp(false, 500, lang('messages.server_error'));
        }

    }

    
    //Product Detail
    public function productDetail(Request $request){
      try{    

           $user = User::where('api_key', $request->api_key)->select('id')->first();
            $data = [];
            $url = route('home'); 
            $product = Product::where('id', $request->product_id)->select('id', 'name', 'url', 'sku', 'quantity', 'featured_image', 'offer_price', 'regular_price', 'description', 'product_description', 'category_id')->first(); 
            $data['id'] = $product->id;
            $data['name'] = $product->name;
            $data['url'] = $product->url;
            $data['sku'] = $product->sku;
            $data['quantity'] = (int) $product->quantity;
            //$data['image'] = $url.$product->featured_image;
            $data['offer_price'] = (int) $product->offer_price;
            $data['regular_price'] = (int) $product->regular_price;
            $data['short_description'] = $product->description;
            $data['product_description'] = $product->product_description;
            $category = Category::where('id', $product->category_id)->select('name')->first();
            $data['category'] = $category->name;
            if($user){
              $chk_wish = Wishlist::where('user_id', $user->id)->where('product_id', $request->product_id)->select('id')->first();
              if($chk_wish){
                $data['wishlist'] = 1;
              } else{
                $data['wishlist'] = 0;
              }
            } else {
                $data['wishlist'] = 0;
            }

            if($user){
              $chk_cart = Cart::where('user_id', $user->id)->where('product_id', $request->product_id)->select('quantity')->first();
              if($chk_cart){
                $data['cart_unit'] = $chk_cart->quantity;
              } else{
                $data['cart_unit'] = 0;
              }
            } else {
                $data['cart_unit'] = 0;
            }

            $gallerys = ProductImage::where('product_id', $product->id)->select('product_image')->get();
            
            $gallery1 = array();
            $products_img['image'] = $url.$product->featured_image;
            $gallery1[] = $products_img;
            if($gallerys) {
              foreach ($gallerys as $key => $gallery) {
                $products_img['image'] = $url.$gallery->product_image;
                $gallery1[] = $products_img;
              }
            }
            
            $data['gallery'] = $gallery1;
            
            // $data['rating'] = Review::where('product_id', $product->id)->where('status', 1)->avg('rating');
            
            // $data['reviews'] = \DB::table('reviews')
            // ->join('users', 'reviews.user_id', '=','users.id')
            // ->select('reviews.image', 'reviews.review', 'reviews.rating', 'reviews.created_at', 'users.name')
            // ->where('reviews.status', 1)
            // ->where('reviews.product_id', $product->id)
            // ->get();

            
            $related_products = array();
            $new_products = Product::where('status', 1)->where('id', '!=', $product->id)->where('category_id', $product->category_id)->select('id')->orderby('id', 'desc')->limit(12)->get();
            if($new_products) {
              foreach ($new_products as $key => $product) {
                $pro = Product::where('id', $product->id)->select('id', 'thumbnail', 'name', 'sku', 'quantity', 'offer_price', 'regular_price', 'category_id')->first();
                $category = Category::where('id', $pro->category_id)->select('name')->first();
                $products['product_id'] = $pro->id;
                $products['name'] = $pro->name;
                $products['thumbnail'] = $url.$pro->thumbnail;
                $products['sku'] = $pro->sku;
                $products['quantity'] = (int) $pro->quantity;
                $products['offer_price'] = (int) $pro->offer_price;
                $products['regular_price'] = (int) $pro->regular_price;
                $related_products[] = $products;
              }
            }

            $data['related_products'] = $related_products;

            return apiResponseApp(true, 200, null, null, $data);

        } catch(Exception $e){
            //dd($e);
           return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    public function e_catalogs(){
      try {
        $data = [];
        $cat_list = [];
        $cat_list1 = [];
        $url = route('home'); 
        $Ecatalogs = Ecatalog::where('status', 1)->where('category', 1)->select('title', 'background_image', 'catalog_file', 'url')->get();
        if($Ecatalogs){
           foreach ($Ecatalogs as $key => $ecatalog) {
            $cat['title'] = $ecatalog->title;
            $cat['background_image'] = $url.$ecatalog->background_image;
            $cat['catalog_file'] = $url.$ecatalog->catalog_file;
            $cat['url'] = $url.$ecatalog->catalog_file;
            $cat_list[] =$cat;
           }
          $data['catalogs_2022'] = $cat_list;
        }

        $Ecatalogs = Ecatalog::where('status', 1)->where('category', 0)->select('title', 'background_image', 'catalog_file', 'url')->get();
        if($Ecatalogs){
           foreach ($Ecatalogs as $key => $ecatalog) {
            $cat['title'] = $ecatalog->title;
            $cat['background_image'] = $url.$ecatalog->background_image;
            $cat['catalog_file'] = $url.$ecatalog->catalog_file;
            $cat['url'] = $url.$ecatalog->url;
            $cat_list1[] =$cat;
           }
          $data['speciality_catalogs'] = $cat_list1;
        }


        return apiResponseApp(true, 200, null, null, $data);

      } catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    public function tradeshows(){
      try {

        $data = [];
        $cat_list = [];
        $url = route('home'); 
        $date = date('Y-m-d');
        $data = Tradeshow::where('status', 1)->where('from_date', '>=', $date)->select('name as show', 'place', 'booth', 'from_date', 'to_date')->get();

        return apiResponseApp(true, 200, null, null, $data);

      } catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
        }

    }

    public function tradeshows_images(){
      try {

        $data = [];
        $cat_list = [];
        $url = route('home'); 
        $Ecatalogs = TradeshowsImage::where('status', 1)->select('name', 'image')->get();
        if($Ecatalogs){
           foreach ($Ecatalogs as $key => $ecatalog) {
            $cat['title'] = $ecatalog->name;
            $cat['background_image'] = $url.$ecatalog->image;
            $cat_list[] = $cat;
           }
          $data = $cat_list;
        }

        return apiResponseApp(true, 200, null, null, $data);

      } catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
        }

    }
    

    public function my_wishlist(Request $request){
      try{

        if($request->api_key){
          $user = User::where('api_key', $request->api_key)->select('id')->first();
          if($user){
            $data = [];
            $wishlist_pro = array();
            $url = route('home'); 
            $wishlist = Wishlist::where('user_id', $user->id)->select('product_id')->get();
            if ($wishlist){
              foreach ($wishlist as $key => $product) {
                $pro = Product::where('id', $product->product_id)->select('id', 'thumbnail', 'name', 'offer_price', 'regular_price', 'quantity')->first();
                $products['product_id'] = $pro->id;
                $products['name'] = $pro->name;
                $products['thumbnail'] = $url.$pro->thumbnail;
                $products['offer_price'] = (int) $pro->offer_price;
                $products['regular_price'] = (int) $pro->regular_price;
                $products['quantity'] = (int) $pro->quantity;

                $cart = Cart::where('user_id', $user->id)->where('product_id', $product->product_id)->select('quantity')->first();
                if($cart){
                $products['cart_unit'] = $cart->quantity;
                } else{
                 $products['cart_unit'] = 0;
                }
                $wishlist_pro[] = $products;
              }
            }
            $data = $wishlist_pro;
            return apiResponseApp(true, 200, null, null, $data);
          }
        }

      } catch(Exception $Exception){
        return apiResponse(false, 500, lang('messages.server_error'));
       }
    }
   

    public function addToWishlist(Request $request){
       try {
          if($request->api_key){ 
          $user = User::where('api_key', $request->api_key)->select('id')->first();
          if($user){ 
          $inputs = $request->all();
            $inputs = $inputs + [
                'user_id' => $user->id,
                'created_by' => $user->id];
            $chk_wis = Wishlist::where('user_id', $user->id)->where('product_id', $request->product_id)->first();
            if($chk_wis){
               $message = "Product is already in Wishlist";

            } else {
                (new Wishlist)->store($inputs);
                $message = "Wishlist added successfully";
            }
            return apiResponseAppmsg(true, 200, $message, null, null);
          }
        }
       } catch(Exception $Exception){
          return apiResponse(false, 500, lang('messages.server_error'));
       }
    }
     
    public function deleteToWishlist(Request $request){
      try{
        if($request->api_key){ 
          $user = User::where('api_key', $request->api_key)->select('id')->first();
          if($user){ 
            \DB::table('wishlists')->where('user_id', $user->id)->where('product_id', $request->product_id)->delete();
            $message = "Product Successfully Remove from Wishlist";
            return apiResponseAppmsg(true, 200, $message, null, null);
          }
        }
      } catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
        }
    }


    public function case_deal_products(Request $request){
      try {
          
          if($request->api_key){
          $user = User::where('api_key', $request->api_key)->select('id')->first();
          if($user){
          $data = array();
          $url = route('home');  
          $new_products = \DB::table('products')
          ->join('case_deals', 'case_deals.product_id', '=', 'products.id')
          ->select('products.id')
          ->orderby('products.id', 'desc')
          ->where('case_deals.quantity', $request->quantity)
          ->where('products.status', 1)
          ->where('case_deals.status', 1)
          ->get();

          //dd($new_products);
          
          if($new_products) {
            foreach ($new_products as $key => $product) {
              $pro = Product::where('id', $product->id)->select('id', 'thumbnail', 'name', 'sku', 'quantity', 'offer_price', 'regular_price', 'category_id', 'srp')->first();
              $category = Category::where('id', $pro->category_id)->select('name')->first();
              $products['product_id'] = $pro->id;
              $products['name'] = $pro->name;
              $products['category'] = $pro->name;
              $products['thumbnail'] = $url.$pro->thumbnail;
              $products['sku'] = $pro->sku;
              $products['quantity'] = (int) $pro->quantity;
              $products['offer_price'] = (int) $pro->offer_price;
              $products['regular_price'] = (int) $pro->regular_price;
              $products['srp'] = $pro->srp;
              $wishlist = Wishlist::where('user_id', $user->id)->where('product_id', $pro->id)->first();
              if($wishlist){
                $products['wishlist'] = 1;
              } else {
                $products['wishlist'] = 0;
              }

              $data[] = $products;
            }
          }

         return apiResponseApp(true, 200, null, null, $data);

        }
      }
      } catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
        }

    }



    public function home_products(Request $request){
      try{

       
          $recent = array();
          $url = route('home'); 
          $new_products = Product::where('status', 1)->select('id')->orderby('id', 'desc')->limit(30)->get();
          if($new_products) {
            foreach ($new_products as $key => $product) {
              $pro = Product::where('id', $product->id)->select('id', 'thumbnail', 'name', 'sku', 'quantity', 'offer_price', 'regular_price', 'category_id', 'srp')->first();
              $category = Category::where('id', $pro->category_id)->select('name')->first();
              $products['product_id'] = $pro->id;
              $products['name'] = $pro->name;
             // $products['category'] = $pro->name;
              $products['thumbnail'] = $url.$pro->thumbnail;
            //  $products['sku'] = $pro->sku;
             // $products['quantity'] = (int) $pro->quantity;
             // $products['offer_price'] = (int) $pro->offer_price;
             // $products['regular_price'] = (int) $pro->regular_price;
             // $products['srp'] = $pro->srp;
             // $wishlist = Wishlist::where('user_id', $user->id)->where('product_id', $pro->id)->first();
             // if($wishlist){
              //  $products['wishlist'] = 1;
             // } else {
              //  $products['wishlist'] = 0;
             // }

              $recent[] = $products;
            }
          }

          $trending = array();
          $url = route('home'); 
          $new_products = Product::where('status', 1)->where('trending', 1)->select('id')->orderby('id', 'asc')->limit(12)->get();
          if($new_products) {
            foreach ($new_products as $key => $product) {
              $pro = Product::where('id', $product->id)->select('id', 'thumbnail', 'name', 'sku', 'quantity', 'offer_price', 'regular_price', 'category_id', 'srp')->first();
              $category = Category::where('id', $pro->category_id)->select('name')->first();
              $products['product_id'] = $pro->id;
              $products['name'] = $pro->name;
            //  $products['category'] = $pro->name;
              $products['thumbnail'] = $url.$pro->thumbnail;
             // $products['sku'] = $pro->sku;
             // $products['quantity'] = (int) $pro->quantity;
            //  $products['offer_price'] = (int) $pro->offer_price;
            //  $products['regular_price'] = (int) $pro->regular_price;
            //  $products['srp'] = $pro->srp;
            //   $wishlist = Wishlist::where('user_id', $user->id)->where('product_id', $pro->id)->first();
            //   if($wishlist){
            //     $products['wishlist'] = 1;
            //   } else {
            //     $products['wishlist'] = 0;
            //   }
              $trending[] = $products;
            }
          }

        $data['new_arrivals'] = $recent;
        $data['trending'] = $trending;
        

        return apiResponseApp(true, 200, null, null, $data);

      } catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
        }

    }

























    public function download_invoice(Request $request){
         
        try {

          $data['order'] = \DB::table('orders')
          ->join('order_statuses', 'order_statuses.id', '=', 'orders.current_status')
          ->join('order_addresses', 'order_addresses.id', '=', 'orders.shipping_id')
          ->join('order_billings', 'order_billings.id', '=', 'orders.billing_id')
          ->select('orders.id','orders.order_nr', 'orders.total_price', 'orders.wallet_paid', 'order_statuses.type', 'order_addresses.name', 'order_addresses.address', 'order_addresses.state', 'order_addresses.city', 'order_addresses.pincode', 'orders.shipping_charges', 'orders.discount', 'orders.payment_method', 'orders.created_at', 'order_billings.name as billing_name', 'order_billings.address as billing_address', 'order_billings.mobile as billing_mobile', 'order_billings.state as billing_state', 'order_billings.city as billing_city', 'order_billings.pincode as billing_pincode')
          ->where('orders.id', $request->id)
          ->first();
        
      //  dd($data);

          $pdf = \PDF::loadView('pdf.order_invoice', $data);
  

          $name = "invoice";
          $path = public_path('pdf/');
          $fileName =  $name . '.' . 'pdf' ;
          $pdf->save($path . '/' . $fileName);

          $data['link'] = "https://abc.com/pdf/invoice.pdf";

          return apiResponseApp(true, 200, null, null, $data);

       // return \response()->download($pdf, $newName, $headers);

        } catch(Exception $e){
            return apiResponseApp(false, 500, lang('messages.server_error'));
        }
 
    }


 

    public function share_product(Request $request){
      try {

          if($request->product_id){
          $url = route('home'); 
          $product = Product::where('id', $request->product_id)->select('url')->first();
              if($product){
              $data['url'] = $url."/product/".$product->url;
              return apiResponseApp(true, 200, null, null, $data);
              }
          }

      } catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
      }

    } 


    public function childCategory(Request $request){
      try{ 
        $data = [];
        $data['category_name'] = Category::where('id', $request->category_id)->select('name')->first();
        $data['categories'] = Category::where('status', 1)->where('parent_id', $request->category_id)->select('image', 'name', 'id', 'off')->get();

       // $data['sliders'] = Slider::where('status', 1)->select('image', 'type', 'slider_id')->get();  

          return apiResponseApp(true, 200, null, null, $data);

        }catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
        }
    }
  




    //My Cart
    // public function addReview(Request $request){
    //     try{    
    //       $user = User::where('api_key', $request->api_key)->first();
    //       if($user){ 
        
    //        $inputs = $request->all();
    //         $validator = (new Review)->validate($inputs);
    //         if( $validator->fails() ) {
    //             return apiResponse(false, 200, "", errorMessages($validator->messages()));
    //         }
    //         $perchase = 0;
    //         if($lot_products){
    //             foreach ($lot_products as $lot_product) {
    //               $user_order = OrderProduct::where('user_id',  $user->id)->where('product_id', $request->product_id)->first();
    //               if($user_order){
    //                 $perchase = 1;
    //               }
    //             }
    //         }
    //       if($perchase == 1){
    //         $inputs = $inputs + [
    //               'created_by' => $user->id,
    //               'user_id' => $user->id
    //           ];
    //         $id = (new Review)->store($inputs);
    //         $email = $user['email'];
    //         $data['email'] = $user['email'];
    //         $data['name'] = $user['name'];
    //         $data['review'] = $request['review'];
    //         $data['rating'] = $request['rating'];
    //         $product = Product::where('id', $request['product_id'])->first();
    //         $data['product_name'] = $product['name'];
    //         $order = Order::where('id', $user_order['order_id'])->first();
    //         $data['order_nr'] = $order['order_nr'];
    //         \Mail::send('email.review', $data, function($message) use ($email){
    //             $message->from($email);
    //             $message->to('info@abc.com');
    //             $message->subject('Puka Creation - Product Review');
    //         }); 
    //         $data['message1'] = "kindly wait for admin approval";
    //         return apiResponseApp(false, 200, null, null, $data);
    //       } else {
    //         $data['message1'] = "You Never Buy this product";
    //         return apiResponseApp(false, 200, null, null, $data);
    //       }
    //       }
    //     }catch(Exception $e){
    //        return apiResponse(false, 500, lang('messages.server_error'));
    //     }
    // }

    //My Cart
    public function searchWords(Request $request){
      try{    
        $data = [];
        if($request->search){
            $products = Product::where('name', 'LIKE', '%' . $request->search . '%')->where('status', 1)->select('name', 'id', 'thumbnail')->get();
            $url = route('home');
            if($products){
             foreach ($products as $key => $product) {
                $slide['id'] = $product->id;
                $slide['name'] = $product->name;
                $slide['image'] = $url.$product->thumbnail;
              $data[] = $slide;
             }
            }
        }
          return apiResponseApp(true, 200, null, null, $data);
        } catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
        }
    }


    public function save_search_history(Request $request){
      try{    
        $data = [];
        if($request->api_key){
          $inputs = $request->all();

          $user = User::where('api_key', $request->api_key)->select('id')->first();
          if($user){ 
         
            $inputs = $inputs + [    
                                        'user_id' => $user->id,
                                        'search' => $request->search,];
            (new Search)->store($inputs);

            $message = "Search History successfully saved.";
            return apiResponseAppmsg(true, 200, $message, null, null);
         }
        }
          
        } catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    public function case_deal(){

        $data = CaseDeal::where('status', 1)->select('quantity')->groupBy('quantity')->get();
        return apiResponseAppmsg(true, 200, null, null, $data);

    }

     public function search_history(Request $request){
      try{    
        $data = [];
        if($request->api_key){

          $user = User::where('api_key', $request->api_key)->select('id')->first();
          if($user){ 
           
           $data = Search::where('user_id', $user->id)->select('search')->groupBy('search')->get();
           
            return apiResponseAppmsg(true, 200, null, null, $data);
         }
        }
          
        } catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    


    public function cancel_order(Request $request){
      try{
        
        $user = User::where('api_key', $request->api_key)->select('id')->first();
          if($user){ 
        $data['reasons'] = CancelReason::where('status', 1)->select('message')->get();
   
        return apiResponseApp(true, 200, null, null, $data);
      }

      } catch(Exception $e){
           return apiResponse(false, 500, lang('messages.server_error'));
        }
    }


  
   

}
