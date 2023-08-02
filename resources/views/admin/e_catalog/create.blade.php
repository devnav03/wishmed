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
                <h1 class="page-header">e.Catalog <a class="btn btn-sm btn-primary pull-right" href="{!! route('e-catalog.index') !!}"> <i class="fa fa-arrow-left"></i> All e.Catalogs </a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>e.Catalog</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'e-catalog.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('e-catalog.store'), 'id' => 'ajaxSave', 'class' => '', 'files' => 'true')) !!}
                                @elseif($route == 'e-catalog.edit')
                                    {!! Form::model($result, array('route' => array('e-catalog.update', $result->id), 'method' => 'PATCH', 'id' => 'e-catalog-form', 'class' => '', 'files' => 'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group"> 
                                            {!! Form::label('title', lang('Title'), array('class' => '')) !!}
                                            {!! Form::text('title', null, array('class' => 'form-control', 'required'=> 'true')) !!}
                                        </div>  
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group"> 
                                        <label for="category" class="">Category</label>
                                        <select name="category" class="select2 form-control1" required="true">
                                        <option value="">-Select-</option>
                                        <option {{ old('category') == '1' ? 'selected' : '' }} value="1" @if(isset($result)) @if($result->category == 1) selected @endif @endif>Catalogs {{ date('Y') }}</option>
                                        <option {{ old('category') == '0' ? 'selected' : '' }} value="0" @if(isset($result)) @if($result->category == 0) selected @endif @endif>Speciality Catalogs</option>
                                        </select>
                                        @if($errors->has('category'))
                                            <span class="text-danger">{{$errors->first('category')}}</span>
                                        @endif
                                        </div>
                                    </div>
                           
                                    <div class="col-md-12 mgn20">  
                                     <div class="form-group"> 
                                            {!! Form::label('background_image', lang('Background Image'), array('class' => '')) !!}
                                            @if(!empty($result->background_image))
                                            {!! Form::file('background_image', array()) !!}
                                           <a href="{{ route('home') }}{!! $result->catalog_file !!}" target="_blank"> {!! HTML::image(($result->background_image),'' ,array('width' => 150 , 'class'=>'img-responsive file_img') ) !!}</a>
                                            @else
                                            {!! Form::file('background_image', array('required' => 'true')) !!}
                                            @endif
                                            @if ($errors->has('background_image'))
                                            <span class="text-danger">{{$errors->first('background_image')}}</span>
                                            @endif
                                        </div>    
                                    </div>  
                                    <div class="col-md-5 mgn20">  
                                     <div class="form-group"> 
                                            {!! Form::label('catalog_file', lang('Upload Catalog File'), array('class' => '')) !!}
                                            @if(!empty($result->catalog_file))
                                            {!! Form::file('catalog_file', array()) !!}
                                            @else
                                            {!! Form::file('catalog_file', array()) !!}
                                            @endif
                                            @if ($errors->has('catalog_file'))
                                            <span class="text-danger">{{$errors->first('catalog_file')}}</span>
                                            @endif
                                        </div>    
                                    </div>  
                                    <div class="col-md-1 mgn20"> 
                                        OR
                                    </div>
                                    <div class="col-md-6 mgn20">  
                                        {!! Form::label('url', lang('URL'), array('class' => '')) !!}
                                        {!! Form::url('url', null, array('class' => 'form-control')) !!}
                                        @if ($errors->has('url'))
                                        <span class="text-danger">{{$errors->first('url')}}</span>
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



<style type="text/css">
.file_img {
    margin-top: 20px;
/*    margin-left: 200px; */
}
</style>