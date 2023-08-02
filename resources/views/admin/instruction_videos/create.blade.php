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
                <h1 class="page-header">Instruction Videos & Presentations <a class="btn btn-sm btn-primary pull-right" href="{!! route('instruction-videos.index') !!}"> <i class="fa fa-solid fa-arrow-left"></i> All Instruction Videos & Presentations</a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>Instruction Videos & Presentations Information</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'instruction-videos.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('instruction-videos.store'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                @elseif($route == 'instruction-videos.edit')
                                    {!! Form::model($result, array('route' => array('instruction-videos.update', $result->id), 'method' => 'PATCH', 'id' => 'case-deal-form', 'class' => '', 'files'=>'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group view_hv"> 
                                         <label for="product_name">Product</label>
                                        @if(isset($result))
                                            <input class="form-control live_product_1 product_name" name="product_name" value="{{ @$product->name }}" type="text"> 
                                            <input type="hidden" name="product_id" value="{{ $result->product_id }}" class="product_id">
                                        @else
                                            {!! Form::text('product_name', null, array('class' => 'form-control live_product_1 product_name')) !!}
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
                                        {!! Form::label('name', lang('Title'), array('class' => '')) !!}
                                        {!! Form::text('name', null, array('class' => 'form-control')) !!}
                                        @if($errors->has('name'))
                                            <span class="text-danger">{{$errors->first('name')}}</span>
                                        @endif
                                        </div> 
                                    </div>
                                    
                                    <div class="col-md-6 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('iframe_code', lang('Youtube code'), array('class' => '')) !!}
                                            {!! Form::text('iframe_code', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('iframe_code'))
                                             <span class="text-danger">{{$errors->first('iframe_code')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-1 mgn20"> 
                                     <p style="margin-top: 30px;">OR</p>
                                     </div>
                                     <div class="col-md-5 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('video1', lang('Upload Video'), array('class' => '')) !!}
                                            {!! Form::file('video1', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('video1'))
                                             <span class="text-danger">{{$errors->first('video1')}}</span>
                                            @endif
                                           @if(isset($result))
                                           @if($result->video)
                                           <video width="100%" controls style="margin-top: 30px;">
                                           <source src="{{ $result->video }}" type="video/mp4">
                                           <source src="{{ $result->video }}" type="video/ogg">
                                           Your browser does not support HTML video.
                                           </video>
                                           @endif
                                           @endif
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

