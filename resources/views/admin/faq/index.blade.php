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
                <h1 class="page-header">{!! lang('faq.faq') !!} Listing <a class="btn btn-sm btn-primary pull-right" href="{!! route('faq.create') !!}"> <i class="fa fa-plus fa-fw"></i> {!! lang('common.create_heading', lang('faq.faq')) !!} </a></h1>

                <div class="agile-tables">
                    <div class="w3l-table-info">
                      <h3>FAQs Details</h3>

                        {{-- for message rendering --}}
                        @include('admin.layouts.messages')

                        <form action="{{ route('faq.action') }}" method="post">
                            <div class="col-md-3 text-right pull-right padding0 marginbottom10">
                                {!! lang('Show') !!} {!! Form::select('name', ['20' => '20', '40' => '40', '100' => '100', '200' => '200', '300' => '300'], '20', ['id' => 'per-page']) !!} {!! lang('entries') !!}
                            </div>
                            <div class="col-md-3 padding0 marginbottom10">
                                {!! Form::hidden('page', 'search') !!}
                                {!! Form::hidden('_token', csrf_token()) !!}
                                {!! Form::text('name', null, array('class' => 'form-control live-search', 'placeholder' => 'Search faq by name')) !!}
                            </div>
                            <table id="paginate-load" data-route="{{ route('faq.paginate') }}" class="table table-hover">
                            </table>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop

