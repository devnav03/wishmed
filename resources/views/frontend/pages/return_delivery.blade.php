@extends('frontend.layouts.app')
@section('content')
 @if($order)
  @php $total_pr1 = 0; @endphp
    @foreach(get_order_product($order->id) as $o_pro1)
    @php $total_pr1 += $o_pro1->price*$o_pro1->quantity; @endphp
@endforeach
@endif
  <section class="bredcrum">
    <div class="container-fluid">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('out-for-delivery') }}">My Orders</a></li>
        <li>Order Details</li>
      </ul>
    </div>
  </section>
  <div class="clearfix"></div>


  <!-- ORDER-DETAILS STARTS -->
    <section class="order-details py-5">
      <div class="container-fluid">
        <div class="row">
          <div class="col-xl-12">
            <div class="txt-box">
                <div class="detail_hd">
                  <div class="row">
                    <div class="col-xl-12">
                      <h4>Return Order Details</h4>
                    </div>
                 </div>
                </div>  
                <div class="detail_dt">
                  <div class="row">
                    <div class="col-xl-12 col-xs-12">
                      <p>PICKUP DATE: {{ $order->pickup_date }} ({{ $order->pickup_slot }}) | ORDER NO: {{ $order->order_nr }} </p>
                    </div>
                </div>

                  
                @if(session()->has('delivered_order'))
                    <p style="color: #4AB516; font-size: 16px; margin-bottom: 15px; margin-top: -7px;">Return Order successfully pickup</p>
                @endif
                @if($order->boy_pickup == 0)
                <div class="dl_otp" style="max-width: 400px;">
                @if(session()->has('otp_miss'))
                    <p style="color: #f00; font-size: 16px; margin-bottom: 15px; margin-top: -7px;">OTP not match</p>
                @endif
                <form action="{{ route('return_otp') }}" method="post">
                  {{ csrf_field() }}
                  <input type="number" name="otp" maxlength="6" minlength="6" required="true" placeholder="Enter OTP">
                  <input type="hidden" name="return_id" value="{{ $order->return_id }}" required="true">
                  <button type="submit">Submit</button>
                </form>
                </div>
                @endif
 

                <div class="od_detail" style="max-width: 400px;"> 
           
                <div class="row">
                  <div class="col-md-12">
                    <h6 class="shipping_hd">Shipping Address</h6>
                    <div class="shipping-summry" style="margin-top: 22px;">
                      <p class="name mb-1" style="text-transform: uppercase;">{{ $order->name }}</p>
                      <p class="mb-1">{{ $order->address }}, {{ $order->city }},<br> {{ $order->state }} - {{ $order->pincode }}</p>
                      <p class="mb-1">Phone: {{ $order->mobile }}</p>
                    </div>
                  </div>
              
          
                </div>
                </div>

                <div class="deliver-box mt-3">
                  <div class="table-responsive">
                    <table class="table table-bordered text-center mb-4">
                      <thead>
                        <tr class="text-uppercase" style="background: #EBEBEB;">
                          <th>
                            <p class="mb-0" style="max-width: 190px;font-weight: 500;">product</p>
                          </th>
                          <th>
                            <p class="mb-0" style="text-align: left;font-weight: 500;">name</p>
                          </th>
                          <th>
                            <p class="mb-0" style="font-weight: 500;">quantity</p>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        @if(isset($products))
                        @php $total_pr = 0; @endphp
                        @if($products)
                        @php $total_pr = 0; @endphp
                        @foreach($products as $o_pro)
                        @php 
                          $order_info = get_return_quantity($order->order_id, $o_pro->product_id);
                        @endphp
                      
                        <tr style="border-top: 1px solid #d9d9d9;">
                          <td>
                            <a href="{{ route('productDetail', $o_pro->url)}}">
                                <img class="img-fluid d-block max-h-100" style="max-width: 120px;" src="{!! asset($o_pro->thumbnail) !!}" alt="" style="max-width: 140px; margin-left: 30px; box-shadow: 1px 1px 6px rgba(0,0,0,0.1); padding: 15px;"></a>
                          </td>
                          <td class="text-left" style="vertical-align: middle;">
                            <h6 class="mb-0" style="font-family: 'Poppins', sans-serif;color: #212529;font-weight: 300;">{{ $o_pro->name }} {{ $o_pro->weight }} {{ $o_pro->unit }}</h6>
                          </td>
                          <td style="vertical-align: middle;">
                            <p class="mb-0">{{ $order_info->quantity }}</p>
                          </td>
                       
                        </tr>
                        @endforeach
                        @endif
                        @endif
                     

                      </tbody>
                    </table>
                  </div>

                </div>

              </div>
            </div>

          </div>
        </div>

              
      </div>
    </section>
  <!-- ORDER-DETAILS EDNDS -->

@endsection 