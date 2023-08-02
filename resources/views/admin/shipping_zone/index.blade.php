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
                <h1 class="page-header">Shipping Zones <a class="btn btn-sm btn-primary pull-right" href="{!! route('shipping-zone.create') !!}"> <i class="fa fa-plus fa-fw"></i> Create Shipping Zone </a></h1>

                <div class="agile-tables">
                    <div class="w3l-table-info">
      <!--                 <h3>Shipping Zones Details</h3> -->

                        {{-- for message rendering --}}
                        @include('admin.layouts.messages')

                        <form action="{{ route('shipping-zone.action') }}" method="post">
                            <div class="col-md-3 text-right pull-right padding0 marginbottom10">
                                {!! lang('Show') !!} {!! Form::select('name', ['20' => '20', '40' => '40', '100' => '100', '200' => '200', '300' => '300'], '20', ['id' => 'per-page']) !!} {!! lang('entries') !!}
                            </div>
                            <div class="col-md-3 padding0 marginbottom10">
                                {!! Form::hidden('page', 'search') !!}
                                {!! Form::hidden('_token', csrf_token()) !!}
                                {!! Form::text('name', null, array('class' => 'form-control live-search', 'placeholder' => 'Search shipping zone by name')) !!}
                            </div>
                            <table id="paginate-load" data-route="{{ route('shipping-zone.paginate') }}" class="table table-hover">
                            </table>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop

