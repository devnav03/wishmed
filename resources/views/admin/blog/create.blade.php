@extends('admin.layouts.master')
@section('content')

{!! Html::script('js/nicEdit-latest.js') !!} 
<script type="text/javascript">
//<![CDATA[
bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
//]]>
</script>
@include('admin.layouts.messages')
@php
    $route  = \Route::currentRouteName();    
@endphp
<div class="agile-grids">   
    <div class="grids">       
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">Blog <a class="btn btn-sm btn-primary pull-right" href="{!! route('blogs.index') !!}"> <i class="fa fa-solid fa-arrow-left"></i> All Blogs</a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>Blog Information</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'blogs.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('blogs.store'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                @elseif($route == 'blogs.edit')
                                    {!! Form::model($result, array('route' => array('blogs.update', $result->id), 'method' => 'PATCH', 'id' => 'blogs-form', 'class' => '', 'files'=>'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-12">
                                         <div class="form-group"> 
                                            {!! Form::label('title', lang('Title'), array('class' => '')) !!}
                                            {!! Form::text('title', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                        </div> 
                                    </div>  

                                    <div class="col-md-12" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            <label for="description" class="">Description</label>
                                            {!! Form::textarea('description', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('description'))
                                             <span class="text-danger">{{$errors->first('description')}}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            {!! Form::label('meta_title', lang('Meta Title'), array('class' => '')) !!}
                                            {!! Form::text('meta_title', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                        </div> 
                                    </div>

                                    <div class="col-md-12" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            {!! Form::label('meta_description', lang('Meta Description'), array('class' => '')) !!}
                                            {!! Form::text('meta_description', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                        </div> 
                                    </div>

                                    <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                        <label for="sub_category">Category</label>
                                        <select name="category_id" class="select2 form-control1">
                                        <option value="">-Select-</option>
                                        @foreach($BlogCategory as $cat_list2)
                                        
                                        @if(isset($result))
                                        <option value="{{ $cat_list2->id }}" @if($result->category_id == $cat_list2->id) selected @endif>{{ $cat_list2->name }}</option>
                                        @else
                                        <option value="{{ $cat_list2->id }}">{{ $cat_list2->name }}</option>
                                        @endif

                                        @endforeach
                     
                                        </select>
                                        </div>
                                    </div>





                                   </div>
                                    
                                
                                <div class="row">
                                    <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            {!! Form::label('image', lang('Image'), array('class' => '')) !!}
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

