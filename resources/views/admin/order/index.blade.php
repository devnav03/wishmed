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

                <h1 class="page-header noPrint">{!! lang('order.order') !!} Listing <!--<a class="btn btn-sm btn-primary pull-right" href="{{ route('upload-order') }}" style="margin-left: 10px;">Upload Orders</a> --><!-- <a class="btn btn-sm btn-primary pull-right" href="{!! route('export-order') !!}" style="margin-left: 10px;"> <i class="fa fa-plus fa-fw"></i> Export Order </a> --> </h1>

                <div class="agile-tables">
                    <div class="w3l-table-info">

                        {{-- for message rendering --}}
                        @include('admin.layouts.messages')
                         @if(session()->has('transaction'))
                    <p style="color: #f00; font-size: 16px; margin-bottom: 15px; margin-top: -7px;">Kindly update Transaction ID</p>
                                @endif 

                         <div class="panel panel-default noPrint">
                            <div class="panel-heading">
                                Order Filter
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    {!! Form::open(array('method' => 'POST',
                                    'route' => array('order.paginate'), 'id' => 'ajaxForm')) !!}
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="name" class="control-label">Order No.</label>
                                                {!! Form::text('order_nr', null, array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                        @if(isset($Customer))
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="user_id" class="control-label">Customer</label>
                                                {!! Form::select('user_id',$Customer,!empty($result->user_id)?$result->user_id:'', array('class' => 'select2 form-control1')) !!}
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="current_status" class="control-label">Order Status</label>
                                                {!! Form::select('current_status',$OrderStatus,!empty($result->current_status)?$result->current_status:'', array('class' => 'select2 form-control1')) !!}
                                            </div>
                                        </div>  
                                        
                                        <div class="col-sm-3 margintop20">
                                            <div class="form-group">
                                                {!! Form::hidden('form-search', 1) !!}
                                                {!! Form::submit(lang('common.filter'), array('class' => 'btn btn-primary')) !!}
                                                <a href="{!! route('order.index') !!}" class="btn btn-success"> {!! lang('common.reset_filter') !!}</a>
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                      
                        <form action="{{ route('order.action') }}" method="post">
                            <div class="col-md-3 text-right pull-right padding0 marginbottom10">
                                {!! lang('Show') !!} {!! Form::select('name', ['20' => '20', '40' => '40', '100' => '100', '200' => '200', '300' => '300'], '20', ['id' => 'per-page']) !!} {!! lang('entries') !!}
                            </div>
                            <div class="col-md-3 padding0 marginbottom10">
                                {!! Form::hidden('page', 'search') !!}
                                {!! Form::hidden('_token', csrf_token()) !!}
                               <!--  {!! Form::text('name', null, array('class' => 'form-control live-search', 'placeholder' => 'Search order by order number')) !!} -->
                            </div>
                            <table id="paginate-load" data-route="{{ route('order.paginate') }}" class="table table-hover">
                            </table>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop

