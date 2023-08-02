@extends('frontend.layouts.app')
@section('content')
<div class="clearfix"></div>
<section class="bredcrum">
<div class="container">
<ul>
<li><a href="{{ route('home')}}">Home</a></li>
<li>&nbsp;/&nbsp;Change Password</li>
</ul>
</div>
</section>


 <!-- MY ORDERS STARTS -->
    <section class="change_pasw">
      <div class="container">
        <div class="row">
          <div class="col-md-4 offset-md-4">
            <div class="txt-box">
            @if(session()->has('old_password_not_match'))
            <p style="color: #f00;">Old Password is not Match</p>
            @endif
            @if(session()->has('password_change'))
            <p style="color: green;">Your password has been changed successfully.</p>
            @endif
            
            <form action="{{ route('change-password.store') }}" class="profile-change-password" method="post">
                  {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xl-12">
                                <label class="text-capitalize" for="old_password">old password*</label>
                                <input id="old_password" class="form-control" type="password" placeholder="Enter Old Password" required="true" name="old_password">
                                @if ($errors->has('old_password'))
                                  <span class="text-danger">{{$errors->first('old_password')}}</span>
                                @endif
                            </div>
                            <div class="col-xl-12">
                                <label class="text-capitalize" for="name">new password*</label>
                                <input class="form-control" type="password" placeholder="Enter New Password" required="true" name="new_password">
                                @if ($errors->has('new_password'))
                                  <span class="text-danger">{{$errors->first('new_password')}}</span>
                                @endif
                            </div>
                            <div class="col-xl-12">
                                <label class="text-capitalize" for="name">confirm new password*</label>
                                <input class="form-control" type="password" placeholder="Confirm New Password" required="true" name="new_password_confirmation">
                                @if ($errors->has('new_password_confirmation'))
                                  <span class="text-danger">{{$errors->first('new_password_confirmation')}}</span>
                                @endif  
                            </div>
                            <div class="col-xl-12">
                                <button class="btn text-uppercase" style="background: #be0027; color: #fff;" type="submit">save changes</button>
                            </div>
                        </div>
                  </form>
              </div>
            </div>
          </div>
        </div>
      </section>
  <!-- MY ORDERS ENDS -->
  @endsection  