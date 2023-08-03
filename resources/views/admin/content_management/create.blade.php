@extends('admin.layouts.master')
@section('css')
<!-- tables -->
<link rel="stylesheet" type="text/css" href="{!! asset('css/table-style.css') !!}" />
<!-- //tables -->

{!! Html::script('js/nicEdit-latest.js') !!} <script type="text/javascript">
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
                <h1 class="page-header">Content Management System</h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                          <!--   <div class="form-title">
                                <h4>{!! lang('product.product_info') !!}</h4>                        
                            </div> -->
                            <div class="form-body">
                                    {!! Form::model($result, array('route' => array('content-management.update', $result->id), 'method' => 'PATCH', 'id' => 'content-management-form', 'class' => '', 'files'=>'true')) !!}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group"> 
                                            <label for="about" style="font-size: 16px; margin-bottom: 5px;">About Us<span>*</span></label>
                                            {!! Form::textarea('about', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('about'))
                                             <span class="text-danger">{{$errors->first('about')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 mgn20">
                                        <div class="form-group"> 
                                            <label for="privacy" style="font-size: 16px; margin-bottom: 5px;">Return & Cancellation Policy<span>*</span></label>
                                            {!! Form::textarea('privacy', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('privacy'))
                                             <span class="text-danger">{{$errors->first('privacy')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 mgn20">
                                        <div class="form-group"> 
                                            <label for="terms_conditions" style="font-size: 16px; margin-bottom: 5px;">Terms & Conditions<span>*</span></label>
                                            {!! Form::textarea('terms_conditions', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('terms_conditions'))
                                             <span class="text-danger">{{$errors->first('terms_conditions')}}</span>
                                            @endif
                                        </div>
                                    </div> 
                                   
                         <!--            <div class="col-md-12 mgn20">
                                        <div class="form-group"> 
                                            <label for="terms_conditions" style="font-size: 16px; margin-bottom: 5px;">Refund & Return<span>*</span></label>
                                            {!! Form::textarea('refund_return', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('refund_return'))
                                             <span class="text-danger">{{$errors->first('refund_return')}}</span>
                                            @endif
                                        </div>
                                    </div>  -->

                                   
                                  <!--   <div class="col-md-12 mgn20">
                                        <div class="form-group"> 
                                            <label for="contact" style="font-size: 16px; margin-bottom: 5px;">Contact Us<span>*</span></label>
                                            {!! Form::textarea('contact', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('contact'))
                                             <span class="text-danger">{{$errors->first('contact')}}</span>
                                            @endif
                                        </div>
                                    </div> -->
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
