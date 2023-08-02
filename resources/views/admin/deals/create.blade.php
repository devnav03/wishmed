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
                <h1 class="page-header">Case Deal <a class="btn btn-sm btn-primary pull-right" href="{!! route('deals.index') !!}"> <i class="fa fa-solid fa-arrow-left"></i> All Case Deals</a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>Case Deal Information </h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'deals.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('deals.store'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                @elseif($route == 'deals.edit')
                                    {!! Form::model($result, array('route' => array('deals.update', $result->id), 'method' => 'PATCH', 'id' => 'deals-form', 'class' => '', 'files'=>'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-6">
                                         <div class="form-group"> 
                                            {!! Form::label('min_qty', lang('Min qty'), array('class' => '')) !!}
                                            {!! Form::number('min_qty', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                        </div> 
                                    </div>   
                                    
                                    <div class="col-md-6">
                                         <div class="form-group"> 
                                            <label for="discount">Discount</label>
                                            {!! Form::number('discount', null, array('class' => 'form-control', 'required' => 'true')) !!}
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

<script type="text/javascript">
    
imgInp.onchange = evt => {
  const [file] = imgInp.files
  if (file) {
    blah.src = URL.createObjectURL(file)
  }
}    

</script>

@stop

