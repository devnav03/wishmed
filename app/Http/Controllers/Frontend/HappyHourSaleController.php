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
use App\Models\Color;
use App\Models\Style;
use App\Models\HappyHourSale;
use App\Models\HappyHourProduct;
use App\Models\Category;
use App\Models\Brand;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use PDF;

class HappyHourSaleController extends Controller{
    /**
     * Display a listing of resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
   
    public function happyHourSale(){
      try{

        $today = date('Y-m-d H:i');
        $counts =0 ;
        $products = [];
        $FlashSale = HappyHourSale::where('start_date', '<=', $today)->where('end_date', '>=', $today)->where('status', 1)->first();
        //dd($FlashSale);
        if($FlashSale){
          $FlashSalePeoducts = HappyHourProduct::where('happy_hour_id', $FlashSale->id)->get();        
          foreach($FlashSalePeoducts as $key => $FlashSalePeoduct){
              $products[$key] = \DB::table('products')
              ->join('product_lots', 'product_lots.product_id', '=', 'products.id')
              ->select('products.name', 'products.url', 'products.featured_image', 'products.id', 'product_lots.list_price', 'product_lots.sale_price')
              ->where('products.id', $FlashSalePeoduct->product_id)
              ->where('products.status', 1)
              ->first();
          } 
        $counts = HappyHourProduct::where('happy_hour_id', $FlashSale->id)->count(); 
        }

        $Categorys = Category::where('status', 1)->get();
        $colors = Color::where('status', 1)->get();
        $styles = Style::where('status', 1)->get();
     

        return view('frontend.pages.happy-hour-sale', compact('counts', 'products', 'Categorys', 'styles', 'colors'));

      } catch (\Exception $exception) {
         //   dd($exception);
          return back();
      }

    }
   
}
