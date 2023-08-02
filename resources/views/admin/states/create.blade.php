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
                <h1 class="page-header">{!! lang('states.states') !!} <a class="btn btn-sm btn-primary pull-right" href="{!! route('states.index') !!}"> <i class="fa fa-plus fa-fw"></i> All {!! lang('states.states') !!}</a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>{!! lang('states.states_info') !!}</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'states.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('states.store'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                @elseif($route == 'states.edit')
                                    {!! Form::model($result, array('route' => array('states.update', $result->id), 'method' => 'PATCH', 'id' => 'states-form', 'class' => '', 'files'=>'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-12">
                                         <div class="form-group"> 
                                            {!! Form::label('name', lang('common.name'), array('class' => '')) !!}
                                            {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                            @if($errors->has('name'))
                                             <span class="text-danger">{{$errors->first('name')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-12" style="margin-top: 20px;">
                                         <div class="form-group"> 
                                            {!! Form::label('shipping_charges', lang('Shipping Charges'), array('class' => '')) !!}
                                            {!! Form::number('shipping_charges', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                            @if($errors->has('shipping_charges'))
                                             <span class="text-danger">{{$errors->first('shipping_charges')}}</span>
                                            @endif
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

