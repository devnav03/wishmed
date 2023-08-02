@extends('frontend.layouts.app')
@section('content')

  <section class="bredcrum">
    <div class="container">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>&nbsp;/&nbsp;Contact Us</li>
      </ul>
    </div>
  </section>
  <div class="clearfix"></div>


  <!-- CONTACT-MAIN STARTS -->
    <section class="contact-main">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            @if(session()->has('enquiry_sub'))
            <li class="alert alert-success" style="list-style: none; margin-top: 25px;">Thank You, your enquiry has been received and we will be contacting you shortly to follow-up.</li>
            @endif  
            <h4>We Love to hear From You</h4>
            <div class="row">
            <div class="col-md-7">
              <form action="{{ route('contact-enquiry') }}" method="post">
                {{ csrf_field() }}

              <div class="row">
                <div class="col-md-6">
                  <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" required="true" placeholder="First Name">
                  @if($errors->has('first_name'))
                        <span class="text-danger">{{$errors->first('first_name')}}</span>
                  @endif
                  <input type="hidden" name="two" value="{{ $two }}">
                  <input type="hidden" name="three" value="{{ $three }}">
                </div>
                <div class="col-md-6">
                  <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" required="true" placeholder="Last Name">
                  @if($errors->has('last_name'))
                        <span class="text-danger">{{$errors->first('last_name')}}</span>
                  @endif
                </div>
                <div class="col-md-6">
                  <input type="email" name="email" value="{{ old('email') }}" class="form-control" required="true" placeholder="Email">
                  @if($errors->has('email'))
                        <span class="text-danger">{{$errors->first('email')}}</span>
                  @endif
                </div>
                <div class="col-md-6">
                  <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" required="true" placeholder="Phone">
                  @if($errors->has('phone'))
                        <span class="text-danger">{{$errors->first('phone')}}</span>
                  @endif
                </div>
                <div class="col-md-12">
                  <input type="text" name="subject" value="{{ old('subject') }}" class="form-control" required="true" placeholder="Subject">
                  @if($errors->has('subject'))
                        <span class="text-danger">{{$errors->first('subject')}}</span>
                  @endif
                </div>
                <div class="col-md-12">
                  <textarea name="message" class="form-control" required="true" placeholder="Message">{{ old('message') }}</textarea>
                  @if($errors->has('message'))
                        <span class="text-danger">{{$errors->first('message')}}</span>
                  @endif
                </div>
                <div class="col-md-12">
                <p style="float: left; margin-top: 20px; margin-right: 10px;color: #f00;">Fill Captcha</p>
                <p style="float: left; margin-top: 20px; margin-right: 10px;">{{ $three }} + {{ $two }} = </p> <input style="float: left; width: 58px; text-align: center; margin-top: 10px;" required="true" type="number" name="rec_value"> 
                
               @if(session()->has('recap_sub'))
                   <span class="text-danger" style="margin-top: -10px; width: 100%;float: left;">recaptcha not valid </span>
               @endif
               </div>
                <div class="col-12">
                  <div class="buton my-3">
                    <button class="send-message" type="submit">Submit</button>
                  </div>
                </div>
              </div>
            </form>
            </div>
            <div class="col-md-5">
            {!! $contact->contact !!}
            </div>
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