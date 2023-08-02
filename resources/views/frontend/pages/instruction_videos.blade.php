@extends('frontend.layouts.app')
@section('content')
  
   <section class="bredcrum">
    <div class="container">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>&nbsp;/ &nbsp;Instruction Videos & Presentations</li>
      </ul>
    </div>
  </section>
  <div class="clearfix"></div>

<div id="add-cart"></div>
  <!-- PRODUCT-LISTING-PANEL STARTS -->
    <section class="instruction_videos">
      <div class="container">
        <div class="row">
          <div class="col-md-10 offset-md-1">
          <h2>Instruction Videos & Presentations</h2>
          <div class="row">

            @if($instruction_videos)
            @foreach($instruction_videos as $instruction_video)
              <div class="col-md-8">
                <h3>{{ $instruction_video->name }}</h3>
                @if($instruction_video->iframe_code)
                <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $instruction_video->iframe_code }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                
                       @else

        <video width="100%" controls style="margin-top: 30px;">
                                           <source src="{{ $instruction_video->video }}" type="video/mp4">
                                           <source src="{{ $instruction_video->video }}" type="video/ogg">
                                           Your browser does not support HTML video.
         </video>
       
       @endif


              </div>
            @endforeach
            @endif
          </div>
          </div>
      </div>
    </div>
    </section>


@endsection