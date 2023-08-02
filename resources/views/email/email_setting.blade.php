<html>
  <head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body style=" margin: 0;
    background: #FEFEFE;
    color: #585858;
    ">
    <!-- Preivew text -->
    <span class="preheader" style="display: none !important; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0;border-collapse: collapse;border: 0px;"></span>
    <!-- Carpool logo -->
    <table align="center" border="0" cellspacing="0" cellpadding="0" style="  font-size: 15px;
      line-height: 23px;
      max-width: 500px;
      min-width: 460px;
      text-align: center;
      ">
      <tbody>
        <tr>
          <td style=" font-family: -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
            vertical-align: top;
            border: none !important;
            ">
            <img src="{{route('home')}}/assets/frontend/images/logo.png" class="carpool_logo" width="300" style="  display: block;
              margin: 0 auto;
              margin: 30px auto;">
          </td>
        </tr>
        <!-- Header -->
        <tr>
          <td class="sectionlike imageless_section" style=" font-family: -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
            vertical-align: top;
            border: none !important;
            background: #C9F9E9;
            padding-bottom: 10px;
            padding-bottom: 20px;"></td>
        </tr>
        <!-- Content -->
        <tr>
          <td class="section" style=" font-family: -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
            vertical-align: top;
            border: none !important;
            background: #C9F9E9;
            padding: 0px 20px 20px 20px;
            ">
            <table border="0" cellspacing="0" cellpadding="0" class="section_content" style=" font-size: 15px;
              line-height: 23px;
              max-width: 500px;
              min-width: 460px;
              text-align: center;
              width: 100%;
              background: #fff;
              ">
              <tbody>
                <tr>
                  <td class="section_content_padded" style="  font-family: -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
                    vertical-align: top;
                    border: none !important;
                    padding: 0 35px 40px;">
                    <h1 style=" font-size: 20px;
                      font-weight: 500;
                      margin-top: 40px;
                      margin-bottom: 0;
                      ">Hi {{ $name }},</h1>
                    <p class="near_title last" style="margin-top: 10px;margin-bottom: 0;">Your OTP for email settings is <b>{{ $otp }}</b> from this IP address {{ $ip }}.</p>
             
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <!-- Legal footer -->
        <tr>
          <td style=" font-family: -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
            vertical-align: top;
            border: none !important;
            ">
            <p class="footer_legal" style=" padding: 20px 0 40px;
              margin: 0;
              font-size: 12px;
              color: #A5A5A5;
              line-height: 1.5;
              ">
              All Rights Reserved © Copyright 2022 Puka Creations.
            </p>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>