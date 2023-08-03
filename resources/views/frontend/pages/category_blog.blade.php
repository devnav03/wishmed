@extends('frontend.layouts.app')
@section('content')

    <section class="bredcrum">
	    <div class="container">
	        <ul>
		        <li><a href="{{ route('home') }}">Home</a></li>
		        <li>&nbsp;/&nbsp;{{ $BlogCategory->name }}</li>
	        </ul>
	    </div>
    </section>

<section class="blog-section" style="margin-bottom: 20px;">
	<div class="container">
		<div class="row">
			<div class="col-xl-6 col-lg-6 offset-lg-3 offset-xl-3">
				<div class="section-title text-center mb-65">
					<h2>{{ $BlogCategory->name }}</h2>
				</div>
			</div>
		</div>
	<div class="row">

    @if($blogs)
    @foreach($blogs as $blog)
	    <div class="col-xl-4 col-lg-4 col-md-6">
			<div class="blog-wrapper mb-30">
				<div class="blog-img pos-rel">
				<a href="{{ route('blogs-details', $blog->url) }}">
					<img decoding="async" src="{!! asset($blog->image) !!}" alt="blog_small_img1"></a><span class="blog-tag color-1">{{ $blog->name }}</span></div>
			<div class="blog-text">
				<div class="blog-meta">
					<span><i class="fa-solid fa-calendar"></i>
					<a href="{{ route('blogs-details', $blog->url) }}">{{ date('d M Y', strtotime($blog->created_at)) }}</a></span>
				</div>
				<h4><a href="{{ route('blogs-details', $blog->url) }}">{{ $blog->title }}</a></h4>
				<p>{!! Str::limit($blog->meta_description, 80) !!}</p>
				<div class="b-button gray-b-button">
					<a href="{{ route('blogs-details', $blog->url) }}">read more <i class="fa-solid fa-plus"></i></a>
				</div>
			
			</div>
			
			</div>
		</div>
	@endforeach	
    @endif

		
		</div>
		</div>		
	</section>

@endsection