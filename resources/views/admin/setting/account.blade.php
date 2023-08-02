@extends('admin.layouts.master')
@section('content')
@include('admin.layouts.messages')
<div class="agile-grids">   
    <div class="grids">       
        <div class="row">
            
            <div class="col-md-6">
                <h1 class="page-header">{!! lang('common.change_password') !!}</h1>
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>{!! lang('common.password_info') !!}</h4>
                            </div>
                            <div class="form-body">
                                    {!! Form::open(array('method' => 'POST', 'route' => array('setting.manage-account'), 'id' => 'ajaxSave', 'class' => '')) !!}                               
                                <div class="row">
                                    <div class="col-md-12">
                                         <div class="form-group"> 
                                            <label><strong>Username:</strong></label>
                                            {!! \Auth::user()->name !!}
                                        </div> 
                                        <div class="form-group"> 
                                            {!! Form::label('old_password', lang('setting.old_password'), array('class' => '')) !!}
                                            {!! Form::password('password', array('class' => 'form-control' )) !!}
                                        </div> 

                                        <div class="form-group"> 
                                            {!! Form::label('new_password', lang('setting.new_password'), array('class' => '')) !!}

                                            {!! Form::password('new_password', array('class' => 'form-control')) !!}
                                           <span style="font-size: 12px;">Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters</span>

                                        </div>
                                        <div class="form-group"> 
                                            {!! Form::label('confirm_password', lang('setting.confirm_password'), array('class' => '')) !!}
                                            {!! Form::password('confirm_password', array('class' => 'form-control')) !!}
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

