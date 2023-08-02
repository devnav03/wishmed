<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>Invoice</title>
  </head>
  <body width="100%" style="margin:0px; padding: 0px 0px; mso-line-height-rule:exactly; background-color: #fff;">
    <center style="width: 100%; background: #fff; text-align: left; margin:0px; padding: 10px 10px;">
      <table style="width: 100%; margin:0px; padding: 5px 5px; background-color: #ffffff; border: 1px solid #000; margin:0px; padding: 0 auto;">
        <thead>
          <tr style="width: 100%; height: 90px; overflow: hidden;">
            <th colspan="2" style="text-align: center;">
              <center> <img style="width: 120px; margin:0px; padding-top: 40px; padding-bottom: 40px;" src="https://frugr.com/images/logo.png"></center>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr style="width: 100%; border-bottom: 1px solid #000; margin:0px; padding: 15px auto 15px auto;">
            <td style="width: 48%;">
              <h4 style="margin:0px; padding: 0px;">Sold By:</h4>
              <p style="margin:0px; padding: 5px 0px; width: 100%;">Frugr Retail Private Limited</p>
              <p style="margin:0px; padding: 0px 0px 5px 0px;">Zirakpur, Mohali, Punjab 140603 INDIA</p>
              <p style="margin:0px; padding: 0px 0px 5px 0px;">Pan Card :- AAECF0964H</p> 
              <p style="margin:0px; padding: 0px 0px 5px 0px;">GST REGISTRATION NO :- 03AAECF0964H1ZW</p>
            </td>
            @if(isset($order->city))
            <td style="text-align: right; width: 48%;">
              <h4 style="margin:0px; padding: 0px; width: 100%;">Shipping Address:</h4>
              <p style="margin:0px; padding: 5px 0px 5px 0px; width: 100%;">{{$order->name}}, {{$order->address}}, {{$order->city}},  {{$order->state}}- {{$order->pincode}}</p>
               <h4 style="margin:15px 0px 0px 0px; padding: 0px; width: 100%;">Billing Address:</h4>
              <p style="margin:0px; padding: 5px 0px 5px 0px; width: 100%;">{{$order->billing_name}}, {{$order->billing_address}}, {{$order->billing_city}},  {{$order->billing_state}}- {{$order->billing_pincode}}</p>
            </td>
            @endif
          </tr>
          <tr style="width: 100%; margin:0px; padding: 20px auto 15px auto;">
            <td style="width: 48%;">
              <h4 style="margin:0px; padding: 0px;">Order Number:</h4>
              <p style="margin:0px; padding: 0px;">{{ $order->order_nr }}</p>
              <h4 style="margin:0px; padding: 5px 0px 0px 0px; width: 100%;">Order Date: <span style="font-weight: lighter;">{{ $order->created_at }}</span></h4>
            </td>
            <td style="text-align: right; width: 48%;">
             <h4 style="margin:0px; padding: 0px 0px 0px 0px; width: 100%;">Place Of Supply: <span style="font-weight: lighter;">{{$order->state}}</span></h4> 
            
              <h4 style="margin:0px; padding: 5px 0px 0px 0px; width: 100%;">Invoice Date: <span style="font-weight: lighter;">{{ $order->created_at }}</span></h4>
              <h4 style="margin:0px; padding: 5px 0px 0px 0px; width: 100%;">Invoice Number: <span style="font-weight: lighter;">FEI-000{{ $order->id }}</span></h4>
            </td>
          </tr>
          <tr style="width: 100%; border: 1px #000 solid; margin-top:0px; padding: 20px auto 0px auto;">
              <table style="width: 100%; border: 1px #000 solid; border-spacing: 0px; text-align: center;">
                <thead>
                  <tr style="margin:0px; padding: 0px;">
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Sr. No.</th>
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Product Name</th>
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">HSN Code</th>
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Unit Price</th>
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Qty</th>
                     <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Net Amount</th>
                     @if($order->billing_state == 'Chandigarh')
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">CGST</th>
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">SGST</th>  
                     @else
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Tax Rate</th>
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Tax Type</th> 
                     @endif
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Tax Amount</th>
                    <th style="border-bottom: 1px solid #000;border-right: 1px solid #000;">Total</th>
                  </tr>
                </thead>
                <tbody>
                @php $i = 0; 
                $sub_total = 0;
                $sub_tax = 0;
                $stich_price = 0;
                $list_price = 0;
                @endphp
                @foreach(get_order_product($order->id) as $o_pro)
                @php $i++;
                $text_per =  get_tax_per($o_pro->parent_id);
                $sub_total += round((($o_pro->quantity*$o_pro->price)*100/(100+ $text_per)), 2);
                $sub_tax +=  ($o_pro->price*$o_pro->quantity) - round((($o_pro->quantity*$o_pro->price)*100/(100+ $text_per)), 2);
                $list_price += $o_pro->list_price*$o_pro->quantity - $o_pro->quantity*$o_pro->price;
            
                @endphp 
                  <tr>
                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">{{ $i }}</td>

                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">{{$o_pro->name}}</td>
                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">{{ $o_pro->hsncode }}</td>


                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Rs. {{ round((($o_pro->price)*100/(100+ $text_per)), 2) }}</td>

                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">{{ $o_pro->quantity }}</td>

                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Rs. {{ round((($o_pro->quantity*$o_pro->price)*100/(100+$text_per)), 2) }}</td>
                    

                   @if($order->billing_state == 'Chandigarh')
                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">{{ $text_per/2 }}%</td>
                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">{{ $text_per/2 }}%</td>
                   @else
                   <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;"> {{ $text_per }} % </td>
                   <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">IGST</td>
                   @endif

                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">
                     Rs. {{ ($o_pro->price*$o_pro->quantity) - round((($o_pro->quantity*$o_pro->price)*100/(100+$text_per)), 2) }}
                    </td>

                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Rs. 
                      {{ $o_pro->price*$o_pro->quantity }}</td>
     
                  </tr>
                  @endforeach
         
                  <tr>
                    <td colspan="8" style="border-right: 1px solid #000;border-bottom: 1px solid #000; text-align: right; font-weight: bold;">Sub Total:</td>
                    <td colspan="2" style="border-right: 1px solid #000;border-bottom: 1px solid #000;">Rs. {{ $sub_total + $sub_tax }}</td>
                  </tr>
                  <tr>
                    <td colspan="8" style="border-bottom: 1px solid #000;border-right: 1px solid #000; text-align: right; font-weight: bold; min-height: 50px;">Delivery Charges:</td>
                    <td colspan="2" style="border-right: 1px solid #000;border-bottom: 1px solid #000; background: #CACACA;"> @if($order->shipping_charges == 0) Free @else Rs. {{ $order->shipping_charges }} @endif</td>
                  </tr>
            
                  <tr>
                    <td colspan="8" style="border-bottom: 1px solid #000;border-right: 1px solid #000; text-align: right; font-weight: bold; min-height: 50px;">Grand Total:</td>
                    <td colspan="2" style="border-right: 1px solid #000;border-bottom: 1px solid #000; background: #CACACA;">Rs. {{ $sub_tax + $sub_total + $order->shipping_charges }}</td>
                  </tr>


                  <tr>
                    <td colspan="10" style="text-align: right; font-weight: bold; ">
                    <div style="width: 100%;padding-right: 20px;margin-top: 9px;margin-bottom: 9px;text-align: right;font-weight: 700;color: #5FAD36;">Your Total Savings is {{ $list_price }} </div>
                    </td>
                  </tr>

                  <tr>
                    <td colspan="10" style="text-align: right; font-weight: bold; border-top: 1px solid #000;">
                    <div style="width: 100%;padding-right: 20px;margin-top: 9px;margin-bottom: 9px;text-align: center; font-weight: normal;">"This is online generated bill, Signature not required"</div>
                    </td>
                  </tr>
                  
                  <tr>
                    <td colspan="10" style="text-align: center; font-weight: bold; border-top: 1px solid #000;">
                      <p style="font-size: 14px; color: #ab7d47; margin:0px; padding: 0px;">For any query mail us at
                        <span style="font-weight:bold;"></span>
                      </p>
                    </td>
                  </tr>


                </tbody>
              </table>
          </tr>
          
        </tbody>
      </table>
    </center>
  </body>
</html>