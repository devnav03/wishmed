@extends('frontend.layouts.app')
@section('content')

 <section class="bredcrum">
    <div class="container-fluid">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>My Orders</li>
      </ul>
    </div>
  </section>
  <div class="clearfix"></div>



  <!-- MY ORDERS STARTS -->
    <section class="myorders py-5">
      <div class="container-fluid">
        <div class="row">
          <div class="col-xl-12">
            <div class="txt-box">
           <div class="row">      
            <div class="col-md-12">  
                <div class="account-orders">
                  @if(isset($orders))
                  <div class="hist_tax">
                  <h3>Retutn Orders</h3>
                  </div>
                    @php $i =0;  @endphp
                    @if($orders)
                  <div class="table-responsive">
                    
                    <table class="table table-borderless mb-4">
                      <thead class="only-desktop">
                        <tr class="text-uppercase" style="background: #f47624;">
                          <th style="border-right: 1px solid #d9d9d9; color: #fff; text-align: center;">ORDER ID</th>
                          <th style="border-right: 1px solid #d9d9d9; color: #fff; text-align: center;">PICKUP DATE</th>
                          <th style="border-right: 1px solid #d9d9d9; color: #fff; text-align: center;">REASON</th>
                          <th style="border-right: 1px solid #d9d9d9; color: #fff; text-align: center;">CUSTOMER NAME</th>
                          <th style="border-right: 1px solid #d9d9d9; color: #fff; text-align: center;">MOBILE</th>
                          <th style="border-right: 1px solid #d9d9d9; color: #fff; text-align: center;">ADDRESS</th>
                          <th style="border-right: 1px solid #d9d9d9; color: #fff; text-align: center;">PINCODE</th>
                          <th style="color: #fff; text-align: center;">ACTION</th>
                        </tr>
                      </thead>
                      @php $already_pro6 = []; @endphp
                      @foreach($orders as $order)
                      @php $i++;  
               if(in_array($order->order_nr, $already_pro6)) {

                  }
                   else{              
                  $already_pro6[] = $order->order_nr; 
              @endphp 
                <thead class="only-desktop">
                  <tr>
                    <td style="border-right: 1px solid #d9d9d9;text-align: center;"><p class="mb-0">{{ $order->order_nr }}</p> </td>
                    <td style="border-right: 1px solid #d9d9d9;text-align: center;"><p class="mb-0">{{ $order->pickup_date }} ({{ $order->pickup_slot }}) </p></td>
                    <td style="border-right: 1px solid #d9d9d9;text-align: center;"><p class="mb-0">{{ $order->reason }}</p></td>
                    <td style="border-right: 1px solid #d9d9d9;text-align: center;"><p class="mb-0">{{ $order->name }}</p></td>
                    <td style="border-right: 1px solid #d9d9d9;text-align: center;"><p class="mb-0">{{ $order->mobile }}</p></td>
                    <td style="border-right: 1px solid #d9d9d9;text-align: center;"><p class="mb-0">{{ $order->address }}</p></td>
                    <td style="border-right: 1px solid #d9d9d9;text-align: center;"><p class="mb-0">{{ $order->pincode }}</p></td>
              
                    <td style="text-align: center;">
                      @if($order->boy_pickup == 0)
                    <a class="btn btn-xs btn-primary" href="{{ route('enter-return-otp', $order->id) }}" style="background: #f47624; border-color: #f47624;"><i class="fa fa-edit"></i></a> @endif </td>
                  </tr>
                </thead>
                      @php
                        }
                      @endphp
                       @endforeach

                      <tbody>
                      </tbody>
                    </table>
                   
                  </div>
                  @if($i == 0)
                  <div class="text-center">
                  <img src="{!! asset('assets/frontend/images/No-Product-found.jpg') !!}" class="img-fluid d-inline-block max-height-400" alt=""> 
                  </div>
                  @endif
                  @endif
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
 
   
<style type="text/css">
.myorders .txt-box .account-orders table th, 
.myorders .txt-box .account-orders table td{  
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    font-size: 14px;
}
</style>   

  @endsection  

  