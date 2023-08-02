<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductLot;
use App\Models\Request as Makerequest;
use App\Models\OrderProduct;
use Auth;

class DashboardController extends Controller {
  
    public function index() {
        $user = (new User)->totaluser_ent();
        $user_id =  Auth::id();
        $currentMonth = date('m');
        $newusers = \DB::table("users")
        ->where('user_type', 2)
        ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
        ->count();
        $total_order = \DB::table('orders')->count(\DB::raw('DISTINCT id'));
        $newproduct = \DB::table("products")
        ->whereRaw('MONTH(created_at) = ?',[$currentMonth])
        ->count();
        $complete_order = \DB::table('orders')->where('current_status', 5)->count(\DB::raw('DISTINCT id'));  
        $product = Product::where('status', 1)->count();
        $incomes = Order::where('status', 1)->get();
        $payment = 0;
        foreach ($incomes as $income) {
         $payment += $income->total_price;
        } 
        $newtotal_order = \DB::table('orders')->whereRaw('MONTH(created_at) = ?',[$currentMonth])->count(\DB::raw('DISTINCT id'));

        $orders = \DB::table('orders')
          ->join('users', 'users.id', '=','orders.user_id')
          ->select('orders.id','orders.order_nr', 'users.name')->orderBy('id', 'DESC')->limit(10)->get();
        $OrderProducts = OrderProduct::select(\DB::raw('sum(quantity) as max_qty'), 'product_id')->groupBy('product_id')->orderBy('max_qty','desc')->limit(10)->get();
        
        

        return view('admin.dashboard', compact('product', 'payment', 'user', 'newusers', 'total_order', 'newproduct', 'complete_order', 'newtotal_order', 'orders', 'OrderProducts'));
    }  

    
   
    public function financeReport()
    {
        $currentMonth = date('m');

        $orders = Order::where('discount', '!=', 0)->get();
        $discount = 0;
        if($orders){
            foreach($orders as $order){
              $discount += $order->discount;  
            }
        }

        $current_orders = Order::whereRaw('MONTH(created_at) = ?',[$currentMonth])->get();
        $current_discount = 0;
        if($orders){
            foreach($current_orders as $current_order){
              $current_discount += $current_order->discount;  
            }
        }

        $shippings = Order::where('shipping_charges', '!=', 0)->get();
        $shipping = 0;
        if($shippings){
            foreach($shippings as $ship){
              $shipping += $ship->shipping_charges;  
            }
        }

        $current_shippings = Order::whereRaw('MONTH(created_at) = ?',[$currentMonth])->get();
        $current_shipping = 0;
        if($current_shippings){
            foreach($current_shippings as $current_ship){
              $current_shipping += $current_ship->shipping_charges;  
            }
        }
          

        $order_products = OrderProduct::select('product_id', 'quantity', 'price')->get();
        $total_tax = 0; 
        
        foreach ($order_products as $order_product) {
           
        $lot_pro = ProductLot::where('id', $order_product->product_id)->select('product_id')->first();
        $text_per =  get_tax_per($lot_pro->product_id);
        $total_tax +=  ($order_product->price*$order_product->quantity) - round((($order_product->quantity*$order_product->price)*100/(100+ $text_per)), 2);

        }

        $order_products_c = OrderProduct::select('product_id', 'quantity', 'price')->whereRaw('MONTH(created_at) = ?',[$currentMonth])->get();
        $current_tax = 0; 
        
        foreach ($order_products_c as $order_product) {
           
        $lot_pro = ProductLot::where('id', $order_product->product_id)->select('product_id')->first();
        $text_per =  get_tax_per($lot_pro->product_id);
        $current_tax +=  ($order_product->price*$order_product->quantity) - round((($order_product->quantity*$order_product->price)*100/(100+ $text_per)), 2);

        }


        $total_sales = Order::where('total_price', '!=', 0)->get();
        $total_price = 0;
        if($total_sales){
            foreach($total_sales as $total_sale){
              $total_price += $total_sale->total_price;  
            }
        }
        
        $current_sales = Order::whereRaw('MONTH(created_at) = ?',[$currentMonth])->get();
        $current_price = 0;
        if($current_sales){
            foreach($current_sales as $current_sa){
              $current_price += $current_sa->total_price;  
            }
        }
       
        $total_deposits = Order::where('payment_method', 'razorpay')->get();
        $deposit = 0;
        if($total_deposits){
            foreach($total_deposits as $total_deposit){
              $deposit += $total_deposit->total_price;  
            }
        }
        

        $total_cash = Order::where('payment_method', '!=', 'razorpay')->get();
        $cash = 0;
        if($total_cash){
            foreach($total_cash as $total_c){
              $cash += $total_c->total_price;  
            }
        }

        return view('admin.finance_report', compact('discount', 'current_discount', 'shipping', 'current_shipping', 'total_tax', 'current_tax', 'total_price', 'current_price', 'deposit', 'cash'));
    } 

    public function outOfstock(){

        $products = \DB::table('products')
          ->join('categories', 'categories.id', '=','products.category_id')
          ->select('products.name','products.featured_image', 'products.offer_price', 'products.regular_price', 'products.id as pid', 'products.url', 'categories.name as category')
          ->where('products.quantity', 0)
          ->where('products.status', 1)
          ->get();

        return view('admin.out_of_stock', compact('products'));
    }

    public function maxSaleProduct(){

        $OrderProducts = OrderProduct::select(\DB::raw('sum(quantity) as max_qty'), 'product_id')->groupBy('product_id')->orderBy('max_qty','desc')->get();

       //dd($OrderProducts);

        return view('admin.max_sale_product', compact('OrderProducts'));
    }

    public function gst_calculation(){
       
       $orders = Order::where('status', 1)->get();

       return view('admin.gst_calculation', compact('orders'));

    }

    public function maxSaleCategory(){

        $OrderProducts = OrderProduct::join('products', 'order_products.product_id', '=', 'products.id')->join('categories', 'products.category_id', '=', 'categories.id')->select(\DB::raw('sum(order_products.quantity) as max_qty'), \DB::raw('count(order_products.product_id) as total_products'), 'products.category_id')->groupBy('category_id')->orderBy('max_qty','desc')->get();
       // dd($OrderProducts);

        return view('admin.max_sale_category', compact('OrderProducts'));
    }


    public function maxSaleCustomer(){

        $OrderCustomer = OrderProduct::select(\DB::raw('sum(quantity) as max_qty'), 'user_id')->groupBy('user_id')->orderBy('max_qty','desc')->get();

        //dd($OrderCustomer);

 
        return view('admin.max_sale_customer', compact('OrderCustomer'));
    }



}
