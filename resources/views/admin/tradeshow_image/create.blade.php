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
                <h1 class="page-header">Tradeshow Image <a class="btn btn-sm btn-primary pull-right" href="{!! route('tradeshow-images.index') !!}"> <i class="fa fa-solid fa-arrow-left"></i> All Tradeshow Images</a></h1>
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-body">
                                @if($route == 'tradeshow-images.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('tradeshow-images.store'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                @elseif($route == 'tradeshow-images.edit')
                                    {!! Form::model($result, array('route' => array('tradeshow-images.update', $result->id), 'method' => 'PATCH', 'id' => 'tradeshow-images-form', 'class' => '', 'files'=>'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-6">
                                         <div class="form-group"> 
                                            <label>Name</label>
                                            {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                            @if($errors->has('name'))
                                             <span class="text-danger">{{$errors->first('name')}}</span>
                                            @endif
                                        </div> 
                                    </div>                     
                                    <div class="col-md-12" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            <label>Image</label> 
                                            @if(!empty($result->image))
                                            {!! Form::file('image', array()) !!}
                                            @else
                                            {!! Form::file('image', array('required' => 'true')) !!}
                                            @endif
                                            @if ($errors->has('image'))
                                             <span class="text-danger">{{$errors->first('image')}}</span>
                                            @endif
                                        </div>
                                        @if(!empty($result->image))
                                            <div class="form-group"> 
                                                {!! HTML::image(asset($result->image),'' ,array('width' => 100 ,'class'=>'img-responsive') ) !!}
                                            </div>
                                        @endif
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
