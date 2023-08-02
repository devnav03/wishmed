@extends('admin.layouts.master')
@section('css')
<!-- tables -->
<link rel="stylesheet" type="text/css" href="{!! asset('css/table-style.css') !!}" />
<!-- //tables -->
@endsection
@section('content')
@include('admin.layouts.messages')
@php
    $route  = \Route::currentRouteName();    
@endphp
<div class="agile-grids">   
    <div class="grids">       
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">{!! lang('order.order') !!}   <a class="btn btn-sm btn-primary pull-right" href="{!! route('order.index') !!}"> <i class="fa fa-arrow-left"></i> All {!! lang('order.order') !!}</a></h1>
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>{!! lang('order.order_info') !!}</h4>                        
                            </div>
                            <div class="form-body">
                            @if(session()->has('transaction'))
                                <p style="color: #f00; font-size: 16px; margin-bottom: 15px; margin-top: -7px;">Kindly update Transaction ID</p>
                            @endif 
                            @if(session()->has('order_edit'))
                                <li class="alert alert-success" style="list-style: none; margin-top: 25px;">Order successfully updated.</li>
                            @endif
                            
                              
                                <table class="order_info" style="text-align: center;">
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Customer Name</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{{ $user_name->name }}</td>
                                    </tr>
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Customer Email</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{{ $user_name->email }}</td>
                                    </tr>

                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Order No.</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{{ $result->order_nr }}</td>
                                    </tr>
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Order Date</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;"> {!! date('d M, Y', strtotime($result->created_at)) !!}</td>
                                    </tr>
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Total Amount</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;"> <i class="fa fa-dollar-sign"></i>{!! $result->total_price !!}</td>
                                    </tr>
                                    @if($result->discount)
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Discount</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;"> <i class="fa fa-dollar-sign"></i>{!! $result->discount !!}</td>
                                    </tr>
                                    @endif
                                    @if($result->offer_id)
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Offer</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;"> <i class="fa fa-dollar-sign"></i>{!! $offer->title !!}</td>
                                    </tr>
                                    @endif
                                </table>
                       
                                <div class="form-title" style="float: left; width: 100%; margin-top: 20px;">
                                    <h4>Billing Address</h4>
                                </div>
                                <table class="order_info" style="text-align: center;margin-top: 30px; float: left;">
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Name</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{{ $result->billing_first_name }} {{ $result->billing_last_name }}</td>
                                    </tr>
                                    @if($result->billing_company_name)
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Company Name</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{{ $result->billing_company_name}}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Address</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{{ $result->billing_street_address }} <br>{{ $result->billing_street_address2 }}</td>
                                    </tr>
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Suburb</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{{ $result->billing_suburb }}</td>
                                    </tr>
                                    @php
                                        $select = DB::table('shipping_zones')->where('id', $result->billing_state)->select('name')->first();
                                    @endphp
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">State</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{!! $select->name !!}</td>
                                    </tr>
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Pin Postcode</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{{ $result->billing_postcode }}</td>
                                    </tr>
                                    @if($result->billing_email_address)
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Email</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{{ $result->billing_email_address }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Mobile</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{!! $result->billing_phone !!}</td>
                                    </tr>

                                    @if($result->ship_different_address != 1)
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;"></td>
                                        <td width="10%"></td>
                                        <td width="45%" style="text-align: left;font-size: 15px;"> Shipping address is also same</td>
                                    </tr>
                                    @endif

                                </table>

                                 @if($result->ship_different_address == 1)
                                <div class="form-title" style="float: left; width: 100%; margin-top: 20px;">
                                    <h4>Shipping Address</h4>
                                </div>
                                <table class="order_info" style="text-align: center;margin-top: 30px; float: left;">
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Name</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{{ $result->shipping_first_name }} {{ $result->shipping_last_name }}</td>
                                    </tr>
                                    @if($result->shipping_company_name)
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Company Name</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{{ $result->shipping_company_name}}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Address</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{{ $result->shipping_street_address }} {{ $result->shipping_street_address2 }}</td>
                                    </tr>
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Suburb</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{{ $result->shipping_suburb }}</td>
                                    </tr>
                                    @php
                                        $select = DB::table('shipping_zones')->where('id', $result->shipping_state)->select('name')->first();
                                    @endphp

                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">State</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{!! $select->name !!}</td>
                                    </tr>
                                    <tr>
                                        <td width="45%" style="text-align: right; font-size: 16px;">Pin Postcode</td>
                                        <td width="10%">:</td>
                                        <td width="45%" style="text-align: left;font-size: 15px;">{{ $result->shipping_postcode }}</td>
                                    </tr>
                                </table>
                                @endif


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="color: #000; font-weight: 500; font-size: 18px;" id="exampleModalLabel">Products Details</h5>
        <button type="button" style="margin-top: -20px;" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <form action="{{ route('order.order-update') }}" method="post">
            {{ csrf_field() }}
        <input type="hidden" name="order_id" value="{{ $result->id }}">    
        <div class="modal-body">
            <table>
                <tr>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
                @foreach($OrderProduct as $Product) 
                <tr>
                    <td>{{ $Product->name }}</td>
                    <td>{{ $Product->sku }}</td>
                    <td>${!! $Product->price !!}</td>
                    <td>
                        <input value="{{ $Product->id }}" type="hidden" name="product_id[]" required="true">
                        <input value="{{ $Product->quantity }}" type="number" name="quantity[{{ $Product->id }}]" style="width: 75px; text-align: center; height: 40px;" required="true">
                    </td>
                </tr>
                @endforeach
            </table>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
    </div>
  </div>
</div>


    <div class="row" id="Products_Detail">
    <div class="col-md-12">
        <div class="form-title" style="float: left; width: 100%; margin-top: 20px;">
            <h4>Products Detail @if($result->current_status != 4 && $result->current_status != 5) <a style="color: #f00; font-size: 14px; font-weight: 500; float: right;" data-toggle="modal" data-target="#exampleModal" href="#">Edit...<i class="fa fa-pen" aria-hidden="true"></i></a> @endif </h4>
        </div>
    </div>
    <div class="col-md-12">
    <table class="table table-hover">                          
        <thead>
            <tr>
                <th>Name</th>
                <th class="text-center">SKU</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Regular Price</th>
                <th class="text-center">Unit Price</th>
                <th class="text-center">Total Price</th>
            </tr>
        </thead>
<tbody>
<?php $i = 1;
$p = 0; ?>
@foreach($OrderProduct as $Product)
<tr>
    <td>{{ $Product->name }}</td>
    <td>{{ $Product->sku }}</td>
    <td class="text-center">{!! $Product->quantity !!}</td>
    <td class="text-center"><i class="fa fa-dollar-sign"></i> {!! $Product->regular_price !!}  </td>
    <td class="text-center"><i class="fa fa-dollar-sign"></i>{!! $Product->price !!}  </td>
    <td class="text-center"><i class="fa fa-dollar-sign"></i>  {!! $Product->price*$Product->quantity !!}  </td>
</tr>

@php $i++; $p += $Product->price*$Product->quantity @endphp   


@endforeach
<tr>
<td colspan="5">Sub Total</td>
<td class="text-center"><i class="fa fa-dollar-sign"></i> {{ $p }}</td>
</tr>
@if($result->offer_id)
<tr>
<td colspan="5">Discount</td>
<td colspan="2" class="text-center"><i class="fa fa-dollar-sign"></i> {{ $result->discount }}</td>
</tr>
@endif
<tr>
<td colspan="5">Shipping</td>
<td class="text-center"><i class="fa fa-dollar-sign"></i> {{ $result->shipping_price }}</td>
</tr>
<tr>
<td colspan="5">Tax</td>
<td class="text-center"><i class="fa fa-dollar-sign"></i> {{ $result->shipping_tax+$result->product_tax }}</td>
</tr>
<tr>
<td colspan="5"><b>Grand Total</b></td>
<td class="text-center"><b><i class="fa fa-dollar-sign"></i> {{ $result->total_price }}</b></td>
</tr>
</tbody>
</table>                              
</div>
</div>
                    
<!--<div class="row">
<div class="col-md-12">
<button type="submit" class="btn btn-default w3ls-button">Submit</button> 
 </div> 
</div>-->
                                  
                            
                                <div class="row">
                                    <div class="col-md-12">
                                    <div class="form-title" style="float: left; width: 100%;margin-top: 15px;
    margin-bottom: 20px;">
                                        <h4>Order Status</h4>
                                    </div>
                                    </div>
                                <div class="col-md-6">
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-body">
                  
                                {!! Form::open(array('method' => 'POST', 'route' => array('order-product-status'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" value="{{ $id }}" name="order_id">
                                         <div class="form-group"> 
                                            {!! Form::label('type', lang('Current Status'), array('class' => '')) !!}
                                            <select name="status" class="form-control" required ="true">
                                            <option value="">-Select-</option>   
                                            @foreach($statusType as $statusType) 
                                            <option value="{{ $statusType->id }}" @if($statusType->id == $result->current_status) selected @endif>{{ $statusType->type }}</option> 

                                            @endforeach
                                            
                                            </select>
                                        </div> 
                                    </div>   
                                    <div class="col-md-12 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('transaction_id', lang('Transaction ID'), array('class' => '')) !!}
                                            <input type="text" class="form-control" name="transaction_id" value="{{ $result->transaction_id }}"> 
                                        </div> 
                                    </div>
                                                                         
                                    </div>
                                <div class="row">
                                    <p>&nbsp;</p>
                                    <div class="col-md-12">
                                         <button type="submit" class="btn btn-default w3ls-button">Submit</button> 
                                    </div>
                                </div>
                                    
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">                
                <div class="agile-tables">
                    <div class="w3l-table-info">

                        {{-- for message rendering --}}
                        @include('admin.layouts.messages')
<table class="table table-hover" style="margin-top: 0px;">  
                       <thead>
<tr>
    <th class="text-center">#</th>
    <th>Date</th>
    <th>Status</th>
</tr>
</thead>
<tbody>
<?php $index = 2; ?>


<tr>
    <td class="text-center">1</td>
    <td>{!! $result->created_at !!}</td>
    <td>Order Received</td>
</tr>
@foreach($orderStatus as $detail)
<tr>
    <td class="text-center">{!! $index !!}</td>
    <td>{!! $detail->date !!}</td>
    <td>{!! $detail->type !!}</td>
</tr>
<?php $index++; ?>
@endforeach

</tbody>
</table>

                    </div>
                    
                </div>
            </div>
        </div>

<!-- <div class="row">
<div class="col-md-12">
<div class="form-title" style="float: left; width: 100%;margin-top: 15px;margin-bottom: 20px;">
<h4>Order Update</h4>
</div>
</div>
<div class="col-md-6">
 
<textarea name="message" required="true" placeholder="Type Message"> </textarea>
<button type="submit">Send</button>
{!! Form::close() !!}
</div>


</div>   -->  

@if($order_history)
    <div class="row">
        @foreach($order_history as $order_histor)
        <div class="col-md-12">
        <div class="form-title" style="float: left; width: 100%;margin-top: 15px;
        margin-bottom: 20px;">
            <h4>Order History <span style="float: right;"> {!! date('d M Y', strtotime($order_histor->created_at)) !!} </span></h4>
        </div>

         <table class="table table-hover">                          
        <thead>
            <tr>
                <th>Name</th>
                <th class="text-center">SKU</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Regular Price</th>
                <th class="text-center">Unit Price</th>
                <th class="text-center">Total Price</th>
            </tr>
        </thead>
        <tbody>
        <?php $i = 1;
        $p = 0; ?>
        @foreach($OrderProduct as $Product)
        @php
        $his_qty = get_old_qty($Product->id, $order_histor->id);
        @endphp
        <tr>
            <td>{{ $Product->name }}</td>
            <td>{{ $Product->sku }}</td>
            <td class="text-center">{!! $his_qty !!}</td>
            <td class="text-center"><i class="fa fa-dollar-sign"></i>{!! $Product->regular_price !!} </td>
            <td class="text-center"><i class="fa fa-dollar-sign"></i>{!! $Product->price !!} </td>
            <td class="text-center"><i class="fa fa-dollar-sign"></i> {!! $Product->price*$his_qty !!} </td>
        </tr>
        @endforeach
        </tbody>
        </table>
        </div>
        @endforeach
    </div>
@endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

<style type="text/css">
.order_info  tr,
.order_info tr td {
    background: transparent !important;
    border: 0px !important;
    text-align: center;
}  

.modal-dialog {
    width: 800px !important;
}



</style>










