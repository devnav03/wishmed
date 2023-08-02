@extends('admin.layouts.master')
@section('content')
@include('admin.layouts.messages')
@php
    $route  = \Route::currentRouteName();    
@endphp
<div class="agile-grids">   
    <div class="grids">       
        <div class="row">
            <div class="col-md-10">
                <h1 class="page-header">About energy Xpo <a class="btn btn-sm btn-primary pull-right" href="{!! route('about-energy-xpo.index') !!}"> <i class="fa fa-arrow-left"></i> Home About </a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                         
                            <div class="form-body">
                                @if($route == 'about-energy-xpo.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('about-energy-xpo.store'), 'id' => 'ajaxSave', 'class' => '', 'files' => 'true')) !!}
                                @elseif($route == 'about-energy-xpo.edit')
                                    {!! Form::model($result, array('route' => array('about-energy-xpo.update', $result->id), 'method' => 'PATCH', 'id' => 'about-energy-xpo-form', 'class' => '', 'files' => 'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group"> 
                                            {!! Form::label('heading', lang('Page Heading'), array('class' => '')) !!}
                                            {!! Form::text('heading', null, array('class' => 'form-control')) !!}
                                        </div>  
                                    </div>
                                    <div class="col-md-8 mgn-top"> 
                                        <div class="form-group"> 
                                            {!! Form::label('Description 1', lang('Description 1'), array('class' => '')) !!}
                                            {!! Form::textarea('description_1', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-8 mgn-top"> 
                                         <div class="form-group"> 
                                            {!! Form::label('Description 2', lang('Description 2'), array('class' => '')) !!}
                                            {!! Form::textarea('description_2', null, array('class' => 'form-control')) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('file', lang('common.image'), array('class' => '')) !!}
                                            {!! Form::file('image', null, array('class' => 'form-control')) !!}

                                        </div>  
                                        @if(!empty($result->image))
                                            <div class="form-group"> 
                                                {!! HTML::image(asset('uploads/about-energy-xpo-image/'.$result->image),'' ,array('width' => 70 , 'height' => 70,'class'=>'img-responsive') ) !!}
                                            </div>
                                        @endif   
                                        <div class="form-group"> 
                                            {!! Form::label('second_heading', lang('Page Second Heading'), array('class' => '')) !!}
                                            {!! Form::text('second_heading', null, array('class' => 'form-control')) !!}
                                        </div> 
                                        <div class="form-group"> 
                                            {!! Form::label('growing_description_1', lang('Growing Description 1'), array('class' => '')) !!}
                                            {!! Form::textarea('growing_description_1', null, array('class' => 'form-control')) !!}
                                        </div>  
                                        <div class="form-group"> 
                                            {!! Form::label('growing_description_2', lang('Growing Description 2'), array('class' => '')) !!}
                                            {!! Form::textarea('growing_description_2', null, array('class' => 'form-control')) !!}
                                        </div>
                                         <div class="form-group">
                                            {!! Form::label('file', lang('Page Second image '), array('class' => '')) !!}
                                            {!! Form::file('image_2', null, array('class' => 'form-control')) !!}

                                        </div>  
                                        @if(!empty($result->image_2))
                                            <div class="form-group"> 
                                                {!! HTML::image(asset('uploads/about-energy-xpo-image/'.$result->image_2),'' ,array('width' => 70 , 'height' => 70,'class'=>'img-responsive') ) !!}
                                            </div>
                                        @endif   
                                        <div class="form-group"> 
                                            {!! Form::label('   heading_3', lang('Page Third heading'), array('class' => '')) !!}
                                            {!! Form::text('heading_3', null, array('class' => 'form-control')) !!}
                                        </div> 
                                        <div class="form-group"> 
                                            {!! Form::label('electric_description_1', lang('Electric  Description 1'), array('class' => '')) !!}
                                            {!! Form::textarea('electric_description_1', null, array('class' => 'form-control')) !!}
                                        </div>  
                                        <div class="form-group"> 
                                            {!! Form::label('electric_description_2', lang('Electric  Description 2'), array('class' => '')) !!}
                                            {!! Form::textarea('electric_description_2', null, array('class' => 'form-control')) !!}
                                        </div>  
                                         <div class="form-group">
                                            {!! Form::label('file', lang('Page Third Image'), array('class' => '')) !!}
                                            {!! Form::file('image_3', null, array('class' => 'form-control')) !!}

                                        </div>  
                                        @if(!empty($result->image_3))
                                            <div class="form-group"> 
                                                {!! HTML::image(asset('uploads/about-energy-xpo-image/'.$result->image_3),'' ,array('width' => 70 , 'height' => 70,'class'=>'img-responsive') ) !!}
                                            </div>
                                        @endif   
                                        <div class="checkbox"> 
                                            <label>{!! Form::checkbox('status', '1', true) !!} Status </label> 
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

