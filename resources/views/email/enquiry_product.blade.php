<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- utf-8 works for most cases -->
        <meta name="viewport" content="width=device-width">
        <!-- Forcing initial-scale shouldn't be necessary -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Use the latest (edge) version of IE rendering engine -->
        <meta name="x-apple-disable-message-reformatting">
        <!-- Disable auto-scale in iOS 10 Mail entirely -->
        <title>Emailer</title>
    </head>
    <body style="margin: 0; mso-line-height-rule: exactly; margin-top: 50px !important; margin-bottom: 50px !important; background-color: #222222;">
      <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,500,600" rel="stylesheet" type="text/css">

        <style>
            html, body {
               font-family: 'Open Sans', sans-serif;
            }
        </style>

        <center style="width: 100%; text-align: left;">
            <div style="width:700px; min-height: 300px; margin: 0 auto; background-color:white; border: 12px solid white;">
               
               <div style="width: 100%; height: 100%; margin: 0 auto;">
                  <div class="header-logo" style="width: 120px; padding:20px 20px; overflow: hidden; margin: 0 auto;">
                    <a href="#" target="_blank"><img style="max-width: 150px; margin: auto; display: block;" src="http://uphaar.thegirafe.com/public/images/logo.png" alt="http://uphaar.thegirafe.com/public/images/logo.png"></a>
                  </div>
                  <div style="padding: 50px 0px; width: 100%; text-align: center;">
                    <table cellpadding="3" cellspacing="0" style="width: 600px; border-bottom: 1px solid #CCC; border-right: 1px solid #CCC">
                      <tbody>
                        <tr valign="top">
                          <td style="border-top: 1px solid #CCC; border-left: 1px solid #CCC; padding: 10px; color: #3D3D3D">Name</td>
                          <td style="border-top: 1px solid #CCC; border-left: 1px solid #CCC; padding: 10px; color: #3D3D3D">{{ $mail_data['name'] }}</td></tr>
                          <tr valign="top"><td style="border-top: 1px solid #CCC; border-left: 1px solid #CCC; padding: 10px; color: #3D3D3D">Email</td><td style="border-top: 1px solid #CCC; border-left: 1px solid #CCC; padding: 10px; color: #3D3D3D">{{ $mail_data['email'] }}</td></tr>
                          <tr valign="top"><td style="border-top: 1px solid #CCC; border-left: 1px solid #CCC; padding: 10px; color: #3D3D3D">Phone</td><td style="border-top: 1px solid #CCC; border-left: 1px solid #CCC; padding: 10px; color: #3D3D3D">{{ $mail_data['phone'] }}</td></tr>
                          <tr valign="top"><td style="border-top: 1px solid #CCC; border-left: 1px solid #CCC; padding: 10px; color: #3D3D3D">Product</td><td style="border-top: 1px solid #CCC; border-left: 1px solid #CCC; padding: 10px; color: #3D3D3D">{{ $mail_data['product_name'] }}</td></tr>
                          <tr valign="top"><td style="border-top: 1px solid #CCC; border-left: 1px solid #CCC; padding: 10px; color: #3D3D3D">Message</td><td style="border-top: 1px solid #CCC; border-left: 1px solid #CCC; padding: 10px; color: #3D3D3D"><p>{{ $mail_data['query'] }}</p>
</td></tr></tbody>
</table>
                  </div>
                  
                  
               </div>
            </div>
      </center>
   </body>
</html>