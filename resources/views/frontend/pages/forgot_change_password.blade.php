@extends('frontend.layouts.app')
@section('content')


 <!-- MY ORDERS STARTS -->
    <section class="forgot-password">
      <div class="container">
        <div class="row">
          <div class="col-xl-8 col-lg-8 col-md-8 col-12 offset-xl-2 offset-lg-2 mt-md-0 mt-4">
            <div class="row">
                  <div class="col-sm-12">
                      @if (session('failure'))
                      <div class="alert alert-danger">
                          {{ session('failure') }}
                      </div>
                      @endif

                      @if (session('success'))
                      <div class="alert alert-success">
                          {{ session('success') }}
                      </div>
                      @endif

                      @if ($errors->any())
                          <div class="alert alert-danger">
                              <ul>
                                  @foreach ($errors->all() as $error)
                                      <li>{{ $error }}</li>
                                  @endforeach
                              </ul>
                          </div>
                      @endif
                  </div>
              </div>
            <div class="txt-box">
                <div class="account-orders">
                  {!! Form::open(array('method' => 'POST', 'route' => array('change_password_forgot'), 'class' => 'profile-change-password')) !!}
                  {{ csrf_field() }}
                  <input type="hidden" name="user_id" value="{{ $user_id }}">
                        <div class="row">
                            <div class="col-xl-8 my-2">
                              <label style="width: 100%; font-size: 18px; margin-bottom: 15px;">Hey {{ $user->name }}, Reset Your Password</label>

                                <label class="text-capitalize" for="password" style="font-size: 16px;">Enter New Password</label>
                                <input class="form-control" type="password" name="new_password" placeholder="New Password" required="true" style="border: 1px solid #212529; height: 45px;">
                                @if ($errors->has('new_password'))
                                  <span class="text-danger">{{$errors->first('new_password')}}</span>
                                @endif
                            </div>
                            <div class="col-xl-8 my-2">
                                <label class="text-capitalize" for="confirm_password" style="font-size: 16px;">Confirm New Password</label>
                                <input class="form-control" name="confirm_password" type="password" placeholder="Confirm New Password" required="true" style="border: 1px solid #212529; height: 45px;">
                                @if ($errors->has('confirm_password'))
                                  <span class="text-danger">{{$errors->first('confirm_password')}}</span>
                                @endif
                            </div>
                            <div class="col-xl-8 my-4">
                                <button class="btn text-uppercase" type="submit" style="padding: 9px 40px;">Submit</button>
                            </div>
                        </div>
                    {!! form::close() !!}
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
  <!-- MY ORDERS ENDS -->
  @endsection  