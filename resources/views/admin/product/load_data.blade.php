<thead>
<tr>
    <th><input type="checkbox" id="checkedAlldddd" name="all"></th>
    <!-- <th width="5%" class="text-center">{!! lang('common.id') !!}</th> -->
    <th onclick="sortTable(1)">{!! lang('common.name') !!} <i class="fa fa-arrow-up"></i> </th>
    <th onclick="sortTable(2)">SKU <i class="fa fa-arrow-up"></i> </th>
    <th onclick="sortTable(3)" class="text-center">Category <i class="fa fa-arrow-up"></i> </th>
    <th onclick="sortTable(4)" class="text-center">Product Type</th>
    <th onclick="sortTable(7)" class="text-center">Regular Price <i class="fa fa-arrow-up"></i> </th>
    <th onclick="sortTable(8)" class="text-center">Offer Price <i class="fa fa-arrow-up"></i> </th>
    <th onclick="sortTable(9)" width="8%" class="text-center"> {!! lang('common.status') !!} <i class="fa fa-arrow-up"></i> </th>
    <th class="text-center">{!! lang('common.action') !!}</th>
</tr>
</thead>
<tbody>
<?php $index = 1; ?>
@foreach($data as $detail)

<tr id="order_{{ $detail->id }}">

    <td style="vertical-align: middle;"><input type="checkbox" value="{{ $detail->id }}" class="delete-checkbox delete" name="delete[]"></td>
   <!-- <td class="text-center">{!! pageIndex($index++, $page, $perPage) !!}</td> -->
    <td style="vertical-align: middle;"><a href="{!! route('product.edit', [$detail->id]) !!}">{{ $detail->name }} </a> </td>
    <td style="vertical-align: middle;">{!! $detail->sku !!}</td>
    <td style="vertical-align: middle;" class="text-center">{!! $detail->category !!}@if($detail->cat2), {{ $detail->cat2  }} @endif @if($detail->cat3), {{ $detail->cat3  }} @endif @if($detail->cat4), {{ $detail->cat4  }} @endif @if($detail->cat5), {{ $detail->cat5  }} @endif</td>

    <td style="vertical-align: middle;" class="text-center"> @if($detail->product_type == 1) Simple @else Group @endif </td>

    <td style="vertical-align: middle;" class="text-center">{!! $detail->regular_price !!}</td>
    <td style="vertical-align: middle;" class="text-center">{!! $detail->offer_price !!}</td>
         <td class="text-center">
        <a href="javascript:void(0);" class="toggle-status" data-message="{!! lang('messages.change_status') !!}" data-route="{!! route('product.toggle', $detail->id) !!}" title="@if($detail->status == 0) Deactive @else Active @endif">
            {!! Html::image('images/' . $detail->status . '.gif') !!}
        </a>
    </td>
    <td style="vertical-align: middle;" class="text-center col-md-1">
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
@else
<tr>
    <td colspan="11">
        <span class="delete-btn">Delete</span>
        {!! paginationControls($page, $total, $perPage) !!}
    </td>
</tr>
@endif
</tbody>

<script>

function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("paginate-load");
  switching = true;
  // Set the sorting direction to ascending:
  dir = "asc";
  /* Make a loop that will continue until
  no switching has been done: */
  while (switching) {
    // Start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < (rows.length - 1); i++) {
      // Start by saying there should be no switching:
      shouldSwitch = false;
      /* Get the two elements you want to compare,
      one from current row and one from the next: */
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /* Check if the two rows should switch place,
      based on the direction, asc or desc: */
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
      and mark that a switch has been done: */
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      // Each time a switch is done, increase this count by 1:
      switchcount ++;
    } else {
      /* If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again. */
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>

