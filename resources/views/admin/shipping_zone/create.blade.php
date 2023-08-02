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
                <h1 class="page-header">Shipping Zone <a class="btn btn-sm btn-primary pull-right" href="{!! route('shipping-zone.index') !!}"> <i class="fa fa-arrow-left"></i> All shipping Zones </a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>Shipping Zone</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'shipping-zone.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('shipping-zone.store'), 'id' => 'ajaxSave', 'class' => '', 'files' => 'true')) !!}
                                @elseif($route == 'shipping-zone.edit')
                                    {!! Form::model($result, array('route' => array('shipping-zone.update', $result->id), 'method' => 'PATCH', 'id' => 'shipping-zone-form', 'class' => '', 'files' => 'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group"> 
                                            {!! Form::label('name', lang('Name'), array('class' => '')) !!}
                                            {!! Form::text('name', null, array('class' => 'form-control')) !!}
                                        </div>  
                                    </div>      
                                </div>

                                <div class="row" style="background: #f3f3f3; margin-top: 25px;">
                                <div class="col-md-4 slider_btn" style="margin-top: 15px;margin-bottom: 15px;">
                                    <div class="form-group"> 
                                    <label style="font-weight: 500; font-size: 15px; margin-bottom: 1px;">Flat Rate</label><br>
                                    <span>No</span>&nbsp; <label class="switch">
                                    <input type="checkbox" class="cred_value" @if(isset($result)) @if($result->flat_rate == 1) checked="true" @endif @endif name="flat_rate" value="1"> 
                                    <span class="slider round"></span>
                                    </label>&nbsp; <span>Yes</span>
                                    </div>  
                                </div> 

                                <div class="col-md-4" style="margin-top: 15px;    margin-bottom: 15px;">
                                    <div class="form-group"> 
                                    <label>Tax status</label>
                                    <select name="flat_tax" class="select2 form-control1">
                                    @if(isset($result))
                                        <option @if($result->flat_tax == 1) selected @endif value="1">Taxable</option>
                                        <option @if($result->flat_tax == 0) selected @endif value="0">None</option>
                                    @else
                                        <option value="1">Taxable</option>
                                        <option value="0">None</option>
                                    @endif
                                    </select>
                                    </div>  
                                </div> 

                                <div class="col-md-4" style="margin-top: 15px; margin-bottom: 15px;">
                                    <div class="form-group"> 
                                    <label>Cost</label>
                                    {!! Form::number('flat_price', null, array('class' => 'form-control')) !!}
                                    </div>  
                                </div> 
                                </div>


                                <div class="row" style="background: #f3f3f3; margin-top: 25px;">
                                    <div class="col-md-4 slider_btn" style="margin-top: 15px;margin-bottom: 15px;">
                                        <div class="form-group"> 
                                        <label style="font-weight: 500; font-size: 15px; margin-bottom: 1px;">Delivery</label><br>
                                        <span>No</span>&nbsp; <label class="switch">
                                        <input type="checkbox" class="cred_value" @if(isset($result)) @if($result->delivery == 1) checked="true" @endif @endif name="delivery" value="1"> 
                                        <span class="slider round"></span>
                                        </label>&nbsp; <span>Yes</span>
                                        </div>  
                                    </div> 

                                    <div class="col-md-4" style="margin-top: 15px;    margin-bottom: 15px;">
                                        <div class="form-group"> 
                                        <label>Tax status</label>
                                        <select name="delivery_tax" class="select2 form-control1">
                                        @if(isset($result))
                                            <option @if($result->delivery_tax == 1) selected @endif value="1">Taxable</option>
                                            <option @if($result->delivery_tax == 0) selected @endif value="0">None</option>
                                        @else
                                            <option value="1">Taxable</option>
                                            <option value="0">None</option>
                                        @endif
                                        </select>
                                        </div>  
                                    </div> 

                                    <div class="col-md-4" style="margin-top: 15px; margin-bottom: 15px;">
                                        <div class="form-group"> 
                                        <label>Cost</label>
                                        {!! Form::number('delivery_price', null, array('class' => 'form-control')) !!}
                                        </div>  
                                    </div> 
                                </div>

                                <div class="row" style="background: #f3f3f3; margin-top: 25px;">
                                <div class="col-md-4 slider_btn" style="margin-top: 15px;margin-bottom: 15px;">
                                    <div class="form-group"> 
                                    <label style="font-weight: 500; font-size: 15px; margin-bottom: 1px;">Local Pickup</label><br>
                                    <span>No</span>&nbsp; <label class="switch">
                                    <input type="checkbox" class="cred_value" @if(isset($result)) @if($result->local_pickup == 1) checked="true" @endif @endif name="local_pickup" value="1"> 
                                    <span class="slider round"></span>
                                    </label>&nbsp; <span>Yes</span>
                                    </div>  
                                </div> 

                                <div class="col-md-4" style="margin-top: 15px;    margin-bottom: 15px;">
                                    <div class="form-group"> 
                                    <label>Tax status</label>
                                    <select name="local_tax" class="select2 form-control1">
                                    @if(isset($result))
                                        <option @if($result->local_tax == 1) selected @endif value="1">Taxable</option>
                                        <option @if($result->local_tax == 0) selected @endif value="0">None</option>
                                    @else
                                        <option value="1">Taxable</option>
                                        <option value="0">None</option>
                                    @endif
                                    </select>
                                    </div>  
                                </div> 

                                <div class="col-md-4" style="margin-top: 15px; margin-bottom: 15px;">
                                    <div class="form-group"> 
                                    <label>Cost</label>
                                    {!! Form::number('local_price', null, array('class' => 'form-control')) !!}
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

