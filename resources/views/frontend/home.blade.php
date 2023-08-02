@extends('frontend.layouts.app')
@section('content')


<section class="hero-area">
	<div class="hero-slider">
		<div class="slider-active">
			<div class="single-slider slider-height d-flex align-items-center" data-background="{!! asset('assets/frontend/images/slider-hero-bg.jpg')  !!}" style="background-image: url(&quot;{!! asset('assets/frontend/images/slider-hero-bg.jpg')  !!}&quot;);"><div class="container">
				<div class="row">
				<div class="col-xl-5 col-lg-6">
					<div class="hero-text mt-90">
					    <div class="hero-slider-caption ">
						<span data-animation="fadeInUp" data-delay=".2s">covid -19 products</span>
						<h2 data-animation="fadeInUp" data-delay=".4s">Face Mask Thermometer</h2>
						<p data-animation="fadeInUp" data-delay=".6s">Quis autem vel eum iure reprehenin voluptate velit esse quam nihil molestiae conse</p></div>
					    <div class="hero-slider-btn">
							<a data-animation="fadeInUp" data-delay=".8s" href="#" class="c-btn">shop now <i class="fa-solid fa-plus"></i></a>
							 <div class="b-button" data-animation="fadeInUp" data-delay="1s" style="animation-delay: 1s;"><a href="#">hot collection <i class="fa-solid fa-plus"></i></a>
							 </div>
						</div>
					</div>
			    </div>
				<div class="col-xl-7 col-lg-6">
					<div class="slider-img d-none d-lg-block" data-animation="fadeInRight" data-delay=".8s"><img decoding="async" src="{!! asset('assets/frontend/images/slider-hero-img.png') !!}" alt="">
					</div>
				</div>
			</div>
			</div>
			</div>
		</div>
	</div>
</section>

 <section class="salfull-section">
	    <div class="container">
		    <div class="row">
				<div class="col-md-6">
					<div class="banner-img pos-rel">
						<a href="{{ route('categoryDetail', 'medical-supply') }}">
							<img decoding="async" src="{!! asset('assets/frontend/images/04-banner.JPG')  !!}" alt="banner1"></a>
						<div class="banner-text"><!-- <span>Medical Supply</span> -->
							<h2>Medical Supply</h2>
							<div class="b-button red-b-button">
								<a href="{{ route('categoryDetail', 'medical-supply') }}">Shop Now <i class="fa-solid fa-plus"></i></a>
							</div>
						</div>
					</div>
				</div>									
				<div class="col-md-6">
					<div class="banner-img pos-rel">
					<a href="{{ route('categoryDetail', 'dental-supply') }}">
						<img decoding="async" src="https://klbtheme.com/medibazar/wp-content/uploads/2020/09/05-banner.jpg" alt="banner1">
					</a>
					<div class="banner-text">
					<!-- 	<span>Surgery Lab</span> -->
						<h2>Dental Supply</h2>
						<div class="b-button red-b-button">
							<a href="{{ route('categoryDetail', 'dental-supply') }}">Shop Now <i class="fa-solid fa-plus"></i></a>
						</div>
						</div>
					</div>
				</div>		
		    </div>		
	    </div>
	</section>

<section class="sale-section">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="banner-img pos-rel">
					<a href="#">
						<img decoding="async" src="{!! asset('assets/frontend/images/01-banner.jpg') !!}" alt="banner1"></a>
						<div class="banner-text"><span>Super Sale</span>
							<h2>New Collection</h2>
						<div class="b-button red-b-button">
							<a href="#">Shop Now <i class="fa-solid fa-plus"></i></a>
						</div>
					</div>
				</div>
			</div>		
				
			<div class="col-md-4">
				<div class="banner-img pos-rel">
					<a href="#">
						<img decoding="async" src="{!! asset('assets/frontend/images/02-banner.jpg') !!}" alt="banner1"></a>
						<div class="banner-text"><span>Gun Covid -19</span>
							<h2>Temperature</h2>
							<div class="b-button red-b-button">
								<a href="#">Shop Now <i class="fa-solid fa-plus"></i></a>
						    </div>
					    </div>
				</div>
		    </div>		
									
			<div class="col-md-4">
				<div class="banner-img pos-rel">
				<a href="#">
					<img decoding="async" src="{!! asset('assets/frontend/images/03-banner.jpg') !!}" alt="banner1"></a>
				<div class="banner-text"><span>Pulse</span><h2>Oximeter</h2>
					<div class="b-button red-b-button">
						<a href="#">Shop Now <i class="fa-solid fa-plus"></i></a>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>			
</section>


<section class="elementor-section tab-section">
	<div class="container">
			<div class="product-area pb-50">
					<div class="tab-border mb-60">
						<div class="row">
						<div class="col-xl-7 col-lg-6">
							<div class="section-title mb-25">
								<h2>Best Selling Products</h2>
								<!-- <p>Sed ut perspiciatis unde omnis iste natus error</p> -->
							</div>
						</div>
						<div class="col-xl-5 col-lg-6">
							<div class="product-tab mb-25">
								<ul class="nav justify-content-start justify-content-lg-end product-nav" id="myTab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active show" data-toggle="tab" id="accessories-tab" href="#accessories" role="tab" aria-controls="medical-equipment" aria-selected="false"><i class="fa-solid fa-shield"></i> Medical Supply</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="medical-equipment-tab" data-toggle="tab" href="#medical-equipment" role="tab" aria-controls="medical-equipment" aria-selected="false"><i class="fa-solid fa-shield"></i> Dental Supply</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="popular-tab" data-toggle="tab" href="#popular" role="tab" aria-controls="popular" aria-selected="true"><i class="fa-solid fa-shield"></i> Lab Equipment</a>
									</li>
								</ul>
								</div>
							</div>
						</div>
					</div>
<div class="product-tab-content klb-product">
<div class="tab-content" id="myTabContent">
<div class="tab-pane fade active show" id="accessories" role="tabpanel" aria-labelledby="accessories-tab">
<div class="row">

    @foreach($medicals as $medical)
		<div class="col-xl-3 cl-lg-3 col-md-6">
			<div class="product-wrapper text-center mb-45">
			    <div class="product-img pos-rel">
					<a href="{{ route('productDetail', $medical->url) }}">
					<img decoding="async" class="secondary-img" src="{!! asset($medical->featured_image) !!}" alt="el_hover_img1">
					</a>
					<div class="product-action">
	                  @if(Auth::id()) 
	                  @php get_wishlist($medical->id) @endphp
	                  @if(empty(get_wishlist($medical->id)))
	                  <a href="{{ route('addWishlist', $medical->id) }}" title="Add To Wishlist" class="action-btn button"><i class="fa-solid fa-heart"></i></a>
	                  @else
	                  <a title="Remove From Wishlist" href="{{ route('deleteWishlist', $medical->id) }}" class="action-btn button"><i class="fa-solid fa-heart"></i></a>
	                  @endif
	                  @endif
                    <a href="{{ route('productDetail', $medical->url) }}" class="action-btn button"><i class="fa-solid fa-cart-shopping"></i></a>
                    <a href="{{ route('productDetail', $medical->url) }}" class="action-btn button"><i class="fa-solid fa-magnifying-glass"></i></a>
                  </div>
				</div>
				<div class="product-text">
					<h4><a href="#">{{ $medical->name }}</a></h4>



@if($medical->product_type == 1)
@php
if(\Auth::check()){
    $s_price = get_discounted_price($medical->id);
} else {
    $s_price = $medical->offer_price;
}
@endphp
					@if($medical->regular_price > $s_price)
					<del aria-hidden="true">
					<span class="woocommerce-Price-amount amount">
					<bdi><span class="woocommerce-Price-currencySymbol">$</span>{{ number_format($medical->regular_price, 2) }}</bdi></span>
					</del> 
                    @endif
					<ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>{{ number_format($s_price, 2) }}</bdi>
					</span></ins>
@else
@php
$group_price = get_group_price($medical->id);
@endphp
                <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>{{ number_format($group_price['min_price'], 2) }}</bdi>
					</span> - <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>{{ number_format($group_price['max_price'], 2) }}</bdi>
					</span></ins>
@endif
		</div>
		</div>
		</div>
    @endforeach

</div>
</div>

<div class="tab-pane fade" id="medical-equipment">
<div class="row">

@foreach($dentals as $medical)
		<div class="col-xl-3 cl-lg-3 col-md-6">
			<div class="product-wrapper text-center mb-45">
			    <div class="product-img pos-rel">
					<a href="{{ route('productDetail', $medical->url) }}">
					<img decoding="async" class="secondary-img" src="{!! asset($medical->featured_image) !!}" alt="el_hover_img1">
					</a>

					<div class="product-action">

                  @if(Auth::id()) 
                  @php get_wishlist($medical->id) @endphp
                  @if(empty(get_wishlist($medical->id)))
                  <a href="{{ route('addWishlist', $medical->id) }}" title="Add To Wishlist" class="action-btn button"><i class="fa-solid fa-heart"></i></a>
                  @else
                  <a title="Remove From Wishlist" href="{{ route('deleteWishlist', $medical->id) }}" class="action-btn button"><i class="fa-solid fa-heart"></i></a>
                  @endif
                  @endif
                    <a href="{{ route('productDetail', $medical->url) }}" class="action-btn button"><i class="fa-solid fa-cart-shopping"></i></a>
                    <a href="{{ route('productDetail', $medical->url) }}" class="action-btn button"><i class="fa-solid fa-magnifying-glass"></i></a>
                  </div>
				</div>
				<div class="product-text">
					<h4><a href="#">{{ $medical->name }}</a></h4>
@if($medical->product_type == 1)
@php
if(\Auth::check()){
    $s_price = get_discounted_price($medical->id);
} else {
    $s_price = $medical->offer_price;
}
@endphp
					@if($medical->regular_price > $s_price)
					<del aria-hidden="true">
					<span class="woocommerce-Price-amount amount">
					<bdi><span class="woocommerce-Price-currencySymbol">$</span>{{ number_format($medical->regular_price, 2) }}</bdi></span>
					</del> 
                    @endif
					<ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>{{ number_format($s_price, 2) }}</bdi>
					</span></ins>
@else
@php
$group_price = get_group_price($medical->id);
@endphp
                <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>{{ number_format($group_price['min_price'], 2) }}</bdi>
					</span> - <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>{{ number_format($group_price['max_price'], 2) }}</bdi>
					</span></ins>
@endif


				</div>
			</div>
		</div>
    @endforeach



</div>
</div>
<div class="tab-pane fade" id="popular" role="tabpanel">
	<div class="row">

		@foreach($labs as $medical)
		<div class="col-xl-3 cl-lg-3 col-md-6">
			<div class="product-wrapper text-center mb-45">
			    <div class="product-img pos-rel">
					<a href="{{ route('productDetail', $medical->url) }}">
					<img decoding="async" class="secondary-img" src="{!! asset($medical->featured_image) !!}" alt="el_hover_img1">
					</a>

					<div class="product-action">

                  @if(Auth::id()) 
                  @php get_wishlist($medical->id) @endphp
                  @if(empty(get_wishlist($medical->id)))
                  <a href="{{ route('addWishlist', $medical->id) }}" title="Add To Wishlist" class="action-btn button"><i class="fa-solid fa-heart"></i></a>
                  @else
                  <a title="Remove From Wishlist" href="{{ route('deleteWishlist', $medical->id) }}" class="action-btn button"><i class="fa-solid fa-heart"></i></a>
                  @endif
                  @endif
                    <a href="{{ route('productDetail', $medical->url) }}" class="action-btn button"><i class="fa-solid fa-cart-shopping"></i></a>
                    <a href="{{ route('productDetail', $medical->url) }}" class="action-btn button"><i class="fa-solid fa-magnifying-glass"></i></a>
                  </div>
				</div>
				<div class="product-text">
					<h4><a href="#">{{ $medical->name }}</a></h4>
		
@if($medical->product_type == 1)
@php
if(\Auth::check()){
    $s_price = get_discounted_price($medical->id);
} else {
    $s_price = $medical->offer_price;
}
@endphp
					@if($medical->regular_price > $s_price)
					<del aria-hidden="true">
					<span class="woocommerce-Price-amount amount">
					<bdi><span class="woocommerce-Price-currencySymbol">$</span>{{ number_format($medical->regular_price, 2) }}</bdi></span>
					</del> 
                    @endif
					<ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>{{ number_format($s_price, 2) }}</bdi>
					</span></ins>
@else
@php
$group_price = get_group_price($medical->id);
@endphp
                <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>{{ number_format($group_price['min_price'], 2) }}</bdi>
					</span> - <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>{{ number_format($group_price['max_price'], 2) }}</bdi>
					</span></ins>
@endif

				</div>
			</div>
		</div>
    @endforeach


</div>
</div>
</div>
</div>
</div>
</div>
</section>




<!--<section class="elementor-section elementor-top-section">				
	<div class="deal-area pb-50 pt-95">
		<div class="container">
			<div class="row">
				<div class="col-xl-6 col-lg-6 offset-lg-3 offset-xl-3">
					<div class="section-title text-center mb-50">
					    <h2>Deal Of This Week</h2>
					    <p>Sed ut perspiciatis unde omnis iste natus error</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-8 col-lg-8 offset-lg-2 offset-xl-2">
					<div class="deal-wrapper text-center">
						<div class="deal-count">
							<div class="deal-time">
								<div class="time-count">00 <span>Days</span>
								</div>
								<div class="time-count">00 <span>Hour</span>
								</div>
								<div class="time-count">00 <span>Minute</span></div>
								<div class="no_before time-count">00 <span>Second</span></div>
							</div>
				        </div>
				    </div>
				</div>
	        </div>
	    </div>
	</div>
	<div class="banner-02-area pb-70 pl-165 pr-165">
		<div class="container-fluid">
			<div class="row">
			<div class="col-xl-4 col-lg-4 col-md-6">
				<div class="banner-02-wrapper text-center mb-30" style="background: rgb(237, 247, 251);">
					<div class="banner-02-text">
						<span>Digital Meter</span>
						<h2>Blood Pressure Meter</h2>
					</div>
				    <div class="banner-02-img pos-rel">
					    <a href="#"><img decoding="async" src="{!! asset('assets/frontend/images/b-01.png') !!}" alt="Blood Pressure Meter"></a><span class="banner-tag">hot</span>
				    </div>
					<div class="banner-price">
						<span class="old-price">$155.99</span>
						<span class="new-price">$109.99</span>
					</div>
					<div class="banner-button"><a class="c-btn" href="#">Buy Now <i class="fa-solid fa-plus"></i></a>
					</div>
				</div>
			</div>
			<div class="col-xl-4 col-lg-4 col-md-6">
				<div class="banner-02-wrapper text-center mb-30" data-bg-color="#f8f8f8" style="background: rgb(248, 248, 248);">
					<div class="banner-02-text">
						<span>Medical Equipment</span>
						<h2>Inhaler Pressure Meter</h2>
				    </div>
					<div class="banner-02-img pos-rel">
						<a href="#"><img decoding="async" src="{!! asset('assets/frontend/images/b-02.png') !!}" alt="Inhaler Pressure Meter"></a><span class="banner-tag">hot</span>
					</div>
						<div class="banner-price"><span class="old-price">$250.99</span>
							<span class="new-price">$239.99</span>
						</div>
						<div class="banner-button">
							<a class="c-btn" href="#">Buy Now <i class="fa-solid fa-plus"></i></a></div>
				</div>
			</div>
			<div class="col-xl-4 col-lg-4 col-md-6">
				<div class="banner-02-wrapper text-center mb-30" style="background: rgb(243, 248, 255);">
					<div class="banner-02-text">
						<span>Pressure Meter</span>
						<h2>Hand Sanitizer</h2>
					</div>
					<div class="banner-02-img pos-rel">
					    <a href="#"><img decoding="async" src="{!! asset('assets/frontend/images/b-03.png') !!}" alt="Hand Sanitizer"></a><span class="banner-tag">hot</span>
					</div>
					<div class="banner-price">
						<span class="old-price">$140.99</span>
					    <span class="new-price">$90.99</span>
					</div>
					<div class="banner-button">
					    <a class="c-btn" href="#">Buy Now <i class="fa-solid fa-plus"></i></a>
					</div>
				</div>
			</div>
	    </div>
	</div>
</div>		
</section> -->




<section class="featured-tab">								
	<div class="container">		
		<div class="product-area klb-product pb-70">
			<div class="container">
		<div class="row mb-30">
			<div class="col-xl-7 col-lg-7 col-md-7">
				<div class="section-title mb-30">
					<h2>Featured Products</h2>
					<p>Sed ut perspiciatis unde omnis iste natus error</p>
				</div>
			</div>
			<div class="col-xl-5 col-lg-5 col-md-5">
				<div class="b-button shop-btn s-btn text-md-right mb-30">
				    <a href="#">View All Products <i class="fa-solid fa-arrow-right"></i></a>
				</div>
			</div>
		</div>

<div class="row">

@foreach($feature_products as $feature_product)
<div class="col-lg-3 col-md-6">
	<div class="product-03-wrapper grey-2-bg mb-30 text-center">
	<!-- <div class="badge-tag"><span class="product-tag pro-tag hot-1">-24%</span></div> -->
	<div class="product-02-img pos-rel">
		<a href="{{ route('productDetail', $feature_product->url) }}"><img decoding="async" src="{!! asset($feature_product->featured_image) !!}" alt="product_img1"></a>
		<div class="product-action">

		@if(Auth::id()) 
        @php get_wishlist($feature_product->id) @endphp
        @if(empty(get_wishlist($feature_product->id)))
            <a href="{{ route('addWishlist', $feature_product->id) }}" title="Add To Wishlist" class="action-btn button"><i class="fa-solid fa-heart"></i></a>
        @else
            <a title="Remove From Wishlist" href="{{ route('deleteWishlist', $feature_product->id) }}" class="action-btn button"><i class="fa-solid fa-heart"></i></a>
        @endif
        @endif

            <a href="{{ route('productDetail', $feature_product->url) }}" class="action-btn button"><i class="fa-solid fa-cart-shopping"></i></a>
            <a href="{{ route('productDetail', $feature_product->url) }}" class="action-btn button"><i class="fa-solid fa-magnifying-glass"></i></a>

		</div>
</div>
<div class="product-text">
	<h4><a href="{{ route('productDetail', $feature_product->url) }}">{{ $feature_product->name }}</a></h4>

@if($feature_product->product_type == 1)
@php
if(\Auth::check()){
    $s_price = get_discounted_price($feature_product->id);
} else {
    $s_price = $feature_product->offer_price;
}
@endphp

	<del><span><bdi><span>$</span>{{ $feature_product->regular_price }}</bdi></span></del> 

	<ins><span><bdi><span>$</span>{{ $s_price }}</bdi></span></ins>
@else
@php
$group_price = get_group_price($feature_product->id);
@endphp

    <ins><span><bdi><span>$</span>{{ number_format($group_price['min_price'], 2) }}</bdi></span> -  <span><bdi><span>$</span>{{ number_format($group_price['max_price'], 2) }}</bdi></span></ins>
@endif

</div>
</div>
</div>
@endforeach


</div>
</div>
</div>		
</div>
</section>

<section class="elementor-section">		
	<div class="testimonial-area">
				<div class="container">

				<div class="row">
					<div class="col-xl-6 col-lg-6 offset-lg-3 offset-xl-3">
						<div class="section-title text-center mb-65">
							<h2>What Our Client’s Say</h2>
							<p>Sed ut perspiciatis unde omnis iste natus error</p>
						</div>
					</div>
				</div>

				<div class="row blog-slider owl-theme owl-carousel">

                @foreach($feedbacks as $feedback)
				<div class="col-xl-12">
				<div class="testimonial-wrapper">
					<div class="inner-test">
						<div class="test-img"><img decoding="async" src="{!! asset($feedback->image) !!}" alt="">
						</div>
						<div class="test-rating">
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
						</div>
					</div>
					<div class="clearfix"></div>
						<div class="test-text">
							<p>{{ $feedback->comment }}</p>
							<h4>{{ $feedback->name }}</h4>
							<span>{{ $feedback->designation }}</span>
						</div>
					</div>
				</div>
                @endforeach
			
		</div>
		</div>
		</div>
	</section>

<section class="blog-section">
				<div class="container">
					<div class="row">
						<div class="col-xl-6 col-lg-6 offset-lg-3 offset-xl-3">
							<div class="section-title text-center mb-65">
								<h2>Latest News &amp; Blog</h2><p>Sed ut perspiciatis unde omnis iste natus error</p>
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


<section class="client-logo">
	<div class="container">
		<div class="row">	
			<div class="col-md-2">	
                <a href="#"><img decoding="async" src="{!! asset('assets/frontend/images/01-2.png') !!}" alt="blog_small_img1"></a>
			</div>
			<div class="col-md-2">	
                <a href="#"><img decoding="async" src="{!! asset('assets/frontend/images/02-1.png') !!}" alt="blog_small_img1"></a>
			</div>
			<div class="col-md-2">	
                <a href="#"><img decoding="async" src="{!! asset('assets/frontend/images/03.png') !!}" alt="blog_small_img1"></a>
			</div>
			<div class="col-md-2">	
                <a href="#"><img decoding="async" src="{!! asset('assets/frontend/images/04.png') !!}" alt="blog_small_img1"></a>
			</div>
			<div class="col-md-2">	
                <a href="#"><img decoding="async" src="{!! asset('assets/frontend/images/05.png') !!}" alt="blog_small_img1"></a>
			</div>
			<div class="col-md-2">	
                <a href="#"><img decoding="async" src="{!! asset('assets/frontend/images/06.png') !!}" alt="blog_small_img1"></a>
			</div>
			<div class="col-md-2">	
                <a href="#"><img decoding="async" src="{!! asset('assets/frontend/images/07.png') !!}" alt="blog_small_img1"></a>
			</div>
			<div class="col-md-2">	
                <a href="#"><img decoding="async" src="{!! asset('assets/frontend/images/08.png') !!}" alt="blog_small_img1"></a>
			</div>
			<div class="col-md-2">	
                <a href="#"><img decoding="async" src="{!! asset('assets/frontend/images/09.png') !!}" alt="blog_small_img1"></a>
			</div>
			<div class="col-md-2">	
                <a href="#"><img decoding="async" src="{!! asset('assets/frontend/images/10.png') !!}" alt="blog_small_img1"></a>
			</div>
			<div class="col-md-2">	
                <a href="#"><img decoding="async" src="{!! asset('assets/frontend/images/11.png') !!}" alt="blog_small_img1"></a>
			</div>
			<div class="col-md-2">	
                <a href="#"><img decoding="async" src="{!! asset('assets/frontend/images/12.png') !!}" alt="blog_small_img1"></a>
			</div>
        </div>
	</div>
</section>



<section class="vision-section">
		<div class="container">		
			
			<div class="row">	
            <div class="col-md-4">
			<div class="features-wrapper">
				<div class="features-icon fe-1 f-left" style="color:#4e97fd">
					<i class="fa-solid fa-truck"></i>
				</div>
				<div class="features-text">
					<h3>Free Shipping</h3>
					<p>Sed perspicia unde omnis iste nat error voluptate accus</p>
				</div>
			</div>
			</div>	

			<div class="col-md-4">
			    <div class="features-wrapper">
					<div class="features-icon fe-1 f-left" style="color:#E4573D">
						<i class="fa-solid fa-dollar-sign"></i></div>
					<div class="features-text">
						<h3>Money Back</h3>
						<p>Sed perspicia unde omnis iste nat error voluptate accus</p>
					</div>
				</div>		
		    </div>

			<div class="col-md-4">
				<div class="features-wrapper">
					<div class="features-icon fe-1 f-left" style="color:#FEBD00">
						<i class="fa-solid fa-unlock"></i>
					</div>
					<div class="features-text">
						<h3>Free Shipping</h3>
						<p>Sed perspicia unde omnis iste nat error voluptate accus</p>
					</div>	
			    </div>
			</div>
		    </div>
		</section>

@endsection