<thead class="noPrint">
<tr>
    <th width="5%" class="text-center">{!! lang('common.id') !!}</th>
    <th>Order No.</th>
    <th>Customer</th>
    <th>Price</th>
    <th>Order Date</th>
    <th style="text-align: center;width: 165px;"> {!! lang('common.status') !!} </th>
    <th class="text-center" style="width: 124px;">{!! lang('common.action') !!}</th>
</tr>
</thead>
<tbody>
<?php $index = 1; ?>
@foreach($data as $detail)
<tr id="order_{{ $detail->id }}">
    <td class="text-center noPrint">{!! pageIndex($index++, $page, $perPage) !!}</td>
    <td class="noPrint">{!! $detail->order_nr !!}</td>
    <td class="noPrint">{!! $detail->user_name !!}</td>
    <td class="noPrint"><i class="fa fa-dollar-sign"></i>{!! $detail->total_price !!}</td>
    <td class="noPrint">{!! date('d M, Y', strtotime($detail->created_at)) !!}</td>
    <td class="noPrint" style="text-align: center;">
     <a href="#" data-toggle="modal" data-target="#exampleModal{{ $detail->id }}" style=" @if($detail->c_status == 7) background: #f00; @endif @if($detail->c_status == 4) background: #00f; @endif @if($detail->c_status == 9) background: #f76f72; @endif @if($detail->c_status == 10) background: #C4A484; @endif @if($detail->c_status == 3) background: #ADD8E6; @endif @if($detail->c_status == 2) background: #90ee90; @endif @if($detail->c_status == 1) background: #f7a000; @endif @if($detail->c_status == 5) background: green; @endif color: #fff; padding: 6px 15px; border-radius: 14px;"> {!! $detail->current_status !!}</a>
    <div class="modal fade" id="exampleModal{{ $detail->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-body">
                  
                                {!! Form::open(array('method' => 'POST', 'route' => array('order-product-status'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" value="{{ $detail->id }}" name="order_id">
                                         <div class="form-group" style="text-align: left;"> 
                                            {!! Form::label('type', lang('Current Status'), array('class' => '')) !!}
                                            <select name="status" class="form-control" required ="true">
                                            <option value="">-Select-</option>   
                                            @foreach($statusType as $statustype) 
                                            <option value="{{ $statustype->id }}"
                                             @if($statustype->id == $detail->c_status) selected @endif>{{ $statustype->type }}</option> 

                                            @endforeach
                                            
                                            </select>
                                        </div> 
                                    </div>   
                                    <div class="col-md-12 mgn20">
                                         <div class="form-group" style="text-align: left;"> 
                                            {!! Form::label('transaction_id', lang('Transaction ID'), array('class' => '')) !!}
                                            <input type="text" class="form-control" name="transaction_id" value="{{ $detail->transaction_id }}"> 
                                        </div> 
                                    </div>                             
                                    </div>
                                      
                                <div class="row">
                                    <p>&nbsp;</p>
                                    <div class="col-md-12">
                                         <button type="submit" class="btn btn-default w3ls-button">Submit</button> 
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
          </div>
        </div>
      </div>
    </div>
    </td>
    <td class="text-center">
<a class="btn btn-xs btn-primary noPrint" href="{{ route('order.edit', [$detail->id]) }}"><i class="fa fa-eye"></i></a> <!--<a class="btn btn-xs btn-primary noPrint" href="{{ route('order.edit', [$detail->id]) }}#Products_Detail"><i class="fa fa-edit"></i></a>--><a class="btn btn-xs btn-primary noPrint" style="    margin-left: 3px;" onclick="window.print1({{$detail->id}});"> <i class="fa fa-print"></i></a> 
 <div class="def_nne pri{{$detail->id}}"> 
<div class="a-row a-spacing-base" style="float: left;width: 100%;margin-bottom: 10px;">
    <div class="a-column a-span9 a-spacing-top-mini">
        <div class="a-row a-spacing-none">
            <span class="order-date-invoice-item">
                Ordered on {{ date('d M Y', strtotime($detail->created_at)) }}
                <i class="a-icon a-icon-text-separator" role="img"></i>
            </span>
            <span class="order-date-invoice-item"> Order <bdi dir="ltr">{{ $detail->order_nr }}</bdi> </span>
        </div> 
    </div>  
</div>

<div class="a-box a-spacing-base">
<div class="a-box-inner">
<div class="a-fixed-right-grid">
<div class="a-fixed-right-grid-inner" style="padding-right:150px">
<div class="a-fixed-right-grid-col a-col-left" style="padding-right:0%;float:left; width: 450px;">
<div class="a-row">
<div class="a-column a-span5">         
<div class="a-section a-spacing-none od-shipping-address-container">
<h5 class="a-spacing-micro">Shipping Address</h5> 

<div class="a-row a-spacing-micro">
<div class="displayAddressDiv">
<ul class="displayAddressUL">
<li class="displayAddressLI displayAddressFullName">{{ $detail->address_name }}</li>
<li class="displayAddressLI displayAddressAddressLine1">{{ $detail->address }}</li>
<li class="displayAddressLI displayAddressCityStateOrRegionPostalCode">{{ $detail->city }}, {{ $detail->state }} {{ $detail->pincode }}</li>
<li class="displayAddressLI displayAddressCountryName">{{ $detail->country_name }}</li>
</ul>
</div>
</div> 
</div> 
</div> 

<div class="a-column a-span7 a-span-last">
<div class="a-section a-spacing-base">
<div class="a-section a-spacing-none">
<h5 class="a-spacing-micro">Transaction ID</h5> 
<div class="a-row">
    {{ $detail->transaction_id }}
</div> 
</div> 
</div>
</div> 
</div> 
</div> 

<div id="od-subtotals" class="a-fixed-right-grid-col a-col-right" style="width:260px;margin-right:-260px;float:left;">
<h5 class="a-spacing-micro a-text-left">Order Summary</h5> 
<div class="a-row">
<div class="a-column a-span7 a-text-left">
<span class="a-color-base">Item(s) Subtotal:</span> 
</div> 
<div class="a-column a-span5 a-text-right a-span-last">
<span class="a-color-base">
<span style="text-decoration: inherit; white-space: nowrap;"><span class="currencyINR">&nbsp;&nbsp;</span>&#36;{{ $detail->total_price + $detail->case_deal_price }}</span>
</span> 
</div> 
</div> 
 
<div class="a-row">
<div class="a-column a-span7 a-text-left">
<span class="a-color-base">Total:</span> 
</div> 
<div class="a-column a-span5 a-text-right a-span-last">
<span class="a-color-base">
<span style="text-decoration: inherit; white-space: nowrap;"><span class="currencyINR">&nbsp;&nbsp;</span>&#36;{{ $detail->total_price }}</span>
</span> 
</div> 
</div> 
@if($detail->case_deal_price)   
<div class="a-row">
<div class="a-column a-span7 a-text-left">
<span class="a-color-base">Case Deal:</span> 
</div> 
<div class="a-column a-span5 a-text-right a-span-last">
<span class="a-color-base">
        -<span style="text-decoration: inherit; white-space: nowrap;"><span class="currencyINR">&nbsp;&nbsp;</span>&#36;{{ $detail->case_deal_price }}</span>
    </span> 
</div> 
</div> 
 @endif 
<div class="a-row">
<div class="a-column a-span7 a-text-left">
<span class="a-color-base a-text-bold">Grand Total:</span> 
</div> 
<div class="a-column a-span5 a-text-right a-span-last">
<span class="a-color-base a-text-bold">
<span style="text-decoration: inherit; white-space: nowrap;"><span class="currencyINR">&nbsp;&nbsp;</span>&#36;{{ $detail->total_price }}</span></span> 
</div> 
</div> 
</div> 
</div>
</div>
</div>
</div> 


<div class="a-box shipment shipment-is-delivered" style="width: 100%;padding-bottom: 20px;">
<div class="a-box-inner">
<div class="a-fixed-right-grid a-spacing-top-medium">
<div class="a-fixed-right-grid-inner a-grid-vertical-align a-grid-top">
<div class="a-fixed-right-grid-col a-col-left" style="padding-right:3.2%;float:left;">

@foreach(get_ord_prod($detail->id) as $prod)
<div class="a-row" style="margin-bottom: 10px; float:left; width:100%;padding-top:15px;padding-bottom:15px; border-bottom:1px solid #f3f3f3; ">
<div class="a-fixed-left-grid a-spacing-none"><div class="a-fixed-left-grid-inner" style="padding-left:100px">
<div class="a-text-center a-fixed-left-grid-col a-col-left" style="width:100px;margin-left:-100px;float:left;">
<div class="item-view-left-col-inner">
<img alt="" src="{!! asset($prod->thumbnail) !!}">
</div>
</div>
<div class="a-fixed-left-grid-col yohtmlc-item a-col-right" style="padding-left:1.5%;float:left;">        
<div class="a-row">     
        {{ $prod->name }}
</div>
<div class="a-row">
<span class="a-size-small a-color-secondary">
Sold by: Puka Creations</span></div>
<div class="a-row">   
<span class="a-size-small a-color-price">
    <span style="text-decoration: inherit; white-space: nowrap;"><span class="currencyINR">&nbsp;&nbsp;</span>&#36;{{ $prod->price }}</span>
</span> 
</div>
</div>
</div>
</div>
</div>
@endforeach



</div>
</div>
</div>
</div>
</div>


 </div></td>     
</tr>
@endforeach
@if (count($data) < 1)
<tr class="noPrint"><td class="text-center" colspan="8"> {!! lang('messages.no_data_found') !!} </td></tr>
@else
<tr class="noPrint">
    <td colspan="10">
        {!! paginationControls($page, $total, $perPage) !!}
    </td>
</tr>
@endif
</tbody>



<script type="text/javascript">
function print1($id) {
    $('.def_nne').attr('style','display: none !important');
    $('.pri'+$id).attr('style','display: block !important');
    window.print();

};    

</script>