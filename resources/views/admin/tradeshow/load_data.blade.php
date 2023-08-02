<thead>
<tr>
    <th width="5%" class="text-center">{!! lang('common.id') !!}</th>
    <th>Show {!! lang('common.name') !!}</th>
    <th>Place</th>
    <th>Booth</th> 
    <th>From</th>
    <th>To</th>
    <th>Total Amount</th>
    <th>Paid Amount</th>
    <th>Balance</th>
    <th width="6%" class="text-center"> {!! lang('common.status') !!} </th>
    <th class="text-center">{!! lang('common.action') !!}</th>
</tr>
</thead>
<tbody>
<?php $index = 1; ?>
@foreach($data as $detail)
<tr id="order_{{ $detail->id }}">
    <td class="text-center">{!! pageIndex($index++, $page, $perPage) !!}</td>
    <td><a href="{!! route('tradeshow.edit', [$detail->id]) !!}"> {!! $detail->name !!}</a></td>
    <td>{!! $detail->place !!}</td>
    <td>{!! $detail->booth !!}</td>
    <td>{!! date('d M, Y', strtotime($detail->from_date)) !!}</td>
    <td>{!! date('d M, Y', strtotime($detail->to_date)) !!}</td>
    <td>{!! $detail->total_payment !!}</td>
    <td>{!! $detail->down_payment_3 + $detail->down_payment_2 + $detail->down_payment_1 !!}</td>
    <td>{!! $detail->total_payment - ($detail->down_payment_3 + $detail->down_payment_2 + $detail->down_payment_1) !!}</td>
    <td class="text-center"> <a href="javascript:void(0);" class="toggle-status" data-message="{!! lang('messages.change_status') !!}" data-route="{!! route('tradeshow.toggle', $detail->id) !!}" title="@if($detail->status == 0) Deactive @else Active @endif"> {!! HTML::image('images/' . $detail->status . '.gif') !!} </a></td>
    <td class="text-center col-md-1">
        <a class="btn btn-xs btn-primary" href="{{ route('tradeshow.edit', [$detail->id]) }}"><i class="fa fa-edit"></i></a>
    </td>    
</tr>
@endforeach
@if (count($data) < 1)
<tr>
    <td class="text-center" colspan="8"> {!! lang('messages.no_data_found') !!} </td>
</tr>
@else
<tr>
    <td colspan="10">
        {!! paginationControls($page, $total, $perPage) !!}
    </td>
</tr>
@endif
</tbody>