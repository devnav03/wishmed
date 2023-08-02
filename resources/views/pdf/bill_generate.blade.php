<html>
<head>
</head>
<body>
<h1 style="text-align: center;">Order Detail</h1>
<table style="border: 1px solid #000; width: 100%;max-width: 100%;">
 <thead>
   <tr>
     <th style="line-height: 1.42857143;padding-left:5px;">#</th>
     <th style="border-left: 1px solid #000;line-height: 1.42857143;padding-left:5px;">Name</th>
     <th style="border-left: 1px solid #000;line-height: 1.42857143;padding-left:5px;line-height: 1.42857143;">Order No.</th>
     <th style="border-left: 1px solid #000;line-height: 1.42857143;padding-left:5px;">Order Date</th>
     <th style="border-left: 1px solid #000;line-height: 1.42857143;padding-left:5px;">Price</th>
     <th style="border-left: 1px solid #000;line-height: 1.42857143;padding-left:5px;">Status</th>
     <th style="border-left: 1px solid #000;line-height: 1.42857143;padding-left:5px;">Payment Method</th>
   </tr>
 </thead> 

@if(isset($orders))
<?php $i =1;?>
@foreach($orders as $order)
 <tr>
     <td style="border-top: 1px solid #000;line-height: 1.42857143;padding-left:5px;">{{ $i }}</td>
     <td style="border-top: 1px solid #000;line-height: 1.42857143;padding-left:5px;border-left: 1px solid #000;">{{ $order->name }}</td>
     <td style="border-top: 1px solid #000;line-height: 1.42857143;padding-left:5px;border-left: 1px solid #000;">{{ $order->order_nr }}</td>
     <td style="border-top: 1px solid #000;line-height: 1.42857143;padding-left:5px;border-left: 1px solid #000;">{{ $order->created_at }}</td>
     <td style="border-top: 1px solid #000;line-height: 1.42857143;padding-left:5px;border-left: 1px solid #000;">{{ $order->total_price }}</td>
     <td style="border-top: 1px solid #000;line-height: 1.42857143;padding-left:5px;border-left: 1px solid #000;">{{ $order->type }}</td>
     <td style="border-top: 1px solid #000;line-height: 1.42857143;padding-left:5px;border-left: 1px solid #000;">{{ $order->payment_method }}</td>
   </tr>
<?php $i++;?>   
@endforeach   
@else
No Data

@endif

</table>
</body>
</html>