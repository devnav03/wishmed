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
              <h1 class="page-header">{!! lang('order.order') !!} Status ({!! $result->order_nr !!})<a class="btn btn-sm btn-primary pull-right" href="{!! route('order.index') !!}"> <i class="fa fa-plus fa-fw"></i> All {!! lang('order.order') !!}</a></h1>
          </div>
            <div class="col-md-6">
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-body">
                             <!--    @if(session()->has('transaction'))
                    <p style="color: #f00; font-size: 16px; margin-bottom: 15px; margin-top: -7px;">Kindly select a delivery boy</p>
                                @endif -->
                                {!! Form::open(array('method' => 'POST', 'route' => array('order-product-status'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" value="{{ $id }}" name="order_id">
                                         <div class="form-group"> 
                                            {!! Form::label('type', lang('Current Status'), array('class' => '')) !!}
                                            <select name="status" class="form-control" required ="true">
                                            <option value="">-Select-</option>   
                                            @foreach($statusType as $statusType) 
                                            <option value="{{ $statusType->id }}">{{ $statusType->type }}</option> 

                                            @endforeach
                                            
                                            </select>
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
            <div class="col-md-6">                
                <div class="agile-tables">
                    <div class="w3l-table-info">

                        {{-- for message rendering --}}
                        @include('admin.layouts.messages')
<table class="table table-hover" style="margin-top: 0px;">  
                       <thead>
<tr>
    <th class="text-center">#</th>
    <th>Date</th>
    <th>Status</th>
</tr>
</thead>
<tbody>
<?php $index = 2; ?>


<tr>
    <td class="text-center">1</td>
    <td>{!! $result->created_at !!}</td>
    <td>Order Received</td>
</tr>
@foreach($orderStatus as $detail)
<tr>
    <td class="text-center">{!! $index !!}</td>
    <td>{!! $detail->date !!}</td>
    <td>{!! $detail->type !!}</td>
</tr>
<?php $index++; ?>
@endforeach

</tbody>
</table>

                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop

