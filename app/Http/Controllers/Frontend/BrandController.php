<?php

namespace App\Http\Controllers\Frontend;
/**
 * :: Brand Controller ::
 * To manage homepage.
 *
 **/
Use Mail;
Use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Brand;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use PDF;

class BrandController extends Controller{
   
    public function brandDetails($url = null)
    {

try {
  
    $brand = Brand::where('url', $url)->where('status', 1)->first();
      $products = \DB::table('products')
      ->select('name', 'url', 'thumbnail', 'products.id', 'product_type')
      ->where('products.brand_id', $brand->id)
      ->where('products.status', 1)
      ->get();

      $counts = \DB::table('products')
      ->select('name')
      ->where('products.brand_id', $brand->id)
      ->where('products.status', 1)
      ->count();

      // $Categorys = Category::where('status', 1)->get();
      $Categorys = Category::where('status', 1)->where('parent_id', 1)->select('name', 'id', 'url')->get();
      $fil_heading = "GROCERY & STAPLES";

       $Brands = Brand::where('status', 1)->get();

  return view('frontend.pages.brand_products', compact('products', 'brand', 'Categorys', 'Brands', 'counts', 'fil_heading'));
    }
    catch (\Exception $exception) {
       
         //dd($exception);
     
            return back();
        }

    }

    public function getBrand(Request $request)
    {

      $brand_id = $request->brand_id;
      $cat_address = Brand::where('id', $brand_id)->first();
      $filter_type = 'Brand';

      $products = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->join('packagings', 'packagings.id', '=','products.packaging_id')
        ->select('products.name', 'products.url', 'products.no_of_sheets_wt', 'products.sheet_size', 'products.weight_pcs', 'products.featured_image', 'products.id', 'product_lots.list_price', 'product_lots.sale_price', 'packagings.name as packaging')
        ->where('products.status', 1)
        ->where('products.brand_id', $brand_id)
        ->get();

      $count = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->join('packagings', 'packagings.id', '=','products.packaging_id')
        ->select('products.name', 'products.url', 'products.no_of_sheets_wt', 'products.sheet_size', 'products.weight_pcs', 'products.featured_image', 'products.id', 'product_lots.list_price', 'product_lots.sale_price', 'packagings.name as packaging')
        ->where('products.status', 1)
        ->where('products.brand_id', $brand_id)
        ->count();  

      $page = 'Brand';
       
         return view('frontend.pages.filter.filter', compact('products', 'cat_address', 'filter_type', 'page', 'count'));
    }

   public function getSortByBrand(Request $request)
  {

    try{
      $str = $request->sort_by;
       $arr = explode('route',trim($str));
      if($arr[0] == 'New Arrivals'){
       $products = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->join('packagings', 'packagings.id', '=','products.packaging_id')
        ->select('products.name', 'products.url', 'products.no_of_sheets_wt', 'products.sheet_size', 'products.weight_pcs', 'products.featured_image', 'products.id', 'product_lots.list_price', 'product_lots.sale_price', 'packagings.name as packaging')
        ->where('products.status', 1)
        ->where('products.brand_id', $arr[1])
        ->orderBy('products.id', 'DESC')->get();
        $count = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->join('packagings', 'packagings.id', '=','products.packaging_id')
        ->select('products.name', 'products.url', 'products.no_of_sheets_wt', 'products.sheet_size', 'products.weight_pcs', 'products.featured_image', 'products.id', 'product_lots.list_price', 'product_lots.sale_price', 'packagings.name as packaging')
        ->where('products.status', 1)
        ->where('products.brand_id', $arr[1])
        ->orderBy('products.id', 'DESC')->count();
      }
      if($arr[0] == 'High Price to Low'){
       $products = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->join('packagings', 'packagings.id', '=','products.packaging_id')
        ->select('products.name', 'products.url', 'products.no_of_sheets_wt', 'products.sheet_size', 'products.weight_pcs', 'products.featured_image', 'products.id', 'product_lots.list_price', 'product_lots.sale_price', 'packagings.name as packaging')
        ->where('products.status', 1)
        ->where('products.brand_id', $arr[1])
        ->orderBy('product_lots.sale_price', 'DESC')->get();
        $count = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->join('packagings', 'packagings.id', '=','products.packaging_id')
        ->select('products.name', 'products.url', 'products.no_of_sheets_wt', 'products.sheet_size', 'products.weight_pcs', 'products.featured_image', 'products.id', 'product_lots.list_price', 'product_lots.sale_price', 'packagings.name as packaging')
        ->where('products.status', 1)
        ->where('products.brand_id', $arr[1])
        ->orderBy('product_lots.sale_price', 'DESC')->count();
      }
      if($arr[0] == 'Low Price to High'){
       $products = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->join('packagings', 'packagings.id', '=','products.packaging_id')
        ->select('products.name', 'products.url', 'products.no_of_sheets_wt', 'products.sheet_size', 'products.weight_pcs', 'products.featured_image', 'products.id', 'product_lots.list_price', 'product_lots.sale_price', 'packagings.name as packaging')
        ->where('products.status', 1)
        ->where('products.brand_id', $arr[1])
        ->orderBy('product_lots.sale_price', 'ASC')->get();
        $count = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->join('packagings', 'packagings.id', '=','products.packaging_id')
        ->select('products.name', 'products.url', 'products.no_of_sheets_wt', 'products.sheet_size', 'products.weight_pcs', 'products.featured_image', 'products.id', 'product_lots.list_price', 'product_lots.sale_price', 'packagings.name as packaging')
        ->where('products.status', 1)
        ->where('products.brand_id', $arr[1])
        ->orderBy('product_lots.sale_price', 'ASC')->count();
      }
      if($arr[0] == 'Residential Range'){
       $products = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->join('packagings', 'packagings.id', '=','products.packaging_id')
        ->select('products.name', 'products.url', 'products.no_of_sheets_wt', 'products.sheet_size', 'products.weight_pcs', 'products.featured_image', 'products.id', 'product_lots.list_price', 'product_lots.sale_price', 'packagings.name as packaging')
        ->where('products.status', 1)
        ->where('products.product_range', 1)
        ->where('products.brand_id', $arr[1])
        ->get();
        $count = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->join('packagings', 'packagings.id', '=','products.packaging_id')
        ->select('products.name', 'products.url', 'products.no_of_sheets_wt', 'products.sheet_size', 'products.weight_pcs', 'products.featured_image', 'products.id', 'product_lots.list_price', 'product_lots.sale_price', 'packagings.name as packaging')
        ->where('products.status', 1)
        ->where('products.product_range', 1)
        ->where('products.brand_id', $arr[1])
        ->count();
      }
      if($arr[0] == 'Institutional Range'){
       $products = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->join('packagings', 'packagings.id', '=','products.packaging_id')
        ->select('products.name', 'products.url', 'products.no_of_sheets_wt', 'products.sheet_size', 'products.weight_pcs', 'products.featured_image', 'products.id', 'product_lots.list_price', 'product_lots.sale_price', 'packagings.name as packaging')
        ->where('products.status', 1)
        ->where('products.product_range', 2)
        ->where('products.brand_id', $arr[1])
        ->get();
        $count = \DB::table('products')
        ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
        ->join('packagings', 'packagings.id', '=','products.packaging_id')
        ->select('products.name', 'products.url', 'products.no_of_sheets_wt', 'products.sheet_size', 'products.weight_pcs', 'products.featured_image', 'products.id', 'product_lots.list_price', 'product_lots.sale_price', 'packagings.name as packaging')
        ->where('products.status', 1)
        ->where('products.product_range', 2)
        ->where('products.brand_id', $arr[1])
        ->count();
      }
      
      $filter_type = $arr[0];
      $page = 'Brand';

     return view('frontend.pages.filter.sortby', compact('products', 'filter_type', 'count'));

    } catch(\Exception $exception) {
            return back();
    }

  }

  public function brands(){

    try{

      $brands  = Brand::where('status', 1)->get();

       return view('frontend.pages.brands', compact('brands'));

    } catch(\Exception $exception) {
            return back();
    }

  }   
   
}
