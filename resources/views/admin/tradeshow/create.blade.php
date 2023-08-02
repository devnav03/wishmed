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
                <h1 class="page-header">Tradeshow <a class="btn btn-sm btn-primary pull-right" href="{!! route('tradeshow.index') !!}"> <i class="fa fa-solid fa-arrow-left"></i> All Tradeshow</a></h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>Tradeshow Information</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'tradeshow.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('tradeshow.store'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                @elseif($route == 'tradeshow.edit')
                                    {!! Form::model($result, array('route' => array('tradeshow.update', $result->id), 'method' => 'PATCH', 'id' => 'case-deal-form', 'class' => '', 'files'=>'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <label class="with-help" style="width: 15%;">Region </label>
                                        <input id="all" type="radio" name="region" @if(isset($result)) @if($result->region == 2) checked @endif @else checked="" @endif value="2">
                                        <label for="all" style="display: inline-block;"><span></span>All</label>

                                        <input id="organization" type="radio" name="region" @if(isset($result)) @if($result->region == 0) checked @endif @endif value="0">
                                        <label for="organization" style="display: inline-block;"><span></span>US</label>

                                        <input id="individual" type="radio" name="region" @if(isset($result)) @if($result->region == 1) checked @endif @endif value="1">
                                        <label for="individual" style="display: inline-block;"><span></span>Asia</label>
                                        <div class="help-block with-errors"></div>
                                        </div> 
                                    </div>  


                                    <div class="col-md-4 mgn20">
                                        <div class="form-group"> 
                                            <label for="product_name">Show Name</label>
                                            {!! Form::text('name', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                            @if($errors->has('name'))
                                             <span class="text-danger">{{$errors->first('name')}}</span>
                                            @endif
                                            
                                        </div> 
                                    </div>   
                              
                                    <div class="col-md-4 mgn20">
                                        <div class="form-group"> 
                                        {!! Form::label('place', lang('Place'), array('class' => '')) !!}
                                        {!! Form::text('place', null, array('class' => 'form-control')) !!}
                                        @if($errors->has('place'))
                                            <span class="text-danger">{{$errors->first('place')}}</span>
                                        @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-4 mgn20">
                                        <div class="form-group"> 
                                        {!! Form::label('total_payment', lang('Total Amount'), array('class' => '')) !!}
                                        {!! Form::text('total_payment', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                        @if($errors->has('total_payment'))
                                            <span class="text-danger">{{$errors->first('total_payment')}}</span>
                                        @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('from_date', lang('From Date'), array('class' => '')) !!}
                                            {!! Form::date('from_date', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                            @if($errors->has('from_date'))
                                             <span class="text-danger">{{$errors->first('from_date')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('to_date', lang('To Date'), array('class' => '')) !!}
                                            {!! Form::date('to_date', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                            @if($errors->has('to_date'))
                                             <span class="text-danger">{{$errors->first('to_date')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('booth', lang('Booth'), array('class' => '')) !!}
                                            {!! Form::text('booth', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('booth'))
                                             <span class="text-danger">{{$errors->first('booth')}}</span>
                                            @endif
                                        </div> 
                                    </div>

                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('down_payment_1', lang('Down Payment I'), array('class' => '')) !!}
                                            {!! Form::text('down_payment_1', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('down_payment_1'))
                                             <span class="text-danger">{{$errors->first('down_payment_1')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('down_payment_date_1', lang('Down Payment I Date'), array('class' => '')) !!}
                                            {!! Form::date('down_payment_date_1', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('down_payment_date_1'))
                                             <span class="text-danger">{{$errors->first('down_payment_date_1')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('down_payment_1_remark', lang('Down Payment I Remark'), array('class' => '')) !!}
                                            {!! Form::text('down_payment_1_remark', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('down_payment_1_remark'))
                                             <span class="text-danger">{{$errors->first('down_payment_1_remark')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    
                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('down_payment_2', lang('Down Payment II'), array('class' => '')) !!}
                                            {!! Form::text('down_payment_2', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('down_payment_2'))
                                             <span class="text-danger">{{$errors->first('down_payment_2')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('down_payment_date_2', lang('Down Payment II Date'), array('class' => '')) !!}
                                            {!! Form::date('down_payment_date_2', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('down_payment_date_2'))
                                             <span class="text-danger">{{$errors->first('down_payment_date_2')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('down_payment_2_remark', lang('Down Payment II Remark'), array('class' => '')) !!}
                                            {!! Form::text('down_payment_2_remark', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('down_payment_2_remark'))
                                             <span class="text-danger">{{$errors->first('down_payment_2_remark')}}</span>
                                            @endif
                                        </div> 
                                    </div>

                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('down_payment_3', lang('Down Payment III'), array('class' => '')) !!}
                                            {!! Form::text('down_payment_3', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('down_payment_3'))
                                             <span class="text-danger">{{$errors->first('down_payment_3')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('down_payment_date_3', lang('Down Payment III Date'), array('class' => '')) !!}
                                            {!! Form::date('down_payment_date_3', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('down_payment_date_3'))
                                             <span class="text-danger">{{$errors->first('down_payment_date_3')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('down_payment_3_remark', lang('Down Payment III Remark'), array('class' => '')) !!}
                                            {!! Form::text('down_payment_3_remark', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('down_payment_3_remark'))
                                             <span class="text-danger">{{$errors->first('down_payment_3_remark')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    
                                    @if(isset($result))
                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('balance', lang('Paid Amount'), array('class' => '')) !!}
                                            <input type="text" value="{{ $result->down_payment_3 + $result->down_payment_2 + $result->down_payment_1 }}" class="form-control" readonly>
                                          <!--  {!! Form::number('balance', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('balance'))
                                             <span class="text-danger">{{$errors->first('balance')}}</span>
                                            @endif -->
                                        </div> 
                                    </div>
                                   
                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('balance', lang('Balance'), array('class' => '')) !!}
                                            <input type="text" value="{{ $result->total_payment - ($result->down_payment_3 + $result->down_payment_2 + $result->down_payment_1) }}" 
                                            class="form-control" readonly>
                                           <!-- {!! Form::number('balance', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('balance'))
                                             <span class="text-danger">{{$errors->first('balance')}}</span>
                                            @endif -->
                                        </div> 
                                    </div>
                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('balance_remark', lang('Balance Remark'), array('class' => '')) !!}
                                            {!! Form::text('balance_remark', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('balance_remark'))
                                             <span class="text-danger">{{$errors->first('balance_remark')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                     @endif
                                   <!-- <div class="col-md-6 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('total_cost', lang('Total Cost'), array('class' => '')) !!}
                                            {!! Form::text('total_cost', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('total_cost'))
                                             <span class="text-danger">{{$errors->first('total_cost')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-6 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('total_cost_remark', lang('Total Cost Remark'), array('class' => '')) !!}
                                            {!! Form::text('total_cost_remark', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('total_cost_remark'))
                                             <span class="text-danger">{{$errors->first('total_cost_remark')}}</span>
                                            @endif
                                        </div> 
                                    </div> -->

                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('hotel_booking', lang('Hotel Booking'), array('class' => 'ful_w')) !!}
                                            <input type="checkbox" name="hotel_booking" value="1" @if(isset($result)) @if($result->hotel_booking == 1) checked @endif @endif> 
                                            @if($errors->has('hotel_booking'))
                                             <span class="text-danger">{{$errors->first('hotel_booking')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-8 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('hotel_booking_remark', lang('Hotel Booking Remark'), array('class' => '')) !!}
                                            {!! Form::text('hotel_booking_remark', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('hotel_booking_remark'))
                                             <span class="text-danger">{{$errors->first('hotel_booking_remark')}}</span>
                                            @endif
                                        </div> 
                                    </div>

                                    <div class="col-md-4 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('logistics', lang('Logistics'), array('class' => 'ful_w')) !!}
                                            <input type="checkbox" @if(isset($result)) @if($result->logistics == 1) checked @endif @endif name="logistics" value="1"> 
                                            @if($errors->has('logistics'))
                                             <span class="text-danger">{{$errors->first('logistics')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-8 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('logistics_remark', lang('Logistics Remark'), array('class' => '')) !!}
                                            {!! Form::text('logistics_remark', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('logistics_remark'))
                                             <span class="text-danger">{{$errors->first('logistics_remark')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-12 mgn20">
                                         <div class="form-group"> 
                                            {!! Form::label('comment', lang('Comment'), array('class' => '')) !!}
                                            {!! Form::textarea('comment', null, array('class' => 'form-control')) !!}
                                            @if($errors->has('comment'))
                                             <span class="text-danger">{{$errors->first('comment')}}</span>
                                            @endif
                                        </div> 
                                    </div>


                                    </div>
                                
                                <div class="row">
                                    <p>&nbsp;</p>
                                    <div class="col-md-7">
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

<style type="text/css">

#live_product_1 input{
    position: absolute;
    width: 100%;
    opacity: 0;
    height: 20px;
    z-index: 4;
} 
#live_product_1 li{
    cursor: pointer;
    position: relative;
    padding: 4px 10px;
}

label span{
  color: #f00;
} 
input[type="radio"] {
    display: none;
}
.ful_w{
    width: 100%;
}
input[type="checkbox"]{
    width: 30px;
    height: 30px;
}
</style>

