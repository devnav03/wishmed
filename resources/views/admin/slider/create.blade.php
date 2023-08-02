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
                <h1 class="page-header">{!! lang('slider.slider') !!} <a class="btn btn-sm btn-primary pull-right" href="{!! route('slider.index') !!}"> <i class="fa fa-solid fa-arrow-left"></i> All {!! lang('slider.sliders') !!}</a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>{!! lang('slider.slider_info') !!}</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'slider.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('slider.store'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                @elseif($route == 'slider.edit')
                                    {!! Form::model($result, array('route' => array('slider.update', $result->id), 'method' => 'PATCH', 'id' => 'slider-form', 'class' => '', 'files'=>'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-6">
                                         <div class="form-group"> 
                                            {!! Form::label('title', lang('Title'), array('class' => '')) !!}
                                            {!! Form::text('title', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                        </div> 
                                    </div>  
                                

                                    <div class="col-md-6">
                                        <div class="form-group"> 
                                        <label for="link" class="">Link</label>
                                        {!! Form::url('link', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                        <label for="order" class="">Order</label>
                                        {!! Form::number('order', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                        <label>Show In Web</label>
                                            <select name="show_in_web" class="select2 form-control1" required="true">
                                                <option value="">-Select-</option>
                                                @if(isset($result))
                                                <option @if($result->show_in_web=="1") selected @endif value="1">Yes</option>
                                                <option @if($result->show_in_web=="0") selected @endif} value="0">No</option>
                                                @else
                                                <option {{ old('show_in_web') == '1' ? 'selected' : '' }} value="1">Yes</option>
                                                <option {{ old('show_in_web') == '0' ? 'selected' : '' }} value="0">No</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                        <label>Show In APP</label>
                                            <select name="show_in_app" class="select2 form-control1" required="true">
                                                <option value="">-Select-</option>
                                                @if(isset($result))
                                                <option @if($result->show_in_app=="1") selected @endif value="1">Yes</option>
                                                <option @if($result->show_in_app=="0") selected @endif} value="0">No</option>
                                                @else
                                                <option {{ old('show_in_app') == '1' ? 'selected' : '' }} value="1">Yes</option>
                                                <option {{ old('show_in_app') == '0' ? 'selected' : '' }} value="0">No</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                   </div>
                                    
                                
                                <div class="row">

                                    <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            {!! Form::label('image', lang('Slider Image (size 1200X400)'), array('class' => '')) !!}
                                            @if(!empty($result->image))
                                        <!--     {!! Form::file('image', array()) !!} -->
                                            <input name="image" type='file' accept="image/png, image/jpeg" id="imgInp" />
                                            @else
                                          <!--   {!! Form::file('image', array('required' => 'true')) !!} -->
                                            <input name="image" type='file' accept="image/png, image/jpeg" required="true" id="imgInp" />
                                            @endif
                                            <img id="blah" style="max-width: 55%;" src="#" alt="" />
                                        </div>
                                        @if(!empty($result->image))
                                            <div class="form-group"> 
                                                 {!! Html::image(asset($result->image),'' ,array('width' => 150,'class'=>'img-responsive') ) !!}
                                            </div>
                                        @endif
                                    </div>  

                                    <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            {!! Form::label('app_slider', lang('APP Slider Image (size 1050X500)'), array('class' => '')) !!}
                                            @if(!empty($result->app_slider))
                                            <!-- {!! Form::file('app_slider', array()) !!} -->
                                            <input name="app_slider" type='file' accept="image/png, image/jpeg" id="imgInp1" />
                                            @else
                                            <!-- {!! Form::file('app_slider', array('required' => 'true')) !!} -->
                                            <input name="app_slider" type='file' accept="image/png, image/jpeg" required="true" id="imgInp1" />
                                            @endif
                                            <img id="blah1" style="max-width: 55%;" src="#" alt="" />
                                        </div>
                                        @if(!empty($result->app_slider))
                                            <div class="form-group"> 
                                                 {!! HTML::image(asset($result->app_slider),'' ,array('width' => 100 ,'class'=>'img-responsive') ) !!}
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

<script>
function getSubcategory(val) {
  $.ajax({
    type: "GET",
    url: "{{ route('getPage') }}",
    data: {'page_name' : val},
    success: function(data){
        $("#category-list").html(data);
    }
  });
}

imgInp.onchange = evt => {
  const [file] = imgInp.files
  if (file) {
    blah.src = URL.createObjectURL(file)
  }
}

imgInp1.onchange = evt => {
  const [file] = imgInp1.files
  if (file) {
    blah1.src = URL.createObjectURL(file)
  }
}

</script>

@stop

