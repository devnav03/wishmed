<thead>
<tr>
    <th width="5%" class="text-center">{!! lang('common.id') !!}</th>
    <th>Image</th>
    <th>Title</th>
    <th width="6%" class="text-center"> {!! lang('common.status') !!} </th>
    <th class="text-center">{!! lang('common.action') !!}</th>
</tr>
</thead>
<tbody>
<?php $index = 1; ?>
@foreach($data as $detail)
<tr id="order_{{ $detail->id }}">
    <td class="text-center" style="vertical-align: middle;">{!! pageIndex($index++, $page, $perPage) !!}</td>
    <td>{!! Html::image(($detail->image),'' ,array('width' => 200 , 'class'=>'img-responsive') ) !!}</td>
    <td style="vertical-align: middle;"><a href="{!! route('slider.edit', [$detail->id]) !!}">{!! $detail->title !!}</a></td>
    <td class="text-center" style="vertical-align: middle;">
         <a href="{{ route('sliderToggle', $detail->id) }}" title="@if($detail->status == 0) Deactive @else Active @endif">            
            {!! HTML::image('images/' . $detail->status . '.gif') !!}
        </a>
    </td>
    <td class="text-center col-md-1" style="vertical-align: middle;">
        <a class="btn btn-xs btn-primary" href="{{ route('slider.edit', [$detail->id]) }}"><i class="fa fa-edit"></i></a>
       <!--  <a title="{!! lang('common.delete') !!}" class="btn btn-xs btn-danger __drop" data-route="{!! route('slider.drop', [$detail->id]) !!}" data-message="{!! lang('messages.sure_delete', string_manip(lang('slider.slider'))) !!}" href="javascript:void(0)">
                <i class="fa fa-times"></i>
            </a> -->
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