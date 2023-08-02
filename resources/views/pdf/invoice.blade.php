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
    <div style="float: left; width: 100%; padding: 0px 15px 15px 15px; min-height: 300px; border: 1px solid #004c69;">
      <div style="float: left; width: 100%; overflow: hidden;">
        <a style="display: block; width: 100%;" href="#">
          <img style="max-width: 150px; margin: auto; display: block;" src="http://uphaar.thegirafe.com/assets/frontend/images/logo.png">
        </a>
      </div>
      <div style="float: left; width: 100%; height: 100%; border-bottom: 1px solid #004c69; margin: 15px auto 0px auto;">
        <div style="float: left; width: 49%;">
          <h4 style="font-weight: 600; color: #004c69; float: left; margin: 0px;">Sold By:</h4>
          <p style="float: left; color: #000; font-weight: 600; margin: 10px 0px; font-size: 14px;">Uphaar</p>
          <p style="float: left; color: #000; margin: 0px 0px 10px 0px; font-size: 14px;">SCO 92-93-94, Sector 17 D, Chandigarh, 160017</p>
        </div>
        <div style="float: right; text-align: right; width: 48%;">
          <h4 style="font-weight: 600; float: right; color: #004c69; margin: 0px;">Shipping Address:</h4>
          <p style="float: right; color: #000; margin: 10px 0px 10px 0px; font-size: 14px;"> {{$orderdetails->shipping->name}}, {{$orderdetails->shipping->address}}, {{$orderdetails->shipping->city_name}},  {{$orderdetails->shipping->state_name}}</p>
        </div>
      </div>
      <div style="float: left; width: 100%; height: 100%; margin: 20px auto 0px auto;">
        <div style="float: left; width: 48%;">
          <h4 style="color: #004c69; font-weight: 600; float: left; margin: 0px;">Order Number:</h4>
          <p style="font-size:14px; color: #000; float: left; margin: 0px;">{{$orderdetails->order_nr}}</p>
          <!-- <h4 style="font-weight: 600; color: #004c69; float: left; margin: 10px 0px 0px 0px;">Order Date: <span style="font-size:14px; color: #000; font-weight: lighter;">25-12-2018</span></h4> -->
        </div>
        <div style="float: right; text-align: right; width: 48%;">
          <h4 style="font-weight: 600; color: #004c69; float: right; margin: 0px 0px 0px 0px;">Invoice Number: <span style="font-size:14px; color: #000; font-weight: lighter;">{{$orderdetails->order_nr}}</span></h4>
          <h4 style="font-weight: 600; color: #004c69; float: right; margin: 10px 0px 0px 0px;">Order Date: <span style="font-size:14px; color: #000; font-weight: lighter;">{{$orderdetails->created}}</span></h4>
        </div>
      </div>
      <div style="float: left; width: 100%; height: 100%; border: 1px #004c69 solid; margin: 20px auto 0px auto;">
        <table style="width: 100%; border-spacing: 0px; text-align: center;">
          <thead>
            <tr style="padding: 0px;">
              <th style="font-size:14px; font-weight:600; border-bottom: 1px solid #004c69; border-right: 1px solid #004c69; color: #004c69;">Sr. No.</th>
              <th style="font-size:14px; font-weight:600; border-bottom: 1px solid #004c69; border-right: 1px solid #004c69; color: #004c69;">Product Name</th>
              <th style="font-size:14px; font-weight:600; border-bottom: 1px solid #004c69; border-right: 1px solid #004c69; color: #004c69;">Unit Price</th>
              <th style="font-size:14px; font-weight:600; border-bottom: 1px solid #004c69; border-right: 1px solid #004c69; color: #004c69;">Qty</th>
              <th style="font-size:14px; font-weight:600; border-bottom: 1px solid #004c69; color: #004c69;">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($orderdetails->product as $pro)
            <tr>
              <td style=" font-size: 14px; border-bottom: 1px solid #004c69; border-right: 1px solid #004c69; color: #004c69;">1</td>
              <td style=" font-size: 14px; border-bottom: 1px solid #004c69; border-right: 1px solid #004c69; color: #000;">{{$pro->product->name}}</td>
              <td style=" font-size: 14px; border-bottom: 1px solid #004c69; border-right: 1px solid #004c69; color: #000;">&#8377;&nbsp;{{$pro->product->sale_price}}</td>
              <td style=" font-size: 14px; border-bottom: 1px solid #004c69; border-right: 1px solid #004c69; color: #000;">{{$pro->quantity}}</td>
              <td style=" font-size: 14px; color: #000; border-bottom: 1px solid #004c69;">&#8377;&nbsp;{{$pro->price}}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="4" style="font-weight: 600; font-size:14px; border-right: 1px solid #004c69; border-bottom: 1px solid #004c69; text-align: left; color: #004c69; padding: 1px 5px;">SUB TOTAL</td>
              <td style="font-weight: 600; font-size:14px; color: #000; border-bottom: 1px solid #004c69;">&#8377;&nbsp;{{$orderdetails->sub_total}}</td>
            </tr>
            <tr>
              <td colspan="4" style="font-weight: 600; font-size:14px; border-right: 1px solid #004c69; border-bottom: 1px solid #004c69; text-align: left; color: #004c69; padding: 1px 5px;">GST @ 18%</td>
              <td style="font-weight: 600; font-size:14px; border-bottom: 1px solid #004c69;  color: #000;">&#8377;&nbsp;{{$orderdetails->tax}}</td>
            </tr>
            <tr>
              <td colspan="4" style="font-weight: 700; font-size:16px; border-right: 1px solid #004c69; text-align: left; color: #000; padding: 5px 5px;">Grand Total</td>
              <td style="font-weight: 700; font-size:16px; color: #000;">&#8377;&nbsp;{{$orderdetails->total_price}}</td>
            </tr>
            <tr>
              <td colspan="8" style="padding: 3px 0px 3px 5px; text-align: left; font-weight: 600; border-top: 1px solid #004c69; color: #000; font-size:14px;">
                Amount In Words:
              </td>
            </tr>
            <tr>
              <td colspan="8" style="padding: 0px 0px 3px 5px; text-align: left; font-weight: 600; border-bottom: 1px solid #004c69; color: #000; font-size:14px;">
                {{$orderdetails->word}}
              </td>
            </tr>
            <tr>
              <td style="text-align: right; padding: 5px 5px 5px 0px;" colspan="8">
                <img style="max-width: 100px; display: block; margin-left: 300px;" src="images/signature.png" alt="">
                <strong style="font-weight: 600; color: #000; font-size: 14px;">For Uphaar</strong>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div style="float: left; width: 100%; margin: 15px 0px 0px 0px; text-align: center;">
        <p style="font-size: 14px; color: #004c69; margin: 0px;">For any query mail us at
          <span style="font-weight:600;"><a style="color: unset; text-decoration: unset;" href="info@uphaar.com">info@uphaar.com</a></span>
        </p>
        <p style="font-size: 13px; font-weight: 600; color: #000; margin: 10px 0px 0px 0px;">Terms & Conditions Apply. &copy; 2019 Uphaar. All Rights Reserved.
        </p>
      </div>
    </div>
    </center>
  </body>
</html>