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
    <div style="float: left; width: 100%; min-height: 300px; border: 1px solid #004c69;">
      <div style="float: left; width: 100%; overflow: hidden;">
        <a style="display: block; width: 100%;" href="#">
          <img style="max-width: 150px; margin: auto; display: block;margin-top: 10px;" src="{{route('home')}}/assets/frontend/images/logo.png" alt="logo">
        </a>
      </div>
      <div style="float: left; width: 100%; height: 100%; border-bottom: 1px solid #004c69; margin: 15px auto 0px auto;">
        <div style="float: left; width: 49%;padding-left:10px;">
          <h4 style="font-weight: 600; color: #004c69; float: left; margin: 0px;width: 100%;">Sold By:</h4>
          <p style="float: left; color: #000; margin: 10px 0px 2px 0px; font-size: 14px; margin-top: -1px;">Puka Creations</p>
          <p style="float: left; width:100%; color: #000; margin: 0px 0px 10px 0px; font-size: 13px;">16840 South Main St<br> Gardena CA 90248-3122<br> U.S.A</p>
        </div>
        <div style="float: right; text-align: right; width: 47%;padding-right:10px;">
          <h4 style="font-weight: 600; float: right; color: #004c69; margin: 0px;width: 100%;">Shipping Address:</h4>
          <p style="float: right; color: #000; margin: 10px 0px 10px 0px; font-size: 13px;"> {{$shipping_add->name}}<br> {{$shipping_add->address}}, {{$shipping_add->city}},  {{ $shipping_add->state }}, {{$shipping_add->pincode}}<br> Phone: {{$shipping_add->mobile}}</p>
          <h4 style="font-weight: 600; float: right; color: #004c69; margin: 0px;width: 100%;">Pay Later</h4>
        </div>
      </div>
      <div style="float: left; width: 100%; height: 100%; margin: 20px auto 0px auto;">
        <div style="float: left; width: 48%;padding-left:10px;">
          <h4 style="color: #004c69; font-weight: 600; float: left; margin: 0px;">Order Number:</h4>
          <p style="font-size:14px; color: #000; float: left; margin: 0px;">{{$current_order->order_nr}}</p>
        </div>
        <div style="float: right; text-align: right; width: 47%;padding-right:10px;">
          <h4 style="font-weight: 600; color: #004c69; float: right; margin: 10px 0px 0px 0px;">Order Date: <span style="font-size:14px; color: #000; font-weight: lighter;">{{$current_order->created_at}}</span></h4>
        </div>
      </div>
      <div style="float: left; width: 100%; height: 100%; border: 1px #004c69 solid; margin: 20px auto 0px auto;border-left:0px;">
        <table style="width: 100%; border-spacing: 0px; text-align: center;">
          <thead>
                 <tr style="margin:0px; padding: 0px;">
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Sr. No.</th>
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Product Name</th>
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Unit Price</th>
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Qty</th>
                    <th style="border-bottom: 1px solid #000;">Total Amount</th>
                  </tr>
          </thead>
          <tbody>
            @php $i = 0; 
            $sub_total = 0;
            $stich_price = 0;
            $list_price = 0;
            $sub_tax = 0;
            @endphp

            @foreach(get_order_product($current_order->id) as $o_pro)
            @php $i++;
            @endphp 

                  <tr>
                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">{{ $i }}</td>
                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">{{$o_pro->name}}</td>
                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&#36;{{ $o_pro->price }}</td>
                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">{{ $o_pro->quantity }}</td>
                    <td style="border-bottom: 1px solid #000;">&#36;{{ $o_pro->price*$o_pro->quantity }}</td>
                  </tr>  
            @endforeach

            <tr>
                <td colspan="3" style="border-right: 1px solid #000; text-align: right; font-weight: bold; min-height: 50px;">Total:</td>
                <td colspan="2" style="background: #CACACA;">&#36;{{$current_order->total_price}}</td>
            </tr>

                  <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold; border-top: 1px solid #000;">
                    <div style="width: 100%;padding-right: 20px;margin-top: 9px;margin-bottom: 9px;text-align: center; font-weight: normal;">"This is online generated bill, Signature not required"</div>
                    </td>
                  </tr>
                  
                  <tr>
                    <td colspan="5" style="text-align: center; font-weight: bold; border-top: 1px solid #000;">
                      <p style="font-size: 14px; color: #ab7d47; margin:0px; padding: 0px;">For any query mail us at
                        <span style="font-weight:bold;">orders@pukacreations.com</span>
                      </p>
                    </td>
                  </tr>
          
          </tbody>
        </table>
      </div>
    </div>
    </center>
  </body>
</html>