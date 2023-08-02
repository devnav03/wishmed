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
                <h1 class="page-header">{!! lang('product.product') !!} Listing 
                <a class="btn btn-sm btn-primary pull-right" href="{{ route('upload-product') }}" style="margin-left: 10px;"><i class="fa fa-upload fa-fw"></i> Upload Products</a> <a class="btn btn-sm btn-primary pull-right" href="{{ route('featured-images') }}" style="margin-left: 10px;"><i class="fa fa-upload fa-fw"></i> Upload Images</a>
                <!-- <a class="btn btn-sm btn-primary pull-right" href="{!! route('export-products') !!}" style="margin-left: 10px;"> <i class="fa fa-download fa-fw"></i> Export Products </a> -->  <a class="btn btn-sm btn-primary pull-right" href="{!! route('product.create') !!}"> <i class="fa fa-plus fa-fw"></i> {!! lang('common.create_heading', lang('product.product')) !!} </a></h1>

                <div class="agile-tables">
                    <div class="w3l-table-info">
                        
                        @if(session()->has('upload_zip'))
                        <li class="alert alert-success" style="list-style: none; margin-top: 25px;">Image successfully uploaded </li>
                        @endif 

                        {{-- for message rendering --}}
                        @include('admin.layouts.messages')

                        

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Product Filter
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    {!! Form::open(array('method' => 'POST',
                                    'route' => array('product.paginate'), 'id' => 'ajaxForm')) !!}
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="name" class="control-label">Name</label>
                                                {!! Form::text('name', null, array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="sku" class="control-label">Sku</label>
                                                {!! Form::text('sku', null, array('class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="category_id" class="control-label">Category</label>
                                                {!! Form::select('category_id',$Categorys,!empty($result->category_id)?$result->category_id:'', array('class' => 'select2 form-control1')) !!}
                                            </div>
                                        </div>  

                                  
                                        
                                        <div class="col-sm-3 margintop20">
                                            <div class="form-group">
                                                {!! Form::hidden('form-search', 1) !!}
                                                {!! Form::submit(lang('common.filter'), array('class' => 'btn btn-primary')) !!}
                                                <a href="{!! route('product.index') !!}" class="btn btn-success"> {!! lang('common.reset_filter') !!}</a>
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('product.action') }}" method="post">
                            <div class="col-md-3 text-right pull-right padding0 marginbottom10">
                                {!! lang('Show') !!} {!! Form::select('name', ['50' => '50', '100' => '100', '200' => '200', '300' => '300'], '50', ['id' => 'per-page']) !!} {!! lang('entries') !!}
                            </div>
                            <div class="col-md-3 padding0 marginbottom10">
                                {!! Form::hidden('page', 'search') !!}
                                {!! Form::hidden('_token', csrf_token()) !!}
                               <!--  {!! Form::text('name', null, array('class' => 'form-control live-search', 'placeholder' => 'Search product by name')) !!} -->
                            </div>
                            <table id="paginate-load" data-route="{{ route('product.paginate') }}" class="table table-hover">
                            </table>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.delete-btn {
    position: absolute;
    top: -39px;
    background: #be0027;
    color: #fff;
    padding: 7px 20px 5px 20px;
    left: 0px;
    font-weight: 400;
    font-size: 14px;
    cursor: pointer;
}    

#paginate-load {
    position: relative;
}    
</style>

@stop

