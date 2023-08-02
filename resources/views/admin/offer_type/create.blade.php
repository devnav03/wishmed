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
                <h1 class="page-header">{!! lang('offer_type.offer_type') !!} <a class="btn btn-sm btn-primary pull-right" href="{!! route('offer-type.index') !!}"> <i class="fa fa-plus fa-fw"></i> All {!! lang('offer_type.offer_type') !!}</a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>{!! lang('offer_type.offer_type_info') !!}</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'offer-type.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('offer-type.store'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                @elseif($route == 'offer-type.edit')
                                    {!! Form::model($result, array('route' => array('offer-type.update', $result->id), 'method' => 'PATCH', 'id' => 'offer-type-form', 'class' => '', 'files'=>'true')) !!}
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

