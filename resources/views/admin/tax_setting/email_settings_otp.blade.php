@extends('admin.layouts.master')
@section('content')
@include('admin.layouts.messages')
@php
    $route  = \Route::currentRouteName();    
@endphp
<div class="agile-grids">   
    <div class="grids">       
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h1 class="page-header">Email Settings OTP</h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                        @if(session()->has('not_match'))
                            <li class="alert alert-danger" style="list-style: none; margin-top: 25px; max-width: 80%; margin-left: 10%; background: #e77755; color: #fff;">OTP not valid</li>
                        @endif
                            
                            <div class="form-body">
                                {!! Form::open(array('method' => 'POST', 'route' => array('email-settings.otp'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group"> 
                                            <label for="new_order_email" style="font-size: 16px; margin-bottom: 5px;">Enter OTP<span>*</span></label>
                                            {!! Form::number('otp', null, array('class' => 'form-control', 'required' => 'true' )) !!}
                                            @if ($errors->has('otp'))
                                             <span class="text-danger">{{$errors->first('otp')}}</span>
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
