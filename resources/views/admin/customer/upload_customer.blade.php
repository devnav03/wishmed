`@extends('admin.layouts.master')
@section('css')
<!-- tables -->
<link rel="stylesheet" type="text/css" href="{!! asset('css/table-style.css') !!}" />
<!-- //tables -->

{!! HTML::script('js/nicEdit-latest.js') !!} <script type="text/javascript">
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
            <div class="col-md-12">
                <h1 class="page-header">Customer <a class="btn btn-sm btn-primary pull-right" href="{!! route('customer') !!}"> <i class="fa fa-plus fa-fw"></i> All Customer</a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>Customer Information</h4>                        
                            </div>
                            <div class="form-body">
                                <div class="row">
                                 <div class="col-md-6" style="margin-top: 30px;">
                                {!! Form::open(array('method' => 'POST', 'route' => array('customer.import'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                            <div class="row">
                                <div class="col-md-5">
                                     <div class="form-group"> 
                                        {!! Form::label('file', lang('Excel File'), array('class' => '')) !!}
                                       {!! Form::file('file', array('class' => 'form-control', 'required' => 'true')) !!}
                                    </div> 
                                </div> 
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-default w3ls-button" style="width: 100px;">Upload</button> 
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
    </div>
</div>




@stop

<style type="text/css">
#add_flavour {
    background: #62CBE1;
    color: rgb(255, 255, 255);
    float: left;
    margin-top: 18px;
    padding: 9px 16px;
    font-size: 16px;
    cursor: pointer;
}  
</style>