@extends('frontend.layouts.app')
@section('content')
<section class="bredcrum">
  <div class="container">
    <ul>
      <li><a href="{{ route('home') }}">Home</a></li>
      <li>&nbsp;/ &nbsp;Login</li>
    </ul>
  </div>
</section>

<section class="sign_up signing">
  <div class="container">
    <div class="row">
      <div class="col-md-4 offset-md-4">
        <h1>Login</h1>
        <div class="sign_box">
        <h5>Log In Your Account</h5>
        <div class="row">
        <div class="col-md-12">
              @if(session()->has('failed_login'))
                <p style="background: rgb(214 20 58);color: #fff;padding: 5px 10px;margin-bottom: 15px; border-radius: 9px;margin-top: 0px;">Invalid login credentials</p>
              @endif
          <form action="{{ route('login') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
            <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Email or Mobile" autocomplete="off" required="true">
            <div class="username-require"></div> 
            <div class="username-not-valid"></div>
            
            @if($errors->has('username'))
                <span class="text-danger">{{$errors->first('username')}}</span>
            @endif
            </div>
            <div class="form-group">
            <input type="password" name="password" value="{{ old('password') }}" placeholder="Password" autocomplete="off" required="true">
            @if($errors->has('password'))
                <span class="text-danger">{{$errors->first('password')}}</span>
            @endif
            </div>
            <div class="row">
              <div class="col-md-6">
                <input type="submit" value="Login">
              </div>
              <div class="col-md-6">
                <a href="{{ route('forgot.password') }}">Forgot Password?</a>
              </div>
            </div>
          </form>
        </div>
        </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- <section class="subscribe_index">
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
</section> -->



@endsection