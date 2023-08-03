@extends('frontend.layouts.app')
@section('content')

  <section class="bredcrum">
    <div class="container">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>&nbsp;/&nbsp;<a href="{{ route('home') }}">Blogs</a></li>
        <li>&nbsp;/&nbsp;{{ $blog->title }}</li>
      </ul>
    </div>
  </section>
  <div class="clearfix"></div>

  <!-- ABOUT-MAIN STARTS -->
    <section class="blog-details">
      <div class="container">
        <div class="row">
          <div class="col-md-8 top40">  
            <h1 class="bl_title">{{ $blog->title }}</h1>
            <img class="img-fluid" src="{!! asset($blog->image) !!}" alt="">
            <a href="{{ route('blog-category', $BlogCategory->url) }}" style="background: #009bdf; color: #fff; padding: 6px 20px; font-weight: 500; border-radius: 16px; margin-top: -10px; float: left; margin-bottom: 10px;">{{ $BlogCategory->name }}</a>
            <div class="clearfix"></div> 
            {!! $blog->description !!}
          </div>
          <div class="col-md-4 top40">  
          <h3 class="lt_title">Latest Blogs</h3> 
          @foreach($latest_blogs as $latest_blog)
          <div class="row">
            <div class="col-md-4" style="padding-right: 0px;">
              <a href="{{ route('blogs-details', $latest_blog->url) }}"><img class="img-fluid" src="{!! asset($latest_blog->image) !!}" alt=""></a>
            </div>
            <div class="col-md-8">
              <h4><a href="{{ route('blogs-details', $latest_blog->url) }}">{{ $latest_blog->title }}</a></h4>
            </div>
          </div>
          @endforeach
          </div>
        </div>
      </div>
    </section>
  <!-- CONTACT-MAIN ENDS -->
@endsection  