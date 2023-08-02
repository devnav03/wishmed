<thead>
<tr>
    <th width="5%" class="text-center">{!! lang('common.id') !!}</th>
    <th>User</th>
    <th>Username</th>
    <th>Status</th>
    <th>IP Address</th>
    <th>Time</th>
</tr>
</thead>
<tbody>
<?php $index = 1; ?>
@foreach($data as $detail)
<tr id="order_{{ $detail->id }}" class="@if($detail->is_login == 1) su_log @else fail_log @endif">
    <td class="text-center">{!! pageIndex($index++, $page, $perPage) !!}</td>
    <td>@if($detail->is_login == 1) @if(\Auth::user()->id == $detail->user_id) <span style="color: blue;">You </span> @else {!! $detail->name !!} @endif @else Anonymous Users @endif</td>  
    <td>{{ $detail->username }}</td>
    <td>@if($detail->is_login == 1) Success @else Failed @endif</td>  
    <td>{!! $detail->ip !!}</td>
    <td>{{ $detail->created_at->diffForHumans() }}</td>
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