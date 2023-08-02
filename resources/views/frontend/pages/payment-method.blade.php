@extends('frontend.layouts.app')
@section('content')
@php
$offer_id = null;
@endphp

<!-- BREAD-CRUMBS STARTS -->
    <section class="breadcrumbs py-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="bread-crumbs">
              <ul>
                <li><a href="{{ route('home')}}"><i class="fa fa-home"></i> Home</a></li>
                <li>/ <a href="{{ route('cartDetail')}}">Cart</a></li>
                <li>/ Checkout</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
  <!-- BREAD-CRUMBS ENDS -->
   @php $i = 0;
   $sale_price = 0;  @endphp
  @if(isset($cart_products))
        @foreach($cart_products as $cart_product)
            @php $i++;  @endphp
            @if(isset($cart_product->sale_price))
            @php
            if(get_flash_price($cart_product->pid)){
                $s_type = 'flash';
                $sale_discount = get_flash_price($cart_product->pid);
              } else if(get_clearence_price($cart_product->pid)){
                $s_type = 'clearence';
                $sale_discount = get_clearence_price($cart_product->pid);
             } else if(get_happyhour_price($cart_product->pid)){
               $s_type = 'happy_hour';
                $sale_discount = get_happyhour_price($cart_product->pid);
            } else if(get_festive_price($cart_product->pid)){
               $s_type = 'festive';
                $sale_discount = get_festive_price($cart_product->pid);
              } else {
               $sale_discount = 0;
            }

            $s_price = $cart_product->sale_price-$sale_discount;

            $sale_price += $s_price*$cart_product->quantity;
            @endphp
            @endif
          @endforeach    
@endif
  <div class="clearfix"></div>

  <!-- CHECKOUT-MAIN STARTS -->
<form action="{{ route('manage-address') }}" method="get">  
<!--   {{ csrf_field() }} -->
    <section class="checkout-main py-5">
      <div class="container">
        <div class="row">
          <div class="col-xl-8">
            <div class="row">
              <div class="col-md-1 col-2 px-md-0 pr-0">
                <div class="top-bar active img">
                  <p>Cart</p>
                </div>
              </div>
              <div class="col-md-3 col-4 px-0">
                <div class="top-bar dlvry active img">
                  <p>Delivery & Payment</p>
                </div>
              </div>
              <div class="col-md-2 col-2 px-0">
                <div class="top-bar po">
                  <p>Place Order</p>
                </div>
              </div>
              <div class="col-md-3 px-md-0 col-4 pl-0">
                <div class="top-bar scs">
                  <p>Order Completion</p>
                </div>
              </div>
            </div>
          </div>          
          <div class="col-xl-9">
            <div class="heading mt-4">
              <h4>Select A Payment Method</h4>
            </div>
            <div class="methods">
              <h5>Available Payment Methods</h5>
              <!-- <p class="mb-0 mt-3 bb"><input type="radio" name="payment_method" value="1"> <img src="{!! asset('assets/frontend/images/money-bag.png') !!}" class="img-fluid icon-chk" alt=""> Cash On Delivery</p>
              <span class="ml-3">We also accept Razorpay, subject to availability of the payment method. Please check with the delivery agent.</span> -->
              <p class="mb-0 my-3"><input type="radio" name="payment_method" value="2" checked=""> <img src="{!! asset('assets/frontend/images/rzr-pay.png') !!}" class="img-fluid icon-chk" alt=""> Razor Pay</p>
            </div>
          </div>
           @php  $ip = $_SERVER['HTTP_USER_AGENT']; @endphp
          <div class="col-xl-3 mt-3">
            <div class="total-box mt-5">
              <p class="subtotal">Sub Total({{ cart_count($ip) }} items):  <span id="total_sale_price_cart"><i class="fa fa-inr"></i> {{ $sale_price }}</span></p>
              <div class="button">
                <button type="submit">Proceed to Pay</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  <!-- CHECKOUT-MAIN ENDS -->

  <div class="fix-button">
      <button type="submit"><span id="1total_sale_price_cart"><i class="fa fa-inr"></i> {{ $sale_price }}</span> | Proceed To Pay</button>
    </div>
</form>
@endsection

