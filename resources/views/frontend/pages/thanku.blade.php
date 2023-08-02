@extends('frontend.layouts.app')
@section('content')
  
  <section class="bredcrum">
    <div class="container">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>&nbsp;/&nbsp;<a href="{{ route('my-orders') }}">My Orders</a></li>
        <li>&nbsp;/&nbsp;Place Order</li>
      </ul>
    </div>
  </section>

  <section class="order-sucesful">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
        <img src="{!! asset('assets/frontend/images/logo.png') !!}" class="thanku-img" alt="thanku">
        </div>
        <div class="col-md-8">
          <div class="thanku-page">
            <div class="row">
              <div class="col-md-10 offset-md-1">
                  <h5><img src="{!! asset('assets/frontend/images/check.png') !!}" class="thanku-right" alt="right">Thanks for shopping with us.</h5>
                  <p>Please check your email for order confirmation and detailed delivery information.</p>
                  <h2>Order Number: <span>{{ $current_order->order_nr }}</span> </h2>
                  <p>Your order confirmation and receipt sent to: {{ $email }}</p>
                  <h3>Your order will be shipped to:</h3>
                  <div class="row">
                    <div class="col-md-2">
                     <h6>Address</h6>
                    </div>
                    <div class="col-md-10">
                       
      @if($current_order->ship_different_address == 1)
            @php
                $select = DB::table('shipping_zones')->where('id', $current_order->shipping_state)->select('name')->first();
            @endphp
          <p><b>{{ $current_order->shipping_first_name }} {{ $current_order->shipping_last_name }}</b><br/>
                    {{ $current_order->shipping_street_address }} {{ $current_order->shipping_street_address2 }}, {{ $current_order->shipping_suburb }},  {{$select->name}}, {{ $current_order->shipping_postcode }}</p>
                  @else
          @php
                $select = DB::table('shipping_zones')->where('id', $current_order->billing_state)->select('name')->first();
            @endphp
          <p><b>{{ $current_order->billing_first_name }} {{ $current_order->billing_last_name }}</b><br/>
                    {{ $current_order->billing_street_address }} {{ $current_order->billing_street_address2 }}, {{ $current_order->billing_suburb }},  {{$select->name}}, {{ $current_order->billing_postcode }}</p>  
      @endif

                    </div>
                  </div>
                  <a class="conti_shop" href="{{ route('home') }}">Continue Shopping</a>
              </div>
            </div>
          </div>
         </div>
      </div>
    </div>
  </section>
  @endsection  