@extends('admin.layouts.master')
@section('content')
@include('admin.layouts.messages')
@php
    $route  = \Route::currentRouteName();    
@endphp
<div class="agile-grids">   
    <div class="grids">       
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">Tax Settings</h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                          <!--   <div class="form-title">
                                <h4>{!! lang('product.product_info') !!}</h4>                        
                            </div> -->
                            <div class="form-body">
                                    {!! Form::model($result, array('route' => array('tax-amounts.update', $result->id), 'method' => 'PATCH', 'id' => 'tax-amount-form', 'class' => '', 'files'=>'true')) !!}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group"> 
                                            <label for="product_tax" style="font-size: 16px; margin-bottom: 5px;">Product Tax %<span>*</span></label>
                                            {!! Form::number('product_tax', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('product_tax'))
                                             <span class="text-danger">{{$errors->first('product_tax')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"> 
                                            <label for="shipping_tax" style="font-size: 16px; margin-bottom: 5px;">Shipping Tax %<span>*</span></label>
                                            {!! Form::number('shipping_tax', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('shipping_tax'))
                                             <span class="text-danger">{{$errors->first('shipping_tax')}}</span>
                                            @endif
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
        </div>
    </div>
</div>


@stop
