@extends('frontend.layouts.app')
@section('content')

  <section class="banner">
     <img src="{!! asset('assets/frontend/images/Career.png') !!}" class="img-fluid d-inline-block" alt="">
  </section>


  <!-- BREAD-CRUMBS STARTS -->
    <section class="breadcrumbs py-3">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="bread-crumbs">
              <ul>
                <li><a href="{{ route('home')}}"> Home</a></li>
                <li>/ Career</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
  <!-- BREAD-CRUMBS ENDS -->

  <div class="clearfix"></div>


  <!-- ABOUT-MAIN STARTS -->
    <section class="about-us sale-index career-page">
      <div class="container">
        <div class="row">
          <div class="col-md-3">
            <ul class="sidebar">
                <li><a href="{{ route('about-us')}}">About Uphaar</a></li>          
                <li><a class="active" href="{{ route('career') }}">Career</a></li> 
                <li><a href="{{ route('blog_page') }}">Blog</a></li> 
                <li><a href="{{ route('contact') }}">Contact</a></li> 
                <li><a href="/privacy-policy">Privacy Policy</a></li> 
                <li><a href="/term-condition">Terms & Conditions</a></li> 
            </ul>
          </div>
          <div class="col-md-9 back_nine">
          <div class="row">
          <div class="col-md-11 offset-md-1">
            <h2><span>Careers</span></h2>
            @php $i =0;  @endphp
            <div class="row">
              @foreach($careers as $career)
                @php $i++;  @endphp
                <div class="col-md-9">
                <div class="job-box">
                  <h4>{{ $career->title }}</h4>
                  <div class="job-dewscrp">
                    {!! $career->description !!} 
                  </div>
               </div>
             </div>
             <div class="col-md-3">
               <button class="btn" data-toggle="modal" data-target="#career">Apply Now</button>
             </div>
             <hr>
              @endforeach
            </div>
            @if($i == 0)
              <h6> No Opening </h6>
            @endif
          </div>
           
         </div>
        </div>
        </div>
      </div>
    </section>
  <!-- CONTACT-MAIN ENDS -->

<div class="modal fade" id="career">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header text-center">
            <h4 class="modal-title text-center">Upload Your Resume</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <form action="{{ route('upload-resume') }}" method="post" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="row">
                <div class="col-12 py-2">
                  <input type="text" name="name" placeholder="Name" onKeyPress="return ValidateAlpha(event);" minlength="3" maxlength="50" required="true" class="form-control">
                  @if($errors->has('name'))
                    <span class="text-danger">{{$errors->first('name')}}</span>
                  @endif
                </div>
                <div class="col-12 py-2">
                  <input type="email" name="email" placeholder="Email" class="form-control" id="txtEmail" maxlength="30" required="true" onkeyup="ValidateEmail();">
                  @if($errors->has('email'))
                      <span class="text-danger">{{$errors->first('email')}}</span>
                  @endif        
                </div>
                <div class="col-12 py-2">
                  <input type="text" maxlength="10" placeholder="Phone" minlength="10" name="phone" onkeypress="return isNumberKey(event);" class="form-control" required="true">
                  @if($errors->has('phone'))
                    <span class="text-danger">{{$errors->first('phone')}}</span>
                  @endif
                </div>
                <div class="col-12 py-2">
                   <select class="form-control" name="job" required="true">
                     <option>Select a Job</option>
                     @foreach($careers as $career)
                      <option value="$career->title">{{ $career->title }}</option>
                     @endforeach
                   </select>
                  @if($errors->has('job'))
                    <span class="text-danger">{{$errors->first('job')}}</span>
                  @endif

                </div>
                <div class="col-12 py-2">
                  <textarea rowspan="5" maxlength="500" placeholder="Message" name="message" class="form-control" required="true"></textarea>
                  @if($errors->has('message'))
                    <span class="text-danger">{{$errors->first('message')}}</span>
                  @endif
                </div>
                <div class="col-12 py-2">
                  <input type="file" name="resume" class="form-control" required="true" accept=".doc,.docx"><span style="    padding-left: 10px;padding-top: 3px;float: left;color: #9D1515;">Upload Your Resume</span>
                  @if($errors->has('resume'))
                      <span class="text-danger">{{$errors->first('resume')}}</span>
                  @endif        
                </div>
                <div class="col-12 py-3" style="padding-top: 0px !important;">
                  <div class="button text-center">
                    <button class="text-center btn" type="submit">Submit</button>
                  </div>
                </div>
              </div>
            </form>
        </div>
      </div>
    </div>
  </div>

@endsection  