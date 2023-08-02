<?php

namespace App\Http\Controllers\Frontend;
/**
 * :: Product Controller ::
 * To manage homepage.
 *
 **/
Use Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\CaseDeal;
use App\Models\CategoryProducts;
use App\Models\ProductImage;
use App\Models\Wishlist;
use App\Models\InstructionVideo;
use App\Models\ConfigureProduct;
use App\Models\Cart;
use App\Models\Review;
use App\Models\Offer;

use ElfSundae\Laravel\Hashid\Facades\Hashid;
use PDF;
use Auth;

class ProductController extends Controller{

public function productDetails($url = null) {
try {

    $user_id =  Auth::id();
    $request['session_id'] = $_SERVER['HTTP_USER_AGENT'];
    $current_product = Product::where('products.url', $url)->where('status', 1)->first();
    if($current_product){
    $keyword = Product::where('products.url', $url)->select('meta_title', 'meta_description', 'featured_image', 'url')->first();
    $product = \DB::table('products')
        ->join('categories', 'categories.id', '=','products.category_id') 
        ->leftjoin('categories as c2', 'c2.id', '=','products.sub_category') 
        ->leftjoin('categories as c3', 'c3.id', '=','products.sub_sub_category') 
        ->leftjoin('categories as c4', 'c4.id', '=','products.four_lavel') 
        ->leftjoin('categories as c5', 'c5.id', '=','products.five_lavel') 
        ->select('products.name', 'products.url', 'products.featured_image', 'products.id', 'products.regular_price', 'products.description', 'categories.name as category', 'categories.url as cat_url', 'categories.id as cat_id', 'products.product_description', 'c2.name as cat2', 'c2.url as url2', 'c3.name as cat3', 'c3.url as url3', 'c4.name as cat4', 'c4.url as url4', 'c5.name as cat5', 'c5.url as url5', 'products.offer_price', 'products.quantity', 'products.sku', 'products.product_type')
        ->where('products.status', 1)
        ->where('products.url', $url)
        ->first(); 
    
    $configure_products = [];

    if($product->product_type == 2){
        $simple_id = ConfigureProduct::where('group_id', $product->id)->pluck('simple_id')->toArray();
        $configure_products = Product::where('status', 1)->whereIn('id', $simple_id)->select('id', 'name', 'sku', 'offer_price')->get();
    }

 

    $similars = Product::where('products.status', 1)->where('category_id', $current_product->category_id)->select('name', 'url', 'thumbnail', 'id', 'regular_price', 'offer_price', 'quantity')->limit(15)->get();

    $reviews = \DB::table('reviews')
    ->join('users', 'reviews.user_id', '=','users.id')
    ->select('reviews.image', 'reviews.review', 'reviews.rating', 'reviews.created_at', 'users.name')
    ->where('reviews.status', 1)
    ->where('reviews.product_id', $product->id)
    ->orderby('reviews.id', 'desc')
    ->get();

    $one = Review::where('rating', 1)->where('product_id', $product->id)->where('status', 1)->count();
    $two = Review::where('rating', 2)->where('product_id', $product->id)->where('status', 1)->count();
    $three = Review::where('rating', 3)->where('product_id', $product->id)->where('status', 1)->count();
    $four = Review::where('rating', 4)->where('product_id', $product->id)->where('status', 1)->count();
    $five = Review::where('rating', 5)->where('product_id', $product->id)->where('status', 1)->count();  
    $total = Review::where('product_id', $product->id)->where('status', 1)->count();

    $one_per = 0;
    $two_per = 0;
    $three_per = 0;
    $four_per = 0;
    $five_per = 0;

    if($one){
    $one_per = ($one/$total)*100;
    }
    if($two){
    $two_per = ($two/$total)*100;
    }
    if($three){
    $three_per = ($three/$total)*100;
    }
    if($four){
    $four_per = ($four/$total)*100;
    }
    if($five){
    $five_per = ($five/$total)*100;
    }
    $all_rating = Review::where('product_id', $product->id)->where('status', 1)->avg('rating');
    $u_id =  Auth::id();
    $your_rating = NULL;
    if($u_id){
    $your_rating = Review::where('product_id', $product->id)->where('user_id', $u_id)->where('status', 1)->orderBy('created_at', 'desc')->first();
    }

    //$gallery_imgs = ProductImage::where('product_id', $product->product_lot_id)->get();
    // $case_deal = CaseDeal::where('product_id', $product->id)->where('status', 1)->select('quantity', 'discount', 'max_quantity')->get();

   // dd($case_deal);
  
    if(Auth::id()){
      $cart = Cart::where('user_id', $user_id)->where('product_id', $product->id)->first();
    } else {
      $cart = Cart::where('session_id', $request['session_id'])->where('product_id', $product->id)->first();
    }

    // $case_deal_price = 0;
    // if($cart){
    //   $findCaseDeal = CaseDeal::where('status', 1)->where('product_id', $product->id)->where('quantity', '<=', $cart->quantity)->where('max_quantity', '>=', $cart->quantity)->select('discount')->orderby('discount', 'desc')->first();

    // if($findCaseDeal){
    //       $case_deal_price = round(($product->offer_price/100)*$findCaseDeal->discount, 2);
    //   }
    // }
    
    $gallery_imgs = ProductImage::where('product_id', $product->id)->select('product_image')->get();

  
    $Categorys = Category::where('status', 1)->select('name', 'id', 'url')->where('id', '!=', 13)->Orderby('name')->get();
    $new_products = Product::where('status', 1)->where('category_id', $product->cat_id)->select('id', 'name', 'url', 'quantity', 'offer_price', 'regular_price', 'thumbnail', 'featured_image', 'product_type')->limit(20)->orderby('id', 'desc')->get();
    
    return view('frontend.pages.product-detail', compact('product', 'new_products', 'gallery_imgs', 'similars', 'Categorys', 'reviews', 'one_per', 'two_per', 'three_per', 'four_per', 'five_per', 'all_rating', 'your_rating', 'keyword', 'configure_products'));
    } else {
      return back();
     }
    }
    catch (\Exception $exception) {
    //dd($exception);
            return back();
        }
    }


    public function availablePincode(Request $request)
    {
      try{
       
       $status = 3;
        $pincode = Location::where('pincode', $request->pincode)->first();
        if($pincode){
            $status = 1;
        } else {
           $lrngth = strlen($request->pincode); 
            if($lrngth == 6){
               $status = 0; 
            }
             else {
                $status = 3; 
             }
        }

        return view('frontend.pages.filter.pincode', compact('status'));
          
      }
        catch (\Exception $exception) {
     
            return back();
        }

    }
    

}
