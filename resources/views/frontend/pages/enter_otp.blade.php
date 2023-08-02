@extends('frontend.layouts.app')
@section('content')
<section class="bredcrum">
  <div class="container-fluid">
    <ul>
      <li><a href="{{ route('home') }}">Home</a></li>
      <li><a href="/sign-up">Create Account</a></li>
      <li>Enter OTP</li>
    </ul>
  </div>
</section>

<section class="page_heading">
  <div class="container-fluid">
    <h1>Sign Up</h1>
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
          <h6>We have sent you an OTP on your mobile number {{ $mobile }}</h6>
       
          <form action="{{ route('submit-details') }}" method="post" class="text-center">
            {{ csrf_field() }}
            <input type="hidden" name="reg_id" value="{{ $id }}">
            <input type="number" name="otp" class="otp_input" maxlength="999999" autocomplete="off" required="true"><br>
            <a value="{{ $id }}" class="d-inline-block" onclick="ResendOtp(this.value)">Resend</a>
            <h6>You can also use an existing OTP sent to you in the past 15 minutes</h6>
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