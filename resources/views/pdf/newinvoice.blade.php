<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
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
          <tr style="width: 120px; height: 90px; overflow: hidden;">
            <th>
            <a href="#"><img style="width: 150px; height: 150px; margin-left: 20px; padding-bottom: 50px;" src="http://uphaar.thegirafe.com/public/images/logo.png"></a>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr style="width: 100%; border-bottom: 1px solid #000; margin:0px; padding: 15px auto 15px auto;">
            <td style="width: 48%;">
              <h4 style="margin:0px; padding: 0px;">Sold By:</h4>
              <p style="margin:0px; padding: 5px 0px; width: 100%;">Uphaar</p>
              <p style="margin:0px; padding: 0px 0px 5px 0px;">SCO 92-93-94, Sector 17 D, Chandigarh, 160017</p>
            </td>
            <td style="text-align: right; width: 48%;">
              <h4 style="margin:0px; padding: 0px; width: 100%;">Shipping Address:</h4>
              <p style="margin:0px; padding: 5px 0px 5px 0px; width: 100%;">{{$orderdetails->shipping->name}}, {{$orderdetails->shipping->address}}, {{$orderdetails->shipping->city_name}},  {{$orderdetails->shipping->state_name}}</p>
            </td>
          </tr>
          <tr style="width: 100%; border-bottom: 1px solid #000; margin:0px; padding: 20px auto 15px auto;">
            <td style="width: 48%;">
              <h4 style="margin:0px; padding: 0px;">PAN No: <span style="font-weight: lighter;">AAMCA5258P</span></h4>
              <h4 style="margin:0px; padding: 5px 0px 0px 0px; width: 100%;">GST Registration No: </h4>
              <p style="margin:0px; padding: 0px 0px 5px 0px;">03AASFK3016C1ZS</p>
            </td>
          
          </tr>
          <tr style="width: 100%; margin:0px; padding: 20px auto 15px auto;">
            <td style="width: 48%;">
              <h4 style="margin:0px; padding: 0px;">Order Number:</h4>
              <p style="margin:0px; padding: 0px;">{{$orderdetails->order_nr}}</p>
              <h4 style="margin:0px; padding: 5px 0px 0px 0px; width: 100%;">Order Date: <span style="font-weight: lighter;">{{$orderdetails->created}}</span></h4>
            </td>
            <td style="text-align: right; width: 48%;">
              <h4 style="margin:0px; padding: 0px 0px 0px 0px; width: 100%;">Invoice Number: <span style="font-weight: lighter;">{{$orderdetails->order_nr}}</span></h4>
              <!-- <h4 style="margin:0px; padding: 5px 0px 0px 0px; width: 100%; width: 100%;">Invoice Details: </h4>
              <p style="margin:0px; padding: 0px 0px 0px 0px; width: 100%;">HR-DEL-2-154003231-1819</p> -->
              <h4 style="margin:0px; padding: 5px 0px 0px 0px; width: 100%;">Order Date: <span style="font-weight: lighter;">{{$orderdetails->created}}</span></h4>
            </td>
          </tr>
          <tr style="width: 100%; border: 1px #000 solid; margin-top:0px; padding: 20px auto 0px auto;">
              <table style="width: 100%; border: 1px #000 solid; border-spacing: 0px; text-align: center;">
                <thead>
                  <tr style="margin:0px; padding: 0px;">
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Sr. No.</th>
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Product Name</th>
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Unit Price</th>
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Qty</th>
                    <!-- <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Net Amount</th>
                    <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Tax Amount</th> -->
                    <!-- <th style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Tax Rate</th> -->
                    <th style="border-bottom: 1px solid #000;">Total Amount</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($orderdetails->product as $pro)                  <tr>
                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">{{ $loop->iteration }}</td>
                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">
                       {{$pro->product->name}}
                    </td>
                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">Rs. {{$pro->product->sale_price}}</td>
                    <td style="border-bottom: 1px solid #000; border-right: 1px solid #000;">{{$pro->quantity}}</td>
                  </tr>
                  @endforeach
                  
                  <tr>
                    <td colspan="4" style="border-right: 1px solid #000; text-align: right; font-weight: bold;">Tax:</td>
                    <td style="">Rs. {{$orderdetails->tax}}</td>
                  </tr>


                  <tr>
                    <td colspan="4" style="border-right: 1px solid #000; text-align: right; font-weight: bold;">PAY WALLET:</td>
                    <td style="font-weight: bold;">Rs. {{$orderdetails->wallet_paid}}</td>
                  </tr>
                  <tr>
                    <td colspan="4" style="border-right: 1px solid #000; text-align: right; font-weight: bold;">TOTAL:</td>
                    <td style="font-weight: bold;">Rs. {{$orderdetails->total_price}}</td>
                  </tr>
                  <tr>
                    <td colspan="6" style="text-align: left; font-weight: bold; border-top: 1px solid #000;">
                      
                    </td>
                  </tr>

                  {{--<tr>
                    <td colspan="5" style="text-align: left; font-weight: bold; border-bottom: 1px solid #000; text-transform: capitalize;">
                      {{$orderdetails->word}}
                    </td>
                  </tr>--}}

                  <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold; ">
                      For Uphaar
                    </td>
                  </tr>
                  <tr>
                    <td colspan="4">
                      
                    </td>
                  </tr>
                 

                  <tr>
                    <td colspan="5" style="text-align: center; font-weight: bold; border-top: 1px solid #000;">
                      <p style="font-size: 14px; color: #ab7d47; margin:0px; padding: 0px;">For any query mail us at
                        <span style="font-weight:bold;"><a style="color: unset; text-decoration: unset;" href="#">info@uphaar.com</a></span>
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