<?php

namespace App\Http\Controllers\Frontend;
/**
 * :: Flash Controller ::
 * To manage homepage.
 *
 **/
Use Mail;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Product;
use App\Models\FlashSale;
use App\Models\Style;
use App\Models\Color;
use App\Models\FlashProduct;
use App\Models\Category;
use App\Models\Brand;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use PDF;

class FlashSaleController extends Controller{
    /**
     * Display a listing of resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
   
    public function flashSale(){
      try{

        $today = date('Y-m-d');
        $counts =0 ;
        $products = [];
        $FlashSale = FlashSale::where('start_date', '<=', $today)->where('end_date', '>=', $today)->where('status', 1)->first();

        if($FlashSale){
          $FlashSalePeoducts = FlashProduct::where('flash_id', $FlashSale->id)->get();        
          foreach($FlashSalePeoducts as $key => $FlashSalePeoduct){
              $products[$key] = \DB::table('products')
              ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
              ->select('products.name', 'products.url', 'products.featured_image', 'products.id', 'product_lots.list_price', 'product_lots.sale_price')
              ->where('products.id', $FlashSalePeoduct->product_id)
              ->where('products.status', 1)
              ->first();
          } 
          $counts = FlashProduct::where('flash_id', $FlashSale->id)->count();  
        }

         
        $Categorys = Category::where('status', 1)->get();
        $colors = Color::where('status', 1)->get();
        $styles = Style::where('status', 1)->get();

        return view('frontend.pages.flash-sale', compact('products', 'Categorys', 'styles', 'counts', 'colors'));

      } catch (\Exception $exception) {
           dd($exception);
          return back();
      }

    }
   
}
