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
                <h1 class="page-header">{!! lang('testimonial.testimonial') !!} <a class="btn btn-sm btn-primary pull-right" href="{!! route('testimonials.index') !!}"> <i class="fa fa-plus fa-fw"></i> All Testimonials</a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>{!! lang('testimonial.testimonial_info') !!}</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'testimonials.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('testimonials.store'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                @elseif($route == 'testimonials.edit')
                                    {!! Form::model($result, array('route' => array('testimonials.update', $result->id), 'method' => 'PATCH', 'id' => 'testimonials-form', 'class' => '', 'files'=>'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-10">
                                         <div class="form-group"> 
                                            {!! Form::label('name', lang('common.name'), array('class' => '')) !!}
                                            {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                            @if ($errors->has('name'))
                                             <span class="text-danger">{{$errors->first('name')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-10">
                                         <div class="form-group" style="margin-top: 20px;"> 
                                            {!! Form::label('review', lang('Review'), array('class' => '')) !!}
                                            {!! Form::textarea('review', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                            @if ($errors->has('review'))
                                             <span class="text-danger">{{$errors->first('review')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-12" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            {!! Form::label('image', lang('Image (size 200X200)'), array('class' => '')) !!}
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
                                                 {!! HTML::image(asset($result->image),'' ,array('width' => 70 , 'height' => 70,'class'=>'img-responsive') ) !!}
                                            </div>
                                        @endif
                                    </div> 

                                    </div>
                                        <div class="checkbox"> 
                                            <label>{!! Form::checkbox('status', '1', true, array('required' => 'true')) !!} Status </label> 
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

