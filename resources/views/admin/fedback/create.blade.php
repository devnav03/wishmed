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
                <h1 class="page-header">Customer's Feedback <a class="btn btn-sm btn-primary pull-right" href="{!! route('fedbacks.index') !!}"> <i class="fa fa-solid fa-arrow-left"></i> All Customer's Feedback</a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>Customer's Feedback Information</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'fedbacks.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('fedbacks.store'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                @elseif($route == 'fedbacks.edit')
                                    {!! Form::model($result, array('route' => array('fedbacks.update', $result->id), 'method' => 'PATCH', 'id' => 'fedbacks-form', 'class' => '', 'files'=>'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group"> 
                                        {!! Form::label('name', lang('Name'), array('class' => '')) !!}
                                        {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                        </div> 
                                    </div>  
                                

                                    <div class="col-md-6">
                                        <div class="form-group"> 
                                        <label for="link" class="">Designation</label>
                                        {!! Form::text('designation', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                        <label for="comment" class="">Comment</label>
                                        {!! Form::textarea('comment', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                        </div>
                                    </div>
                                   </div>
                                    
                                
                                <div class="row">
                                    <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            {!! Form::label('image', lang('Customer Image'), array('class' => '')) !!}
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

