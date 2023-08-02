@extends('admin.layouts.master')
@section('css')
<!-- tables -->
<link rel="stylesheet" type="text/css" href="{!! asset('css/table-style.css') !!}" />
<!-- //tables -->
@endsection
@section('content')

<div class="agile-grids">   
    <div class="grids">       
        <div class="row">
            <div class="col-md-12">                
                <h1 class="page-header" style="font-size: 22px;">Export Order Report</h1>

                <div class="agile-tables">
                    <div class="w3l-table-info">

                {!! Form::open(array('method' => 'POST', 'route' => array('order-record'), 'id' => 'ajaxSave', 'class' => 'padding0 marginbottom10', 'files'=>'true')) !!}
   
                            
                    <div class="col-md-2 padding0 marginbottom10">
                        <label class="pull-left" style="font-weight: normal;">From</label><br>
                        <input type="date" required="true" placeholder="From" name="from" class="pull-left">
                    </div>
                    <div class="col-md-2 padding0 marginbottom10">
                        <label class="pull-left" style="font-weight: normal;">To</label><br>
                        <input type="date" required="true" placeholder="To" name="to" class="pull-left">
                    </div>
    
                    <input type="submit" value="Download" name="submit" style="height: 40px;margin-top: 18px;background: #be0027; color: #fff;border: 0px;padding: 0px 25px;">

                    {!! Form::close() !!}
                    </div>
                    
                </div>
            </div>
            <div class="col-md-12">                
                <h1 class="page-header" style="font-size: 22px;margin-top: 40px;">Export Customer Report</h1>

                <div class="agile-tables">
                    <div class="w3l-table-info">

                {!! Form::open(array('method' => 'POST', 'route' => array('customer-record'), 'id' => 'ajaxSave', 'class' => 'padding0 marginbottom10', 'files'=>'true')) !!}
                            
                    <div class="col-md-2 padding0 marginbottom10">
                        <label class="pull-left" style="font-weight: normal;">From</label><br>
                        <input type="date" required="true" placeholder="From" name="from" class="pull-left">
                    </div>
                    <div class="col-md-2 padding0 marginbottom10">
                        <label class="pull-left" style="font-weight: normal;">To</label><br>
                        <input type="date" required="true" placeholder="To" name="to" class="pull-left">
                    </div>
    
                    <input type="submit" value="Download" name="submit" style="height: 40px;margin-top: 18px;background: #be0027; color: #fff;border: 0px;padding: 0px 25px;">

                    {!! Form::close() !!}
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop

