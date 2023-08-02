@extends('frontend.layouts.app')
@section('content')
<section class="bredcrum">
  <div class="container-fluid">
    <ul>
      <li><a href="{{ route('home') }}">Home</a></li>
      <li>Create Account</li>
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
<section class="sign_up_register">
  <div class="container">
    <form action="{{ route('save-user') }}" method="post">
      {{ csrf_field() }}
    <div class="row">
      <div class="col-md-12 bottom_border">
        <div class="row">
        <div class="col-md-4">
          <img src="{!! asset('assets/frontend/images/sign_up_right.jpg') !!}" class="img-fluid d-inline-block" alt="">
        </div>
        <div class="col-md-8">

            <div class="row">
              <div class="col-md-6">
                  <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" autocomplete="off" required="true">
                  @if($errors->has('first_name'))
                    <span class="text-danger">{{$errors->first('first_name')}}</span>
                  @endif
              </div>
              <div class="col-md-6">
                  <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" autocomplete="off" required="true">
                  @if($errors->has('last_name'))
                    <span class="text-danger">{{$errors->first('last_name')}}</span>
                  @endif
              </div>
              <div class="col-md-12">
                  <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" autocomplete="off" required="true">
                  @if($errors->has('email'))
                    <span class="text-danger">{{$errors->first('email')}}</span>
                  @endif
              </div>
              <div class="col-md-6">
                  <input type="password" name="password" value="{{ old('password') }}" placeholder="Password" autocomplete="off" required="true">
                  @if($errors->has('password'))
                    <span class="text-danger">{{$errors->first('password')}}</span>
                  @endif
              </div>
              <div class="col-md-6">
                  <input type="number" name="mobile" @if($mobile) value="{{ $mobile }}" readonly="" @endif value="{{ old('mobile') }}" placeholder="Mobile" autocomplete="off" required="true">
                  @if($errors->has('mobile'))
                    <span class="text-danger">{{$errors->first('mobile')}}</span>
                  @endif
              </div>

              <div class="col-md-6">
                  <h5>Gender</h5> 
                  <div class="btn-group">
                      <a href="#" class="male-gender active_gender" onclick="SelectMale()">Male</a>
                      <a href="#" class="female-gender" onclick="SelectFemale()">Female</a>
                  </div>
                  <input type="hidden" name="gender" id="selectgender" value="Male">
                  @if($errors->has('gender'))
                    <span class="text-danger">{{$errors->first('gender')}}</span>
                  @endif
              </div>
              <div class="col-md-6">
                  <input type="checkbox" name="term" required="true">
                  <p>I want to receive order updates on Whats app Allow us to send updates via WhatsApp for order related communications. We do not use WhatsApp for promotional purposes</p>
              </div>
            </div>
        </div>
        </div>
      
      </div>
      <div class="text-center" style="width: 100%;">
        <button class="bottom_submit" type="submit">Sign Up</button>
        <p class="have_account">Have an Account? <a href="#">LOG IN</a></p>
      </div>  
      @if($id)
      <input type="hidden" name="aprove_id" value="{{ $id }}">
      @else
      <input type="hidden" name="aprove_id" value="0">
      @endif    
          </form>
    </div>
  </div>
</section>


@endsection