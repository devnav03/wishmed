@extends('admin.layouts.master')
@section('css')
{!! HTML::script('js/nicEdit-latest.js') !!}
<script type="text/javascript">
//<![CDATA[
        bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
  //]]>
</script>

@stop

@section('content')
@include('admin.layouts.messages')
@php
    $route  = \Route::currentRouteName();    
@endphp
<div class="agile-grids">   
    <div class="grids">       
        <div class="row">
            <div class="col-md-10">
                <h1 class="page-header">{!! lang('contact.contact') !!} <a class="btn btn-sm btn-primary pull-right" href="{!! route('contact.index') !!}"> <i class="fa fa-plus fa-fw"></i> All {!! lang('blog.blog') !!} </a><a style="margin-right: 10px;" href="{{ url()->previous() }}" class="btn btn-sm btn-success pull-right">Back</a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>{!! lang('blog.blog') !!} Information</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'blog.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('blog.store'), 'id' => 'blog-form', 'class' => '', 'files' => 'true')) !!}

                                @elseif($route == 'blog.edit')
                                    {!! Form::model($result, array('route' => array('blog.update', $result->id), 'method' => 'PATCH', 'id' => 'blog-form', 'class' => '', 'files' => 'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-12">
                                       <div class="form-group">
                                            {!! Form::label('name', lang('common.name'), array('class' => '')) !!}
                                            <sup class="req_field"><i class="fa fa-star" aria-hidden="true"></i></sup>
                                             {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                            
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('slug', lang('Slug'), array('class' => '')) !!}
                                             {!! Form::text('slug', null, array('class' => 'form-control', 'readonly')) !!}                                            
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('meta_title', lang('Meta Title'), array('class' => '')) !!}
                                             {!! Form::text('meta_title', null, array('class' => 'form-control')) !!}
                                            
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('meta_tag', lang('Meta Tag'), array('class' => '')) !!}
                                             {!! Form::text('meta_tag', null, array('class' => 'form-control')) !!}
                                            
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('meta_description', lang('Meta Description'), array('class' => '')) !!}
                                             {!! Form::text('meta_description', null, array('class' => 'form-control')) !!}
                                            
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('description', lang('Description'), array('class' => '')) !!}
                                            <sup class="req_field"><i class="fa fa-star" aria-hidden="true"></i></sup>
                                             {!! Form::textarea('description', null, array('class' => 'form-control', 'rows' => '20')) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('file', lang('Image'), array('class' => '')) !!}
                                            <sup class="req_field"><i class="fa fa-star" aria-hidden="true"></i></sup>
                                            {!! Form::file('image', null, array('class' => 'form-control', 'required' => 'true')) !!}

                                        </div>  
                                        @if(!empty($result->image))
                                            <div class="form-group"> 
                                                {!! HTML::image(asset('uploads/blog_images/'.$result->image),'' ,array('width' => 70 , 'height' => 70,'class'=>'img-responsive') ) !!}
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

@section('script')


@stop