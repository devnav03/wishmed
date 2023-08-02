<?php

namespace App\Http\Controllers\Frontend;
/**
 * :: Goal Controller ::
 * To manage homepage.
 *
 **/
Use Mail;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Product;
use App\Models\ClearanceSale;
use App\Models\ClearanceProduct;
use App\Models\Color;
use App\Models\Style;
use App\Models\FestiveSale;
use App\Models\FestiveProduct;
use App\Models\Category;
use App\Models\Brand;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use PDF;

class ClearenceSaleController extends Controller{
    /**
     * Display a listing of resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
   
    public function clearenceSale(){
      try{


        $today = date('Y-m-d');
        $counts =0 ;
        $products = [];
        $FlashSale = ClearanceSale::where('start_date', '<=', $today)->where('end_date', '>=', $today)->where('status', 1)->first();
        if($FlashSale){
          $FlashSalePeoducts = ClearanceProduct::where('clearance_id', $FlashSale->id)->get();        
          foreach($FlashSalePeoducts as $key => $FlashSalePeoduct){
              $products[$key] = \DB::table('products')
              ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
              ->join('packagings', 'packagings.id', '=','products.packaging_id')
              ->select('products.name', 'products.url', 'products.no_of_sheets_wt', 'products.sheet_size', 'products.weight_pcs', 'products.featured_image', 'products.id', 'product_lots.list_price', 'product_lots.sale_price', 'packagings.name as packaging')
              ->where('products.id', $FlashSalePeoduct->product_id)
              ->where('products.status', 1)
              ->first();
          } 

        $counts = ClearanceProduct::where('clearance_id', $FlashSale->id)->count(); 
        }

          
        $Sliders = Slider::where('status', 1)->where('product_range', 1)->get();
        $Categorys = Category::where('status', 1)->get();
        $Brands = Brand::where('status', 1)->get();

        return view('frontend.pages.clearance-sale', compact('counts', 'Sliders', 'products', 'Categorys', 'Brands'));

      } catch (\Exception $exception) {
         //  dd($exception);
          return back();
      }

    }

  public function festiveSale(){
      try{


        $today = date('Y-m-d');
        $counts =0 ;
        $products = [];
        $FlashSale = FestiveSale::where('start_date', '<=', $today)->where('end_date', '>=', $today)->where('status', 1)->first();
        if($FlashSale){
          $FlashSalePeoducts = FestiveProduct::where('festive_id', $FlashSale->id)->get();        
          foreach($FlashSalePeoducts as $key => $FlashSalePeoduct){
              $products[$key] = \DB::table('products')
              ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
              ->select('products.name', 'products.url', 'products.featured_image', 'products.id', 'product_lots.list_price', 'product_lots.sale_price')
              ->where('products.id', $FlashSalePeoduct->product_id)
              ->where('products.status', 1)
              ->first();
          } 

            $counts = FestiveProduct::where('festive_id', $FlashSale->id)->count(); 
        }

  
        $Categorys = Category::where('status', 1)->get();
        $colors = Color::where('status', 1)->get();
        $styles = Style::where('status', 1)->get();

        return view('frontend.pages.festive-sale', compact('counts', 'FlashSale', 'products', 'Categorys', 'styles', 'colors'));

      } catch (\Exception $exception) {
       

         dd($exception);
          return back();
      }

    }

 
   
}
