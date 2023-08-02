@extends('frontend.layouts.app')
@section('content')

 <section class="bredcrum">
    <div class="container">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>&nbsp;/&nbsp;My Orders</li>
      </ul>
    </div>
  </section>
  <div class="clearfix"></div>


    <section class="myorders">
      <div class="container">
        <div class="row">
          <div class="col-xl-12">
            <div class="txt-box">
           <div class="row">      
            <div class="col-md-12">  
                <div class="account-orders">
                  @if(isset($orders))
                  <div class="hist_tax">
                @if(count($orders) != 0)  
                  <h3>Your Orders</h3>
                @endif
                  @if (Session::has('success1'))
                      <div class="alert alert-success">
                          <button data-dismiss="alert" class="close">
                              &times;
                          </button>
                          <i class="fa fa-check-circle"></i> &nbsp;
                          {!! Session::get('success1') !!}
                      </div>
                  @endif
                  </div>
                    @php $i =0;  @endphp
                    @if($orders)
                  <div class="table-responsive">
                    @foreach($orders as $order)
                    @php $i++;  @endphp
                    <table class="table table-borderless mb-4">
                      <thead class="only-desktop">
                        <tr class="text-uppercase text-center">
                          <th>
                            <p style="font-weight: 600;">ORDER PLACED</p>
                            <p>{{ date('d M Y', strtotime($order->created_at)) }}</p>
                          </th>
                          <th>
                            <p style="font-weight: 600;">ORDER STATUS</p>
                            <p>{{ $order->type }}</p>
                          </th>
                          <th>
                            <p style="font-weight: 600;">total</p>
                            <p style="color: #9D1515;"><i class="fa fa-dollar"></i>{{ $order->total_price }}</p>
                          </th>
                          <th>
                            <p style="font-weight: 600;">ship to</p>
                          @if($order->ship_different_address == 1)
                            <p>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</p>
                          @else
                            <p>{{ $order->billing_first_name }} {{ $order->billing_last_name }}</p>
                          @endif
                          </th>
                          <th>
                            <p style="font-weight: 600;">ORDER ID</p>
                            <p>{{ $order->order_nr }}</p>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach(get_order_product($order->id) as $o_pro)
                        <tr>
                          <td class="order-info" colspan="3">
                            <div class="row">
                              <div class="col-3 pr-0">
                                <a href="{{ route('productDetail', $o_pro->url)}}">
                                <img class="img-fluid mx-auto d-block" src="{!! asset($o_pro->thumbnail) !!}" alt=""></a>
                              </div>
                              <div class="col-9 my-auto">
                                <h6 class="mb-0">{{ $o_pro->name }}</h6>
                              </div>  
                            </div>
                          </td>
                          <td class="delivery-info only-desktop">
                              <!--  <a href="{!! route('productDetail', $o_pro->url) !!}#review"> Write a review</a><br> -->
                               <a href="{{ route('order-detail', $order->id) }}" class="btn btn-edit only-phone">order details</a><br>
                              
                          </td>
                          <td class="text-center total-info only-desktop"> 
                           <!--    <a href="{{ route('order-invoice', $order->id) }}" class="btn btn-edit">invoice</a><br> -->
                               <a style="background: #20A436;" href="{{ route('order-detail', $order->id) }}#order-track" class="btn btn-trk">track order</a>
                             
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    @endforeach
                  </div>
                  @if($i == 0)
                  <div class="text-center">
                  <h5>No Order Found</h5>
                  </div>
                  @endif
                  @endif
                  @endif
                  @if(isset($orders))
                      {{ $orders->links() }}
                    @endif
                </div>
              </div>
            </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
  <!-- MY ORDERS ENDS -->
 
   
   

  @endsection  

  