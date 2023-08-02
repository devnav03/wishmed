@extends('frontend.layouts.app')
@section('content')
<section class="bredcrum">
  <div class="container-fluid">
    <ul>
      <li><a href="{{ route('home') }}">Home</a></li>
      <li>&nbsp;/ &nbsp;Signup</li>
    </ul>
  </div>
</section>

<section class="sign_up signing">
  <div class="container">
    <div class="row">
      <div class="col-md-4 offset-md-4">
        <h1>Sign Up</h1>
        <div class="sign_box">
          <h5>Register An Account</h5>
            @if(session()->has('message_reg'))
                <p style="display: block;color: #008000;font-size: 15px;margin-top: 0px;">Your Account has been created with Puka Creations.<br> We have sent a confirmation link on your registered email Kindly check & Confirm. <!-- If you do not receive the confirmation message within a few minutes of signing up, please check your spam E-mail folder just in case the confirmation email got delivered there instead of your inbox. --></p>
            @endif
          <form action="{{ route('save-user') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
            <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" required="true">
            @if($errors->has('first_name'))
                <span class="text-danger">{{$errors->first('first_name')}}</span>
            @endif
            </div>
            <div class="form-group">
            <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required="true">
            @if($errors->has('last_name'))
                <span class="text-danger">{{$errors->first('last_name')}}</span>
            @endif
            </div>
            <div class="form-group">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required="true">
            @if($errors->has('email'))
                <span class="text-danger">{{$errors->first('email')}}</span>
            @endif
            </div>
            <div class="form-group">
            <input type="number" name="mobile" value="{{ old('mobile') }}" placeholder="Mobile" required="true">
            @if($errors->has('mobile'))
                <span class="text-danger">{{$errors->first('mobile')}}</span>
            @endif
            </div>
            <div class="form-group">
            <input type="password" name="password" value="{{ old('password') }}" placeholder="Password" required="true">
            @if($errors->has('password'))
                <span class="text-danger">{{$errors->first('password')}}</span>
            @endif
            </div>
            <input type="submit" value="Sign Up">
          </form>
          <p>Have an Account? <a href="/log-in">Login</a></p>
        
        </div>
      </div>
    </div>
  </div>
</section>

<section class="subscribe_index">
<div class="ps-newsletter">
          <div class="container">
            <form class="ps-form--newsletter" id="mysubscribe">
                <div class="row">
                    <div class="col-xl-5 col-lg-12 col-md-12 col-sm-12 col-12 ">
                        <div class="ps-form__left">
                            <h3>Newsletter</h3>
                            <p>Subcribe to get information about products and coupons</p>
                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-12 col-md-12 col-sm-12 col-12 ">
                        <div class="ps-form__right">
                          <div id="already_subs" style="color: #f00;left: 13px; width: 100%; position: absolute; bottom: -30px;"></div>
                          <div id="email_subs" style="color: #008000; left: 13px; width: 100%; position: absolute; bottom: -30px;"></div>
                            <div class="form-group--nest">
                                <input type="email"class="form-control" required="true" name="subscribe" placeholder="Email address">
                                <div id="valid_email" style="left: 13px;color: #f00; width: 100%; position: absolute; bottom: -30px;"></div>
                                <button type="submit" class="ps-btn ajax_subscribe">Subscribe</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>


@endsection