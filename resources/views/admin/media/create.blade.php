@extends('admin.layouts.master')
@section('content')
@include('admin.layouts.messages')
@php
    $route  = \Route::currentRouteName();    
@endphp
<div class="agile-grids">   
    <div class="grids">       
        <div class="row">
            <div class="col-md-6">
                <h1 class="page-header">{!! lang('reviews.reviews') !!} <a class="btn btn-sm btn-primary pull-right" href="{!! route('reviews.index') !!}"> <i class="fa fa-plus fa-fw"></i> All {!! lang('reviews.reviews') !!}</a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>{!! lang('reviews.reviews_info') !!}</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'reviews.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('reviews.store'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                @elseif($route == 'reviews.edit')
                                    {!! Form::model($result, array('route' => array('reviews.update', $result->id), 'method' => 'PATCH', 'id' => 'reviews-form', 'class' => '', 'files'=>'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-12">
                                         <div class="form-group"> 
                                            {!! Form::label('name', lang('common.name'), array('class' => '')) !!}
                                            {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                        </div> 
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

