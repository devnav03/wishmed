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

      <div class="row"> 
      <div class="col-md-4">
        <h4>Office Address</h4>
        <p>Unit 2, 22-24 Steel Street, Blacktown, NSW 2148</p>
        <h4>Brisbane office:</h4>
        <p>Unit 11, 42 Smith Street, Capalaba 4157</p>
        <div class="text-center"><div class="is-divider divider clearfix"></div></div>
        <p><strong>Email: </strong> <a href="mailto:sales@wishmed.com.au">sales@wishmed.com.au</a></p>
        <p><strong>Phone no 1:</strong> <a href="tel:+61286780983"> +61 2 8678 0983</a></p>
        <p><strong>Phone no 2:&nbsp;</strong><a href="tel:+61286780993">+61 2 8678 0993&nbsp;</a></p>
        <p><strong>Fax:</strong> <a href="tel:+612 96728656">+61 2 9672 8656</a></p>
      </div>

      <div class="col-md-4">
      <h4>Contact details</h4>
      <p><strong>General Sales Enquiry: </strong><a href="mailto:sales@wishmed.com.au"></a> sales@wishmed.com.au</p>
      <p><strong>Logistic Enquiries: </strong><a href="mailto:support@wishmed.com.au"></a> support@wishmed.com.au</p>
      <p><strong>Accounts Enquiries: </strong><a href="mailto:accounts@wishmed.com.au"></a> accounts@wishmed.com.au</p>
      <p><strong>Purchase Enquiries: </strong><a href="mailto:purchase@wishmed.com.au"></a> purchase@wishmed.com.au</p>
      <div class="text-center"><div class="is-divider divider clearfix"></div></div>

      <p><span style="text-decoration: underline"><strong>Laboratory Enquiries</strong></span></p>
      <p><strong>Rajesh &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;</strong><a href="tel:+61420839919">+61 420 839 919</a></p>
      <p><span style="text-decoration: underline"><strong>Dental Enquiries</strong></span></p>
      <p><strong>Danny</strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<a href="tel:+61426475114">+61 426 475 114</a></p>
      <p><span style="text-decoration: underline"><strong>Medical Enquiries</strong></span></p>
      <p><strong>Ritu &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;</strong><a href="tel:+61420830178">+61 420 839 178</a></p>
      </div>

      <div class="col-md-4">
      <h4>State Wide Sales Contact</h4>
      <p style="margin-bottom: 0px;"><b style="font-weight: 500;">NSW and ACT</b></p> 
      <p><strong>Lavanya:</strong> <a href="tel:+61 421272230"> +61 421 272 230</a></p>
      <br>
      <p style="margin-bottom: 0px;"><b style="font-weight: 500;">Queensland and Northern Territorry</b></p> 
      <p><strong>Rajesh:</strong> <a href="tel:+61 420839742"> +61 420 839 742</a></p>
      <br>
      <p style="margin-bottom: 0px;"><b style="font-weight: 500;">Victoria and South Australia</b></p> 
      <p><strong>Varun:</strong> <a href="tel:+61 412458217"> +61 412 458 217</a></p>
      </div>

</div>
 <div class="row">
      <div class="col-md-3"></div>
      <div class="col-md-6">
            <h4>We Love to hear From You</h4>
            <div class="row"> 
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
                <div class="col-md-6">
                <p style="float: left; margin-top: 12px; margin-right: 10px;color: #f00;">Fill Captcha</p>
                <p style="float: left; margin-top: 12px; margin-right: 10px;">{{ $three }} + {{ $two }} = </p> <input style="float: left; width: 58px; text-align: center; margin-top: 10px;" required="true" type="number" name="rec_value"> 
                
               @if(session()->has('recap_sub'))
                   <span class="text-danger" style="margin-top: -10px; width: 100%;float: left;">recaptcha not valid </span>
               @endif
               </div>
                <div class="col-md-6">
                  <div class="buton my-3">
                    <button class="send-message" type="submit">Submit</button>
                  </div>
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

@endsection  