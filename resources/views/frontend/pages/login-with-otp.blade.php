@extends('frontend.layouts.app')
@section('content')
<section class="bredcrum">
  <div class="container-fluid">
    <ul>
      <li><a href="{{ route('home') }}">Home</a></li>
      <li><a href="/sign-in">Login</a></li>
      <li>Enter OTP</li>
    </ul>
  </div>
</section>

<section class="page_heading">
  <div class="container-fluid">
    <h1>Enter OTP</h1>
    <h6><a href="{{ route('home') }}">Back to Homepage</a></h6>
    <div class="page_line"></div>
  </div>
</section>
<section class="sign_up">
  <div class="container">
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <div class="sign_box">
        <div class="row">
        <div class="col-md-8 offset-md-2">
          <h4>OTP Verification</h4>
@php
$otp_media = \Session::get('otp_media');
$sent_on   = \Session::get('sent_on');
@endphp
          <h6>We have sent you an OTP on your {{ $otp_media }} {{ $sent_on }}</h6>
          @if(session()->has('enter_your_otp'))
          <p style="text-align: center;color: #f00;">Please enter your OTP</p>
          @endif
          @if(session()->has('OTP_not_match'))
          <p style="text-align: center;color: #f00;">OTP not match</p>
          @endif
          <form action="{{ route('getLoginOtp') }}" method="post" class="text-center">
            {{ csrf_field() }}
            <input type="number" name="number" class="otp_input" maxlength="999999" autocomplete="off" required="true"><br>
            <a class="d-inline-block" href="{{ route('resend') }}">Resend</a>
            
            @if(session()->has('resend_otp'))
              <p class="alert alert-primary offer_apply" role="alert" style="background: transparent;color: green; border: 0px; padding: 0px;">OTP Resend successfully</p>
            @endif

            <h6 style="margin-top: 20px;">You can also use an existing OTP sent to you in the past 15 minutes</h6>
            <button type="submit" style="margin-top: 20px;">Done</button>
          </form>
        </div>
       
        </div>
        </div>
      </div>
    </div>
  </div>
</section>


@endsection