@extends('admin.layouts.master')
@section('content')
@php
    $route  = \Route::currentRouteName();    
@endphp
<div class="agile-grids">   
    <div class="grids">       
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">{{ $user->name }}'s Products <a class="btn btn-sm btn-primary pull-right" href="{!! route('customer') !!}"> <i class="fa fa-arrow-left"></i> All Customers </a></h1>
                <div class="panel panel-widget forms-panel" style="float: left;width: 100%; padding-bottom: 20px;">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                          <!--   <div class="form-title">
                                <h4>Customer Information</h4>                        
                            </div> -->
                            <div class="form-body">
                            {!! Form::open(array('method' => 'POST', 'route' => array('customer.update-products'), 'id' => 'ajaxSave', 'class' => '')) !!}
                            <input type="hidden" name="user_id" value="{{ $user_id }}">
                            <table class="product_tb">
                            <tr>
                                <th style="text-align: left;">Product Name</th>
                                <th style="text-align: left;">SKU</th>
                                <th style="text-align: left;">MRP</th>
                                <th style="text-align: left;">Sale Price</th>
                                <th>Discounted Price</th>  
                            </tr>

                            @foreach($products as $product)
                        @php
                            $ex_price = get_ex_price($user_id, $product->id);
                        @endphp

                            <tr>
                                <td style="text-align: left;">{{ $product->name }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>${{ $product->regular_price }}</td>
                                <td>${{ $product->offer_price }}</td>
                                <td style="text-align: center;"> 
                                    <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                    <input style="padding: 5px; max-width: 90px; margin: 0 auto; border: 1px solid;" type="number" name="price[{{$product->id}}]" step="0.01" @if($ex_price != 0) value="{{ $ex_price }}" @endif >
                                </td>  
                            </tr>
                            @endforeach                                

                            </table>  

                            <input type="submit" class="pl_sub" name="submit" value="Submit">   
                                    
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<style type="text/css">
.product_tb th,
.product_tb td{
    border: 1px solid #000;
    padding: 10px;
}    
.product_tb {
    width: 100%;
    margin-top: 20px;
    border: 1px solid;
}
.pl_sub {
    background: #009bdf;
    color: #fff;
    float: right;
    margin-top: 15px;
    border: 0px;
    padding: 7px 27px;
    font-size: 16px;
    letter-spacing: 1px;
    text-transform: uppercase;
    line-height: 26px;
}
</style>

@stop

