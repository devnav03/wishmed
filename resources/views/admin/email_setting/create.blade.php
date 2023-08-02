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
                <h1 class="page-header">Email Settings</h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                          <!--   <div class="form-title">
                                <h4>{!! lang('product.product_info') !!}</h4>                        
                            </div> -->
                            <div class="form-body">
                                    {!! Form::model($result, array('route' => array('email-settings.update', $result->id), 'method' => 'PATCH', 'id' => 'email-settings-form', 'class' => '', 'files'=>'true')) !!}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group"> 
                                            <label for="new_order_email" style="font-size: 16px; margin-bottom: 5px;">New Order's Email<span>*</span></label>
                                            {!! Form::textarea('new_order_email', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('new_order_email'))
                                             <span class="text-danger">{{$errors->first('new_order_email')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 mgn20">
                                        <div class="form-group"> 
                                            <label for="cancel_order_email" style="font-size: 16px; margin-bottom: 5px;">Cancel Order's Email<span>*</span></label>
                                            {!! Form::textarea('cancel_order_email', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('cancel_order_email'))
                                             <span class="text-danger">{{$errors->first('cancel_order_email')}}</span>
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
