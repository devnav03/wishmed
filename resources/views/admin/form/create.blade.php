@extends('admin.layouts.master')
@section('content')
@include('admin.layouts.messages')
@php
    $route  = \Route::currentRouteName();    
@endphp
<div class="agile-grids">   
    <div class="grids">       
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1 class="page-header">Form<a class="btn btn-sm btn-primary pull-right" href="{!! route('form.index') !!}"> <i class="fa fa-arrow-left"></i> All Forms</a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>Form</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'form.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('form.store'), 'id' => 'ajaxSave', 'class' => '', 'files' => 'true')) !!}
                                @elseif($route == 'form.edit')
                                    {!! Form::model($result, array('route' => array('form.update', $result->id), 'method' => 'PATCH', 'id' => 'e-catalog-form', 'class' => '', 'files' => 'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group"> 
                                            {!! Form::label('title', lang('Title'), array('class' => '')) !!}
                                            {!! Form::text('title', null, array('class' => 'form-control', 'required'=> 'true')) !!}
                                        </div>  
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"> 
                                            {!! Form::label('sort', lang('Sort'), array('class' => '')) !!}
                                            {!! Form::number('sort', null, array('class' => 'form-control', 'required'=> 'true')) !!}
                                        </div>  
                                    </div>

                          
                                    <div class="col-md-6 mgn20">  
                                     <div class="form-group"> 
                                            {!! Form::label('file', lang('Upload File'), array('class' => '')) !!}
                                            @if(!empty($result->file))
                                            <input name="file" type='file'/>
                                            <a href="{{ $result->file }}" target="_blank"> <i class="fa fa-file" style="font-size: 65px !important;"></i> </a>
                                            @else
                                            <input name="file" type='file' required="true" />
                                            @endif
                                            @if ($errors->has('file'))
                                            <span class="text-danger">{{$errors->first('file')}}</span>
                                            @endif
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



<style type="text/css">
.file_img {
    margin-top: 20px;
/*    margin-left: 200px; */
}
</style>