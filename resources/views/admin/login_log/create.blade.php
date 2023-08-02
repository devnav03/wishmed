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
                <h1 class="page-header">Faqs <a class="btn btn-sm btn-primary pull-right" href="{!! route('faq.index') !!}"> <i class="fa fa-arrow-left"></i> All Faqs </a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>{!! lang('faq.faq') !!}</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'faq.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('faq.store'), 'id' => 'ajaxSave', 'class' => '', 'files' => 'true')) !!}
                                @elseif($route == 'faq.edit')
                                    {!! Form::model($result, array('route' => array('faq.update', $result->id), 'method' => 'PATCH', 'id' => 'faq-form', 'class' => '', 'files' => 'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group"> 
                                            {!! Form::label('title', lang('faq.title'), array('class' => '')) !!}
                                            {!! Form::text('title', null, array('class' => 'form-control')) !!}
                                        </div>  
                                    </div>
                                    <div class="col-md-12 mgn-top">  
                                     <div class="form-group"> 
                                            {!! Form::label('Description', lang('faq.description'), array('class' => '')) !!}
                                            {!! Form::textarea('description', null, array('class' => 'form-control')) !!}
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

