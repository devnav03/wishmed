@extends('frontend.layouts.app')
@section('content')

  <section class="bredcrum">
    <div class="container">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>&nbsp;/&nbsp;About Us</li>
      </ul>
    </div>
  </section>
  <div class="clearfix"></div>

  <!-- ABOUT-MAIN STARTS -->
    <section class="about-us">
      <div class="container">
        <div class="row">
          <div class="col-md-12">  
            {!! $about->about !!}
          </div>
        </div>
      </div>
    </section>
  <!-- CONTACT-MAIN ENDS -->
@endsection  