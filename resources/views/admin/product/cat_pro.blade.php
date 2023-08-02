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
                <h1 class="page-header">{!! $category->name !!}
                <a class="btn btn-sm btn-primary pull-right" href="{!! route('product.index') !!}"> <i class="fa fa-plus fa-fw"></i> ALL Products </a></h1>

                <div class="agile-tables">
                    <div class="w3l-table-info">
    

                        {{-- for message rendering --}}
                        @include('admin.layouts.messages')


                
                                                            <table class="table table-hover">
                                <thead>
<tr>
    <th><input type="checkbox" id="checkedAlldddd" name="all"></th>
    <th width="5%" class="text-center">{!! lang('common.id') !!}</th>
    <th>{!! lang('common.name') !!}</th>
    <th>SKU</th>
    <th class="text-center">Category</th>
    <th class="text-center">Offer Price</th>
    <!--<th class="text-center">Regular Price</th>-->
    <!-- <th class="text-center">Quantity</th> -->
    <th width="6%" class="text-center"> {!! lang('common.status') !!} </th>
    <th class="text-center">{!! lang('common.action') !!}</th>
</tr>
</thead>
<tbody>
<?php $index = 0;
$index++; ?>
@foreach($data as $detail)

<tr id="order_{{ $detail->id }}">
     <td><input type="checkbox" value="{{ $detail->id }}" class="delete-checkbox delete" name="delete[]"></td>
    <td class="text-center">{!! $index++  !!}</td>
    <td><a href="{!! route('product.edit', [$detail->id]) !!}">{{ str_limit($detail->name,60)}} </a> </td>
    <td>{!! $detail->sku !!}</td>
    <td class="text-center">{!! $detail->category !!}@if($detail->cat2), {{ $detail->cat2  }},@endif @if($detail->cat3) {{ $detail->cat3  }},@endif @if($detail->cat4) {{ $detail->cat4  }},@endif @if($detail->cat5) {{ $detail->cat5  }} @endif @if($detail->cat6), {{ $detail->cat6  }} @endif</td>
    <td class="text-center">{!! $detail->offer_price !!}</td>
    <!--<td class="text-center">{!! $detail->regular_price !!}</td>-->
     <!--  <td class="text-center">{{ $detail->quantity }}</td> -->
    <td class="text-center">
        <a href="javascript:void(0);" class="toggle-status" data-message="{!! lang('messages.change_status') !!}" data-route="{!! route('product.toggle', $detail->id) !!}" title="@if($detail->status == 0) Deactive @else Active @endif">
            {!! HTML::image('images/' . $detail->status . '.gif') !!}
        </a>
    </td>
    <td class="text-center col-md-1">
        <a class="btn btn-xs btn-primary" href="{{ route('product.edit', [$detail->id]) }}"><i class="fa fa-edit"></i></a>
        <a title="{!! lang('common.delete') !!}" class="btn btn-xs btn-danger __drop" data-route="{!! route('product.drop', [$detail->id]) !!}" data-message="{!! lang('messages.sure_delete', string_manip(lang('product.product'))) !!}" href="javascript:void(0)">
                <i class="fa fa-times"></i>
            </a>
    </td>    
</tr>

@endforeach
<input type="hidden" name="delete_url" id="delete_url" value="{{ route('delete-selected') }}">
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script>

$('#checkedAlldddd').on('click', function(event) {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
    

    $('.delete-btn').on('click', function(event) {
        if($('.delete-checkbox:checked').length>0){
          if(confirm("Are you sure to Delete Selected")){
            data = new Array();
            $('.delete-checkbox:checked').each(function() {
              if (this.checked){
                data.push($(this).val());
              }
            });
            $.each( data, function( index, value ){
                $("#TableID_"+value).remove();
            });
            var urls = $('#delete_url').val();
            $.ajax({
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                url : urls,
                data: {id:data},
                type : 'POST',
                success : function(result){
                //  if(result.status==200){
                  //  $('.delete-btn').text('Delete Selectd');
                    location.reload(true);
                   // toastr.success(result.msg)
                  //}
                }
            });
          }
        }
    });


</script>
@if (count($data) < 1)
<tr>
    <td class="text-center" colspan="9"> {!! lang('messages.no_data_found') !!} </td>
</tr>
@endif
</tbody>




                            </table>
                       
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

