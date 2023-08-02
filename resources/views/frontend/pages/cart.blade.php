@extends('frontend.layouts.app')
@section('content')
@php
$offer_id = null;
@endphp

  <section class="bredcrum">
    <div class="container">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>&nbsp;/ &nbsp;Cart</li>
      </ul>
    </div>
  </section>

  <div class="clearfix"></div>
  <!-- CART-MAIN STARTS -->
    <section class="cart-main">
      <div class="container">
          <div id="add-cart"></div>
        @if(\Auth::check())
                @if(session()->has('not_valid'))
                <li class="alert alert-danger" style="list-style: none; margin-top: 25px;">Coupon Code Entered is not valid</li>
                @endif
                @if(session()->has('total_use'))
                <li class="alert alert-danger" style="list-style: none; margin-top: 25px;">Coupon already used</li>
                @endif
                @if(session()->has('user_limit'))
                <li class="alert alert-danger" style="list-style: none; margin-top: 25px;">You are already used</li>
                @endif
                @if(session()->has('offer_apply'))
                <li class="alert alert-success" style="list-style: none; margin-top: 25px;">offer applied</li>
                @endif
              @endif
        <h2>Shopping Cart</h2>
      @if(count($cart_products) != 0) 
        <div class="row">
          <div class="col-md-9">
            @php
            $sale_price = 0;
            $list_price = 0;
            $case_deal_price = 0;
            $tax = 0;
            $discount = 0;
            $stich_price = 0;
            $sale_discount = 0;

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

            if($offer_based == 'brand' || $offer_based == 'category'){
             $name = \Session::get('name'); @endphp
            <div class="alert alert-info" role="alert">
            @php 
            echo 'You get '; 
            if($discount_type == 'Price'){
                echo 'Rs. '.$off_amount.' off in minimum amount Rs.'.$min_amount.' and max discount is Rs. '.$max_discount.'';
            }
            if($discount_type == 'Percentage'){
                echo ''.$off_percentage.'% off in minimum amount Rs.'.$min_amount.' and max discount is Rs. '.$max_discount.'';
            }
              echo ' on all '.$name.' products';
            @endphp
          </div>
            @php 
            }

             if($offer_based == 'product'){
             $name = \Session::get('name'); @endphp
          <div class="alert alert-info" role="alert">
            @php 
            echo 'You get '; 
            if($discount_type == 'Price'){
                echo 'Rs. '.$off_amount.' off in minimum amount Rs.'.$min_amount.' and max discount is Rs. '.$max_discount.'';
            }
            
            if($discount_type == 'Percentage'){
                echo ''.$off_percentage.'% off in minimum amount Rs.'.$min_amount.' and max discount is Rs. '.$max_discount.'';
            }
              echo ' in '.$name.'';

            @endphp
          </div>
            @php 
            }

             if($offer_based == 'get_one'){
             $name = \Session::get('name'); 
             $sub_name = \Session::get('sub_name');
             @endphp
          <div class="alert alert-info" role="alert">
            @php 
            echo 'Buy <b>'.$name.'</b> and get one free <b>'.$sub_name.'</b>'; 
            @endphp
          </div>
            @php 
            }

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
          if($offer_based == 'Price'){
             $discount += $off_amount;
            if(get_price_minimum($offer_id)<$min_amount) {
             $discount = 0;
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

          @endphp

          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                          <th colspan="2" style="text-align: left;">Product name</th>
                          <th style="text-align: center;">PRICE</th>
                          <th style="text-align: center;">QUANTITY</th>
                          <th>TOTAL</th>
                          <th></th>
                      </tr>
                    </thead>  
                    <tbody> 

            @if(isset($cart_products)) 
            @php $i =0;  @endphp
            @foreach($cart_products as $cart_product)
            @php $i++;  @endphp
            @if(isset($cart_product->offer_price))
            @php

            if(\Auth::check()){
              $s_price = get_discounted_price($cart_product->id);
            } else {
              $s_price = $cart_product->offer_price;
            }
 
            $sale_price += $s_price*$cart_product->quantity;
            $list_price += $cart_product->regular_price*$cart_product->quantity; 

          @endphp   

           <!--  //$cd_price = cart_case_deal_price_discount($cart_product->c_id, //$cart_product->offer_price); -->
           <!--  //$case_deal_price += $cd_price*$cart_product->quantity; -->

            
                        
                        <tr style="border-bottom: 1px solid #dee2e6;">
                          <td style="text-align: center;">
                              <img class="max-h-100" src="{!! asset($cart_product->thumbnail) !!}" style="max-width: 100px;" alt="">
                          </td>
                          <td style="vertical-align: middle;max-width: 300px;"> 
                           <h5 style="font-size: 16px; line-height: 22px;">{{ $cart_product->name }}</h5>
                          </td>
                          <td class="second_td" style=" vertical-align: middle;text-align: center;">
                            
                            <p style="font-size: 12px;" id="widthout_sale_price{{ $cart_product->c_id }}"> <del><i class="fa fa-dollar"></i><span">{{ $cart_product->regular_price }}</span></del>  </p>  
                              
                            <p><i class="fa fa-dollar"></i><span id="sale_price{{ $cart_product->c_id }}">{{ $s_price }}</span></p>
                          </td>
                  
                          <td class="third_td" style="vertical-align: middle;text-align: center;">
                            <div class="qty-box">
                                <span id="remove_q{{ $cart_product->c_id }}">
                                    <button @if($cart_product->quantity > 1) value="{{ $cart_product->c_id }}" onclick="removeQuantityCart(this.value)" @endif class="d-inline-block btn-inc">-</button>
                                </span>
                                <span id="quantity{{ $cart_product->c_id }}" style="max-width: 50px;display: inline-block;">
                                    <input type="number" class="d-inline-block form-control" value="{{ $cart_product->quantity }}" readonly="">
                                </span>
                                    <button value="{{ $cart_product->c_id }}" class="btn-inc" onclick="addQuantityCart(this.value)">+</button>
                              </div>
                          </td>
                           <td class="second_td" style="text-align: center;vertical-align: middle;">
                            
                            <p class="mb-0" style="font-size:12px;" id="without_2sale_price{{ $cart_product->c_id }}"> <del><i class="fa fa-dollar"></i><span>{{ $cart_product->regular_price*$cart_product->quantity }}</span></del></p>   
                               
                            <p class="mb-0"><i class="fa fa-dollar"></i><span id="2sale_price{{ $cart_product->c_id }}">{{ $s_price*$cart_product->quantity }}</span> </p>
                            
                          </td>
                          <td class="third_td" style="text-align: center;vertical-align: middle;">
                            <a href="#" data-toggle="modal" data-target="#exampleModal{{ $cart_product->c_id }}" class="delete_del"><i class="fa-solid fa-xmark"></i></a>
                              <div class="modal fade" id="exampleModal{{ $cart_product->c_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-body">
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <p style="text-align: left; font-size: 18px;">Are you sure that you want to delete this item.</p>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="float: left;
                                font-weight: 300; border: 2px solid #000; padding: 6px 25px; margin-right: 15px; color: #fff; background: #000;
                                font-size: 16px; opacity: 1; border-radius: 6px;">Cancel</button> <a href="{{ route('deleteCart', $cart_product->c_id)}}" class="delete_del" style="float: left; font-size: 16px; padding: 4px 25px; border-radius: 6px;">Ok</a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </td>
                    </tr>
            @endif
            @endforeach
                   </tbody>
                    </table>
                  </div>
         
              @if(\Auth::check())
        <!--     <div class="coupon-box">  
            <div class="heading">
              <h6>Coupon Discount</h6>
            </div>
              <form action="{{ route('coupon-code') }}" method="post">
                {{ csrf_field() }}
                <div class="row">
                  <div class="col-8">
                    <input type="text" name="coupon_code" required="true" class="form-control" placeholder="Enter Your Coupon Code">
                  </div>
                  <div class="col-4">
                    <div class="button text-center midl">
                      <button class="coupon_code_button" type="submit">Apply</button>
                    </div>
                  </div>
                </div>
              </form>
            </div> -->
            @endif

            @endif
           
          </div>
          
          <div class="col-md-3">
            @if($sale_price != 0)
            <div class="total_c">
              <p>Item(s) Price <span id="total_list_price"> <i class="fa fa-dollar"></i>{{ $list_price }}</span></p>
              <!-- <p>Discount <span id="discount"> <i class="fa fa-dollar"></i>{{ $discount + $case_deal_price + $list_price - $sale_price }}</span></p> -->
              <p style="border-bottom: 1px solid #d6d6d6; padding-bottom: 15px;">Subtotal <span id="total_sale_price_cart"> <i class="fa fa-dollar"></i>{{ $sale_price  - $discount }}</span></p>

              <p style="font-size: 22px; font-weight: 600; color: #fff; padding-top: 20px;">Total <span id="total_sale_price_cart1" style="color: #fff;"> <i class="fa fa-dollar"></i>{{ $sale_price - $discount }}</span></p>
                  <a href="{{ route('checkout') }}">Proceed to Pay</a>
                  </div>
              @endif    
             </div>

        </div>
        @else
        <div class="text-center" style="width: 100%;">
              <img src="{!! asset('assets/frontend/images/empty_cart.png') !!}" class="img-fluid d-inline-block max-height-400" alt="" style="max-width: 55%;"> 
            </div>
        @endif
      </div>
    </section>
  <!-- CART-MAIN ENDS -->
  <div class="clearfix"></div>



@endsection

