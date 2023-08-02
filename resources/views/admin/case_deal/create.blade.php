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
                <h1 class="page-header">Case Deal <a class="btn btn-sm btn-primary pull-right" href="{!! route('case-deal.index') !!}"> <i class="fa fa-solid fa-arrow-left"></i> All Case Deals</a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>Case Deal Information</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'case-deal.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('case-deal.store'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                @elseif($route == 'case-deal.edit')
                                    {!! Form::model($result, array('route' => array('case-deal.update', $result->id), 'method' => 'PATCH', 'id' => 'case-deal-form', 'class' => '', 'files'=>'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group view_hv"> 
                                            <label for="product_name">Product</label>
                                        @if(isset($result))
                                            <input class="form-control live_product_1 product_name" required="true" name="product_name" value="{{ @$product->name }}" type="text"> 
                                            <input type="hidden" name="product_id" value="{{ $result->product_id }}" class="product_id">
                                        @else
                                            {!! Form::text('product_name', null, array('class' => 'form-control live_product_1 product_name', 'required' => 'true')) !!}
                                            <input type="hidden" name="product_id" class="product_id">
                                        @endif
                                            @if($errors->has('product_id'))
                                             <span class="text-danger">{{$errors->first('product_id')}}</span>
                                            @endif
                                            <ul id="live_product_1"></ul>
                                            
                                        </div> 
                                    </div> 
                                    <div class="col-md-6">
                                         <div class="form-group"> 
                                            {!! Form::label('discount', lang('Discount'), array('class' => '')) !!}
                                            {!! Form::number('discount', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                        </div> 
                                    </div>  
                              
                                    <div class="col-md-6 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('quantity', lang('Min Quantity'), array('class' => '')) !!}
                                            {!! Form::number('quantity', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                        </div> 
                                    </div>
                                    
                                    <div class="col-md-6 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('max_quantity', lang('Max Quantity'), array('class' => '')) !!}
                                            {!! Form::number('max_quantity', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                        </div> 
                                    </div> 



                                    </div>
                                
                                <div class="row">
                                    <p>&nbsp;</p>
                                    <div class="col-md-7">
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

<style type="text/css">

#live_product_1 input{
    position: absolute;
    width: 100%;
    opacity: 0;
    height: 20px;
    z-index: 4;
} 
#live_product_1 li{
    cursor: pointer;
    position: relative;
    padding: 4px 10px;
}

label span{
  color: #f00;
} 

</style>

