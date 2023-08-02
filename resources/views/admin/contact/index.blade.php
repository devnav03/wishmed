@extends('admin.layouts.master')
@section('css')
<!-- tables -->
<style>
form{
overflow: auto;
}
</style>
<link rel="stylesheet" type="text/css" href="{!! asset('css/table-style.css') !!}" />
<!-- //tables -->
@endsection
@section('content')

<div class="agile-grids">   
    <div class="grids">       
        <div class="row">
            <div class="col-md-12">                
                <h1 class="page-header">{!! lang('contact.contact') !!} Enquiries <!-- <a class="btn btn-sm btn-primary pull-right" href="{!! route('export-enquiry') !!}" style="margin-left: 10px;"> <i class="fa fa-download fa-fw"></i> Export Excel </a> --> </h1>

                <div class="agile-tables">
                    <div class="w3l-table-info">
                   

                        {{-- for message rendering --}}
                        @include('admin.layouts.messages')

                        <form action="#" method="post">
                            <div class="col-md-3 text-right pull-right padding0 marginbottom10">
                                {!! lang('Show') !!} {!! Form::select('name', ['20' => '20', '40' => '40', '100' => '100', '200' => '200', '300' => '300'], '20', ['id' => 'per-page']) !!} {!! lang('entries') !!}
                            </div>
                            <div class="col-md-3 padding0 marginbottom10">
                                {!! Form::hidden('page', 'search') !!}
                                {!! Form::hidden('_token', csrf_token()) !!}
                                {!! Form::text('name', null, array('class' => 'form-control live-search', 'placeholder' => 'Search')) !!}
                            </div>
                            <table id="paginate-load" data-route="{{ route('contact-enquiry.paginate') }}" class="table table-hover">
                            </table>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop


