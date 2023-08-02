@extends('frontend.layouts.app')
@section('content')
<section class="bredcrum">
    <div class="container">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>&nbsp;/&nbsp;<a href="log-in">Login</a></li>
        <li>&nbsp;/&nbsp;Forgot Password</li>
      </ul>
    </div>
  </section>


    <section class="forgot-password">
      <div class="container">
        <div class="row">
          <div class="col-xl-8 col-lg-8 col-md-8 col-12 offset-xl-2 offset-lg-2 mt-md-0 mt-4">
            <div class="txt-box">
                <div class="account-orders">
                <form action="{{ route('forgot_password.check_email') }}" class="profile-change-password" method="post">
                  {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xl-8 my-2">
                              @if(session()->has('failure_email'))
                                <p style="text-align: left; font-size: 15px; color: #f00;">Hey, looks like we don't have your email in our database</p>  
                              @endif
                              @if(session()->has('success_forgot'))
                               <p style="text-align: left; font-size: 15px;color: green;">We sent the reset password link to your registerd email id. Kindly check your email.</p>  
                              @endif
                                <label class="text-capitalize" for="old_password">Recover Your Account</label>
                                <input class="form-control" type="email" placeholder="Enter Email" required="true" name="email">
                                @if ($errors->has('email'))
                                  <span class="text-danger">{{$errors->first('email')}}</span>
                                @endif
                            </div>
                            <div class="col-xl-8">
                                <button class="btn text-uppercase" type="submit">Submit</button>
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
  <!-- MY ORDERS ENDS -->
  @endsection  