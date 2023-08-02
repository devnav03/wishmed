<?php

namespace App\Http\Controllers\Frontend;
/**
 * :: Category Controller ::
 * To manage homepage.
 *
 **/
Use Mail;
Use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ConfigureProduct;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use PDF;
use Intervention\Image\ImageManagerStatic as Image;
use Files;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller{
 
public function categoryDetails($url = null) {
try {
      $category = Category::where('url', $url)->where('status', 1)->first();
      // dd($category);
      $simple_ids = ConfigureProduct::pluck('simple_id')->toarray();
      $products = Product::select('thumbnail', 'name', 'id', 'url', 'offer_price', 'regular_price', 'status', 'featured_image', 'product_type')->whereNotIn('id', $simple_ids)
      ->where('status', 1)
      ->where('category_id', $category->id)
      ->orwhere('sub_category', $category->id)
      ->orwhere('sub_sub_category', $category->id)
      ->orwhere('four_lavel', $category->id)
      ->orwhere('five_lavel', $category->id)
      ->orwhere('six_lavel', $category->id)
      ->orwhere('seven_lavel', $category->id)
      ->orderBy('id', 'DESC')
      ->paginate(50);
     // dd($products);
      $counts = Product::select('name')->whereNotIn('id', $simple_ids)->where('category_id', $category->id)->orwhere('sub_category', $category->id)->orwhere('sub_sub_category', $category->id)->orwhere('four_lavel', $category->id)->orwhere('five_lavel', $category->id)->orwhere('six_lavel', $category->id)->orwhere('seven_lavel', $category->id)->where('status', 1)->count();
      $Categorys = Category::where('status', 1)->where('parent_id', NULL)->select('name', 'id', 'url')->orderBy('name')->get();
      return view('frontend.pages.category_products', compact('products', 'category', 'Categorys', 'counts'));
    }
    catch (\Exception $exception) {
     // dd($exception); 
        return back();
      }
    }


    public function top_trending_products() {
    try {

      $products = Product::select('thumbnail', 'name', 'id', 'url', 'offer_price', 'regular_price')
      ->where('status', 1)->where('trending', 1)->orderBy('id', 'DESC')->paginate(50);

      $counts = Product::select('name')->where('trending', 1)->where('status', 1)->count();
      $Categorys = Category::where('status', 1)->select('name', 'id', 'url')->where('id', '!=', 13)->get();
  
      return view('frontend.pages.top_trending_products', compact('products', 'Categorys', 'counts'));
    }
    catch (\Exception $exception) {
     // dd($exception); 
        return back();
      }
    }

     public function getCategory(Request $request) {
      try{
      $products = [];
      $filter_type = 'Category';
      if(isset($request->category_id)){
      $cat =  $request->category_id;
        if(in_array('ALL', $cat)){
          $products = Product::where('status', 1)->select('thumbnail', 'name', 'id', 'url', 'offer_price', 'regular_price')->orderBy('id', 'DESC')->get();

        $page = 'Category';
        return view('frontend.pages.filter.filter_all', compact('products', 'filter_type', 'page'));

        } else {

        foreach ($request->category_id as $key => $value) {
          $products[$key] = Product::where('status', 1)->where('category_id', $value)
          ->orwhere('sub_category', $value)->orwhere('sub_sub_category', $value)->orwhere('four_lavel', $value)->orwhere('five_lavel', $value)->orwhere('six_lavel', $value)->orwhere('seven_lavel', $value)->select('thumbnail', 'name', 'id', 'url', 'offer_price', 'regular_price')->orderBy('id', 'DESC')->get();
        }

        $page = 'Category';
        return view('frontend.pages.filter.filter', compact('products', 'filter_type', 'page'));

        }
      
      }
      
    // dd($products);
      
    } catch (\Exception $exception) {
        //dd($exception);
            return redirect()->route('home')
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
}



public function getbyRating(Request $request){
    try{
      $rating_id = $request->rating_id;
      $all_products = Product::where('status', 1)->where('product_type', 1)->get();
      $filter_type = 'Rating';
      $rate_product = [];
      foreach ($all_products as $all_product) {
         $p_rating = rating($all_product->id);
         if($p_rating >= $rating_id){
          $up = $rating_id+1;
          if($p_rating < $up){
          $rate_product[] = $all_product->id;
          } 
        } 
      }
      $products = [];
      $i = 0;
      foreach ($rate_product as $r_prod) {
        $i++;
       $products[] = \DB::table('products')
        ->select('thumbnail', 'name', 'id', 'url')
        ->where('products.status', 1)
        ->where('products.id', $r_prod)
        ->first();
      }
        $count = $i;  
        $page = 'Rating';
        return view('frontend.pages.filter.filter_rating', compact('products', 'rating_id', 'filter_type', 'page', 'count'));
    } catch (\Exception $exception) {
      // dd($exception);
            return redirect()->route('home')
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
}
   

  public function getSort(Request $request){
    try{

      $filter_type = '';
      $simple_ids = ConfigureProduct::pluck('simple_id')->toarray();
      //dd($request);

      if($request->sort_by == 1){
        $filter_type = 'New Arrivals';
        if($request->category_id == 9009){
          $products = Product::select('thumbnail', 'featured_image', 'name', 'id', 'url', 'offer_price', 'regular_price', 'featured_image', 'product_type')->whereNotIn('id', $simple_ids)->where('status', 1)->orderBy('id', 'DESC')->get();
        } else {
          $products = Product::select('thumbnail', 'featured_image', 'name', 'id', 'url', 'offer_price', 'regular_price', 'featured_image', 'product_type')->where('status', 1)->whereNotIn('id', $simple_ids)->where('category_id', $request->category_id)->Orwhere('sub_category', $request->category_id)->orderBy('id', 'DESC')->paginate(100);
        }
      }

      if($request->sort_by == 2){
        $filter_type = 'High Price to Low';
        if($request->category_id == 9009){
        $products = Product::select('thumbnail', 'featured_image', 'name', 'id', 'url', 'offer_price', 'regular_price', 'product_type')->whereNotIn('id', $simple_ids)->where('status', 1)->orderBy('offer_price', 'DESC')->get();
        } else {
        $products = Product::select('thumbnail', 'featured_image', 'name', 'id', 'url', 'offer_price', 'regular_price', 'product_type')->whereNotIn('id', $simple_ids)->where('status', 1)->where('category_id', $request->category_id)->Orwhere('sub_category', $request->category_id)->orderBy('offer_price', 'DESC')->paginate(100);
        }
      }

      if($request->sort_by == 3){
        $filter_type = 'Low Price to High';
        if($request->category_id == 9009){
          $products = Product::select('thumbnail', 'featured_image', 'name', 'id', 'url', 'offer_price', 'regular_price', 'product_type')->whereNotIn('id', $simple_ids)->where('status', 1)->orderBy('offer_price', 'ASC')->get();
        } else {
          $products = Product::select('thumbnail', 'featured_image', 'name', 'id', 'url', 'offer_price', 'regular_price', 'product_type')->whereNotIn('id', $simple_ids)->where('status', 1)->where('category_id', $request->category_id)->Orwhere('sub_category', $request->category_id)->orderBy('offer_price', 'ASC')->get();
        }
      }

      if($request->sort_by == 4){
        $filter_type = 'Top Trending';
          $products = Product::select('thumbnail', 'name', 'id', 'url', 'offer_price', 'regular_price')
          ->where('status', 1)
          ->where('category_id', $request->category_id)->Orwhere('sub_category', $request->category_id)
          ->paginate(100);
      }

      $sort_by = $request->sort_by;
      $category_id = $request->category_id;

   return view('frontend.pages.filter.sortby', compact('products', 'filter_type', 'sort_by', 'category_id'));

    } catch(\Exception $exception) {
   //  dd($exception);
            return back();
    }

  }




 public function getSort1(Request $request){

    try{

      $filter_type = '';


      if($request->sort_by == 1){
        $filter_type = 'New Arrivals';
       $products = Product::where('status', 1)->where('name', 'LIKE', '%' . $request->search_key . '%')
        ->select('thumbnail', 'name', 'id', 'url', 'offer_price', 'regular_price')->orderBy('id', 'DESC')->paginate(100);
      }

      if($request->sort_by == 2){
        $filter_type = 'High Price to Low';
       $products = Product::where('status', 1)->where('name', 'LIKE', '%' . $request->search_key . '%')->select('thumbnail', 'name', 'id', 'url', 'offer_price', 'regular_price')->orderBy('offer_price', 'DESC')->paginate(100);
      }

      if($request->sort_by == 3){
        $filter_type = 'Low Price to High';
       $products = Product::where('status', 1)->where('name', 'LIKE', '%' . $request->search_key . '%')->select('thumbnail', 'name', 'id', 'url', 'offer_price', 'regular_price')->orderBy('offer_price', 'ASC')->paginate(100);
      }

      if($request->sort_by == 4){
        $filter_type = 'Top Trending';
       $products = Product::where('status', 1)->where('name', 'LIKE', '%' . $request->search_key . '%')->where('trending', 1)->select('thumbnail', 'name', 'id', 'url', 'offer_price', 'regular_price')->paginate(100);
      }

      $search_key = $request->search_key;
      $sort_by = $request->sort_by;

   return view('frontend.pages.filter.sortby1', compact('products', 'filter_type', 'sort_by', 'search_key'));

    } catch(\Exception $exception) {
      //dd($exception);
            return back();
    }

  }


public function getbyAvailability(Request $request)
    {
      try{

      $my_warehouse = \Session::get('my_warehouse');
      $availability_stock = $request->availability_stock;
      if($availability_stock == 0){
        $Availability = 'Out Of Stock';
        $all_products = ProductQuantitie::where('qty', 0)->where('warehouse_id', $my_warehouse)->select('product_code')->get();
      } else {
        $Availability = 'In Stock';
        $all_products = ProductQuantitie::where('qty', '!=', 0)->where('warehouse_id', $my_warehouse)->select('product_code')->get();

      }
      
      $rate_product = [];

      $products = [];
      $i = 0;
      foreach ($all_products as $r_prod) {
        $i++;

       $products[] = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->select('products.thumbnail', 'products.name', 'products.id', 'products.url', 'products.product_type')
        ->where('products.status', 1)
        ->where('product_lots.code', $r_prod->product_code)
        ->first();
      }

      $count = $i; 
      $page = 'Availability';

         return view('frontend.pages.filter.filter_stock', compact('products', 'Availability', 'page', 'count'));
    } catch (\Exception $exception) {
     
   //  dd($exception);
            return redirect()->route('home')
                ->withInput()
                ->with('error', lang('messages.server_error').$exception->getMessage());
        }
}
   

public function configColor(Request $request){

    try{

       $my_pincode = \Session::get('my_pincode');
    $my_zone = \Session::get('my_zone');
    $my_warehouse = \Session::get('my_warehouse');

      $lot_id = $request->lot_id;
      $product_id = $request->product_id;


      $product_lot = ProductLot::where('id', $lot_id)->select('id', 'weight', 'unit', 'code', 'list_price')->first(); 
      $product = Product::where('id', $product_id)->select('id', 'name', 'featured_image')->first();

      $color_count = [];
      $product_option = '';
      $featured_slide = '';
      $img_ft_zoom = '';

     $price = ZonePrice::where('zone_id', $my_zone)->where('product_id', $product_lot->id)->value('price');
    if($price){
    $sale_price = $price;
    } else {
       $sale_price = $product->sale_price; 
    }

    $qty = ProductQuantitie::where('warehouse_id', $my_warehouse)->where('product_code', $product_lot->code)->select('qty')->first();
  
      if($qty){
          $quantity = $qty->qty;     
      } else {
          $quantity = 0; 
      }

      $gallery_imgs = \DB::table('product_images')->select('product_images.product_image')->where('product_images.product_id', $product_lot->id)->get();   
      
      $img_ft_zoom_img = \DB::table('product_images')->select('product_images.product_image')->where('product_images.product_id', $product_lot->id)->first(); 
      
        $product_option .= view('frontend.pages.filter.product_option', compact('lot_id', 'sale_price', 'quantity', 'product_id', 'product_lot', 'product'))->render();

        $featured_slide .= view('frontend.pages.filter.featured_slide', compact('gallery_imgs'))->render();
        $img_ft_zoom .= view('frontend.pages.filter.img_ft_zoom', compact('img_ft_zoom_img'))->render();

        $data['product_option'] = $product_option;
        $data['featured_slide'] = $featured_slide;
        $data['img_ft_zoom'] = $img_ft_zoom;

          return $data;   

      } catch(\Exception $exception) {
        // dd($exception);
            return back();
    }

    }


}
