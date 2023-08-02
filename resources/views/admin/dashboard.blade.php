@extends('admin.layouts.master')
@section('content')
@section('css')
<!-- tables -->
<link rel="stylesheet" type="text/css" href="{!! asset('css/table-style.css') !!}" />
<!-- //tables -->
@endsection
  <div class="social grid">
    <div class="grid-info">
        <div class="col-md-3 top-comment-grid">
          <a href="{!! route('order.index') !!}">
            <div class="comments">
                <div class="comments-icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="comments-info">
                    <h3>{{ $total_order }}</h3>
                    <a href="#">Total Orders</a>
                </div>
                <div class="clearfix"> </div>
            </div>
          </a>
        </div>

        <div class="col-md-3 top-comment-grid">
          <a href="{!! route('order.index') !!}">
            <div class="comments">
                <div class="comments-icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="comments-info">
                    <h3>{{ $newtotal_order }}</h3>
                    <a href="#">New Orders</a>
                </div>
                <div class="clearfix"> </div>
            </div>
          </a>
        </div>
       @if((\Auth::user()->user_type) == 1)
        <div class="col-md-3 top-comment-grid">
          <a href="{!! route('customer') !!}">
            <div class="comments likes">
                <div class="comments-icon">
                    <i class="fa fa-user"></i>
                </div>
                <div class="comments-info likes-info">
                    <h3>{!! $user->total !!}</h3>
                    <a href="#">Total Users</a>
                </div>
                <div class="clearfix"> </div>
            </div>
          </a>
        </div>
        <div class="col-md-3 top-comment-grid">
          <a href="{!! route('customer') !!}">
            <div class="comments likes">
                <div class="comments-icon">
                    <i class="fa fa-user"></i>
                </div>
                <div class="comments-info likes-info">
                    <h3>{!! $newusers !!}</h3>
                    <a href="#">New Users</a>
                </div>
                <div class="clearfix"> </div>
            </div>
          </a>
        </div>
       
        <div class="col-md-3 top-comment-grid" style="margin-top: 1rem;">
          <a href="{!! route('product.index') !!}">
            <div class="comments tweets">
                <div class="comments-icon">
                    <img src="{{ url('/') }}/images/product_ic.png" class="side_icon">
                </div>
                <div class="comments-info tweets-info">
                    <h3>{{ $product }}</h3>
                    <a href="#">Total Products</a>
                </div>
                <div class="clearfix"> </div>
            </div>
          </a>
        </div>
        <div class="col-md-3 top-comment-grid">
          <a href="{!! route('product.index') !!}">
            <div class="comments tweets">
                <div class="comments-icon">
                    <img src="{{ url('/') }}/images/product_ic.png" class="side_icon">
                </div>
                <div class="comments-info tweets-info">
                    <h3>{{ $newproduct }}</h3>
                    <a href="#">New Products</a>
                </div>
                <div class="clearfix"> </div>
            </div>
          </a>
        </div>

        <div class="col-md-3 top-comment-grid">
          <a href="#">
            <div class="comments views">
                <div class="comments-icon">
                    <i class="fa fa-dollar" style="color: #fff; font-size: 4em !important;margin: 0;padding: 0;"></i>
                </div>
                <div class="comments-info views-info">
                    <h3>{{ $payment }}</h3>
                    <a href="#">Total Income</a>
                </div>
                <div class="clearfix"> </div>
            </div>
          </a>
        </div>
        @endif
        <div class="clearfix"> </div>
    </div>
  </div>


   <div class="social grid col-md-12">
    <div class="grid-info row">
        <div class="col-md-6" style="padding-left: 0px;">
        <h3 style="font-weight: 500; font-size: 22px; margin-top: 15px;">Recent Order</h3>    
        <table style="margin-top: 7px;">
            <tr>
                <th>No.</th>
                <th>Customer Name</th>
                <th>Order No</th>
                <th style="text-align: center;">Action</th> 
            </tr>
            @php
            $i = 1;
            $j = 1;
            @endphp
            @foreach($orders as $order)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $order->name }}</td>
                <td>{{ $order->order_nr }}</td>
                <td style="text-align: center;"><a class="btn btn-xs btn-primary" href="{{ route('order.edit', [$order->id]) }}"><i class="fa fa-edit"></i></a></td> 
            </tr>
            @endforeach
        </table>
        </div>
        @if((\Auth::user()->user_type) == 1)
        <div class="col-md-6" style="padding-right: 0px;">
            <h3 style="font-weight: 500; font-size: 22px; margin-top: 15px;">Top Sellers</h3>    
            <table style="margin-top: 7px;">
            <tr>
                <th>No.</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <!-- <th style="text-align: center;">Action</th> -->
            </tr>
            @php
            $i = 1;
            @endphp
            @foreach($OrderProducts as $OrderProduct)
            @php
            $pro = get_max_sale_product($OrderProduct->product_id);
           
            @endphp
            @if(isset($pro->name))
            <tr>
                <td>{{ $j++ }}</td>
                <td>{{ $pro->name }}</td>
                <td>{{ $OrderProduct->max_qty }}</td>
                <!-- <td style="text-align: center;"><a class="btn btn-xs btn-primary" href="{{ route('product.edit', [$OrderProduct->product_id]) }}"><i class="fa fa-edit"></i></a></td> --> 
            </tr>
            @endif
            @endforeach
            </table>
        </div>
        @endif
   
    </div>
  </div>
  
@endsection