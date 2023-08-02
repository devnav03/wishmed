@extends('frontend.layouts.app')
@section('content')
@php
    $route  = \Route::currentRouteName();    
@endphp
  <section class="bredcrum">
    <div class="container">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>&nbsp;/&nbsp;Profile</li>
      </ul>
    </div>
  </section>

  <div class="clearfix"></div>

  <section class="profile-main">
    <div class="container">
      <div class="row">
        <div class="txt-box">
        <h3>Your Informations</h3>
        @if($route == "my-profile")
           <form action="{{ route('update-profile') }}" class="profile-change-password readonly_f" method="post">
          @if(session()->has('success_forgot'))
                  <div class="alert alert-success">
                    <button data-dismiss="alert" class="close">
                        ×
                    </button>
                    <i class="fa fa-check-circle"></i> &nbsp;
                    Profile Successfully Updated
                </div>
                @endif
          {{ csrf_field() }}
        <div class="txt-fields">
          <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-12 my-4">
              <div class="image-box text-center">
                @if($user->profile_image)
                <img class="img-fluid mx-auto d-block" src="{!! asset($user->profile_image) !!}" alt="">
                @else
                <img class="img-fluid mx-auto d-block" src="{!! asset('assets/frontend/images/user_blank.png') !!}" alt="">
                @endif
              </div>
            </div>
            <div class="col-xl col-lg col-md col-12 mb-md-3 mt-3 mb-2">
              <div class="form-group mb-4">
                <label class="mb-1 text-capitalize">Name:</label>
                <input type="text" value="{{ $user->name }}" name="name" required="true" class="form-control" readonly>
                @if ($errors->has('name'))
                  <span class="text-danger">{{$errors->first('name')}}</span>
                @endif
              </div>
              @if($user->mobile)
              <div class="form-group">
                <label class="mb-1 text-capitalize">contact:</label>
                <input type="text" class="form-control" value="{{ $user->mobile }}" type="number" placeholder="Mobile" required="true" name="mobile" readonly>
                @if ($errors->has('mobile'))
                  <span class="text-danger">{{$errors->first('mobile')}}</span>
                @endif
              </div>
              @endif
              @if($user->date_of_birth)
              <div class="form-group">
                <label class="mb-1 text-capitalize">date of birth:</label>
                <input type="date" value="{{ $user->date_of_birth }}" required="true" name="date_of_birth" class="form-control" readonly>
              </div>
              @endif
            </div>
            <div class="col-xl col-lg col-md col-12 mt-md-3 mt-0 mb-3">
              <div class="form-group">
                <label class="mb-1 text-capitalize">email:</label>
                <input class="form-control" value="{{ $user->email }}" type="email" placeholder="Email" required="true" name="email" readonly="">
                @if($errors->has('email'))
                  <span class="text-danger">{{$errors->first('email')}}</span>
                @endif
              </div>
              @if($user->gender)
              <div class="form-group">
                <label class="mb-1 text-capitalize">gender:</label>
                <input type="text" value="{{ $user->gender }}" name="gender" required="true" class="form-control" readonly>
                @if($errors->has('gender'))
                  <span class="text-danger">{{$errors->first('gender')}}</span>
                @endif
              </div>
              @endif
            </div>

            <div class="col-12 text-center">
              <a href="{{ route('edit.my-profile')}}" class="btn gray text-uppercase">Edit Detail</a>
              <a class="btn gold text-uppercase" href="{{ route('home')}}">continue shopping</a>
            </div>
          </div>
        </div>
        </form>

        @else
        <form action="{{ route('update-profile') }}" class="profile-change-password" method="post" enctype="multipart/form-data">
          @if(session()->has('success_forgot'))
                  <div class="alert alert-success">
                    <button data-dismiss="alert" class="close">
                        ×
                    </button>
                    <i class="fa fa-check-circle"></i> &nbsp;
                    Profile Successfully Updated
                </div>
                @endif
          {{ csrf_field() }}
        <div class="txt-fields">
          <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-12 my-4">
              <div class="image-box text-center">
                @if($user->profile_image)
                <img class="img-fluid mx-auto d-block" src="{!! asset($user->profile_image) !!}" alt="">
                @else
                <img class="img-fluid mx-auto d-block" src="{!! asset('assets/frontend/images/user_blank.png') !!}" alt="">
                @endif
                <input class="file mt-3" name="image" type="file">
              </div>
            </div>
            <div class="col-xl col-lg col-md col-12 mb-md-3 mt-3 mb-2">
              <div class="form-group mb-4">
                <label class="mb-1 text-capitalize">Name:</label>
                <input type="text" value="{{ $user->name }}" name="name" required="true" class="form-control">
                @if($errors->has('name'))
                  <span class="text-danger">{{$errors->first('name')}}</span>
                @endif
              </div>
              <div class="form-group">
                <label class="mb-1 text-capitalize">contact:</label>
                <input type="text" class="form-control" value="{{ $user->mobile }}" type="number" placeholder="Mobile" required="true" name="mobile">
                @if ($errors->has('mobile'))
                  <span class="text-danger">{{$errors->first('mobile')}}</span>
                @endif
              </div>
              <div class="form-group">
                <label class="mb-1 text-capitalize">date of birth:</label>
                <input type="date" value="{{ $user->date_of_birth }}" required="true" name="date_of_birth" class="form-control">
              </div>
            </div>
            <div class="col-xl col-lg col-md col-12 mt-md-3 mt-0 mb-3">
              <div class="form-group">
                <label class="mb-1 text-capitalize">email:</label>
                <input class="form-control" value="{{ $user->email }}" type="email" placeholder="Email" required="true" name="email" readonly="">
                @if($errors->has('email'))
                  <span class="text-danger">{{$errors->first('email')}}</span>
                @endif
              </div>
              <div class="form-group">
                <label class="mb-1 text-capitalize">gender:</label>
                <select class="form-control" name="gender" required="true" id="gender">
                  <option disabled="true">Gender</option>
                  <option value="Male" @if($user->gender == 'Male') selected @endif>Male</option>
                  <option value="Female" @if($user->gender == 'Female') selected @endif>Female</option>
                  <option value="Other" @if($user->gender == 'Other') selected @endif>Other</option>
                </select>
                @if($errors->has('gender'))
                  <span class="text-danger">{{$errors->first('gender')}}</span>
                @endif
              </div>
            </div>

            <div class="col-12 text-center">
              <button type="submit" class="btn gray text-uppercase">save changes</button>
              <a class="btn gold text-uppercase" href="{{ route('home')}}">continue shopping</a>
            </div>
          </div>
        </div>
        </form>
        @endif
      </div>
      <p style="text-align: center;float: left; width: 100%;margin-top: 25px;font-family: 'Roboto', sans-serif;font-size: 16px;">For order related information, please check your <a href="{!! route('my-orders') !!}" style="font-size: 16px;border-bottom: 1px solid #000;padding-bottom: 3px;">Order History</a></p>
    </div>
  </section>

  @endsection  