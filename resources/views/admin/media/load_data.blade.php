<thead>
<tr>
    <th width="5%" class="text-center">{!! lang('common.id') !!}</th>
    <th>User</th>
    <th>Image</th>
    <th>Product</th>
    <th>Review</th>
    <th>Rating</th>
    <th width="6%" class="text-center"> {!! lang('common.status') !!} </th>
</tr>
</thead>
<tbody>
<?php $index = 1; ?>
@foreach($data as $detail)
<tr id="order_{{ $detail->id }}">
    <td class="text-center">{!! pageIndex($index++, $page, $perPage) !!}</td>
    <td> {!! $detail->user !!}</td>
    <td>{!! HTML::image(('/uploads/review_images/'.$detail->image),'' ,array('width' => 150 , 'max-height' => 100,'class'=>'img-responsive') ) !!}</td>
    <td> {!! $detail->product !!}</td>
    <td> {!! $detail->review !!}</td>
    <td> {!! $detail->rating !!}</td>
    <td class="text-center">
        <a href="javascript:void(0);" class="toggle-status" data-message="{!! lang('messages.change_status') !!}" data-route="{!! route('reviews.toggle', $detail->id) !!}">
            {!! HTML::image('images/' . $detail->status . '.gif') !!}
        </a>
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