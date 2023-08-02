<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
    <title>Invoice</title>
    <style>
      *{
        font-family: 'Open Sans', Verdana;
      }
    </style>
  </head>
  <body width="100%" style="margin: 50px auto; mso-line-height-rule:exactly; display: block; background-color: #fff; width: 100%; float: left;">
    <center style="width: 500px; display: block; text-align: left; margin: 0 auto">
    <div style="float: left; width: 100%; padding: 0px 15px 15px 15px; min-height: 300px;">
      <div style="float: left; width: 100%; overflow: hidden;">
        <a style="float: left;" href="#">
          <img style="max-width: 120px; margin: auto; display: block;margin-top: 10px;" src="https://frugr.com/images/logo.png" alt="frugr">
        </a>
        <div style="float: left; border-bottom: 1px solid #555; padding-bottom: 4px; margin-left: 3px;margin-top: 7px;">
        <a target="_blank" href="{!! route('my-orders') !!}" style="color: #007185; text-decoration: none; font-size: 15px; padding: 0px 7px;">Your Orders</a> | <a target="_blank" href="{{ route('my-profile')}}" style="color: #007185; text-decoration: none; font-size: 15px; padding: 0px 7px;">Your Account</a> | <a target="_blank" href="http://frugr.com/" style="color: #007185; text-decoration: none; font-size: 15px; padding: 0px 7px;">Frugr.com</a>
        </div> 
      </div>
      <div style="float: left;width: 100%;">
      <h4 style="text-align: right; margin-right: 70px; margin-bottom: 0px; font-weight: 500; font-size: 21px; float: right; margin-top: 0px;width: 100%; color: #5fad36;">Order Confirmation</h4><br>
      <h6 style="margin-top: 0px; float: right; font-weight: 500; font-size: 13px; margin-right: 70px;">Order <span style="color: #007185;">{{ $current_order->order_nr }}</span></h6>
      </div>
      <div style="float: left;width: 100%;">
      <h5 style="color: #f47624; font-weight: 500; font-size: 21px; margin-top: 10px; margin-bottom: 0px;">{{ $cus_name }},</h5>
      <p style="margin-top: 0px; font-size: 14px;line-height: 21px;">Thank you for your order. We'll send a confirmation when your order ships. Your estimated delivery date is indicated below. If you would like to view the status of your order or make any changes to it, please visit <a target="_blank" href="{!! route('my-orders') !!}" style="color: #007185; text-decoration: none;">Your Orders</a> on Frugr.com.</p>
      </div>
       
      <div style="float: left;width: 100%; background: #efefef; border-top: 3px solid #32373D;">
      <div style="float: left;width: 50%;">
      <div style="padding: 15px;">
      <h6 style="margin-top: 0px; color: #a5a5a5; font-weight: normal;font-size: 13px;margin-bottom: 0px;">Arriving:</h6>
      <h5 style="margin-top: 0px; color: #5FAD36;margin-bottom: 10px;">{{ $current_order->delivery_date }}</h5>
      <h6 style="margin-top: 0px; color: #a5a5a5; font-weight: normal;font-size: 13px;margin-bottom: 0px;">Your shipping speed:</h6>
      <h5 style="margin-top: 0px; font-weight: 600;">FREE Delivery on eligible orders</h5>
      <a target="_blank" href="{!! route('my-orders') !!}" style="text-decoration: none; background: #f47624; color: #fff; font-weight: 300; padding: 6px 14px; border-radius: 10px;">View or manage order</a>
      </div> 
      </div>
      <div style="float: left;width: 50%;">
      <div style="padding: 15px;">
      <h6 style="margin-top: 0px; color: #a5a5a5; font-weight: normal;font-size: 13px;margin-bottom: 0px;">Your order will be sent to:</h6>
      <h5 style="margin-top: 0px; margin-bottom: 0px; font-weight: 600; font-size: 13px;">{{ $shipping_add->name }}</h5>
      <h6 style="margin-top: 0px; margin-bottom: 0px; font-weight: 600; font-size: 13px;">{{$shipping_add->address}}, {{$shipping_add->city}},  {{ $shipping_add->state }}, {{$shipping_add->pincode}}</h6>
      </div>
      </div>
      </div>
       <div style="float: left;width: 100%;">
      <h5 style="color: #f47624; font-weight: 500; font-size: 21px; margin-top: 10px; margin-bottom: 0px;border-bottom: 1px solid #e7e7e7;
    padding-bottom: 3px; margin-top: 30px;">Order Summary</h5> 
      <h6 style="margin-top: 3px; font-weight: 500; font-size: 12px;margin-bottom: 3px;">Order <span style="color: #007185;">{{$current_order->order_nr}}</span></h6>
      <h6 style="margin-top: 0px; color: #a5a5a5; font-weight: normal;font-size: 13px;margin-bottom: 0px;">Placed on {{$current_order->created_at}}</h6>
        @php $i = 0; 
            $sub_total = 0;
            $stich_price = 0;
            $list_price = 0;
            $sub_tax = 0;
            @endphp
            @foreach(get_order_product($current_order->id) as $o_pro)
            @php $i++;
                $list_price += $o_pro->list_price*$o_pro->quantity; 
                $text_per =  get_tax_per($o_pro->parent_id);
                $sub_total += round($o_pro->quantity*$o_pro->price);
                $sub_tax +=  ($o_pro->price*$o_pro->quantity) - round((($o_pro->quantity*$o_pro->price)*100/(100+ $text_per)), 2);
            @endphp 
            @endforeach

      <table>
        <tr>
          <td>Item Subtotal:</td>
          <td style="padding-left: 15px;color: #3c3c3c; font-weight: 500; font-size: 14px;">Rs. {{ $sub_total }}</td>
        </tr>
        <tr>
          <td>Shipping & Handling</td>
          <td style="padding-left: 15px;color: #3c3c3c; font-weight: 500; font-size: 14px;">Rs. {{ $shipping_charge }}</td>
        </tr>
        <tr>
          <td>Order Total:</td>
          <td style="padding-left: 15px;color: #3c3c3c; font-weight: 500; font-size: 14px;">Rs. {{ $sub_total + $shipping_charge }}</td>
        </tr>  
      </table>
   <p style="font-size: 14px; color: #555; margin-top: 20px; padding: 0px; font-weight: 500; border-top: 1px solid #e7e7e7; padding-top: 8px;">For any query mail us at info@frugr.com </p>
      </div>
    </div>
    </center>
  </body>
</html>