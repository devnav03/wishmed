@extends('frontend.layouts.app')
@section('content')


  <div class="clearfix"></div>
  <!-- DETAILS-MAIN STRATS -->
    <section class="details-main">
      <div class="container">
        <div id="add-cart"></div>
        <div id="add-cart_full"></div>
        <div class="row">
        <div class="col-lg-12 col-md-12">
        @if(session()->has('cart_successfully'))
            <li class="alert alert-success" style="list-style: none; margin-top: 0px;">Product successfully added in cart </li>
        @endif  

        @if(session()->has('not_added_in_cart'))
            <li class="alert alert-danger" style="list-style: none; margin-top: 0px;">Please choose the quantity of items you wish to add to your cart</li>
        @endif 


        <div class="row">
          <div class="col-lg-6">
              <!--<div class="col-lg-2" style="padding-right: 0px;">  
                <img class="img-fluid mx-auto d-block" src="{!! asset($product->featured_image) !!}" alt="">
              </div>-->
              
              @if(count($gallery_imgs) > 0)

              <div class="main-prod prod-img">
                    <span id="img_ft_zoom">
                    <img class="mn-img img-fluid mx-auto d-block" src="{!! asset($product->featured_image) !!}" alt="" onerror="this.src='https://dentclues.com/img/not-found.png';">
                    </span>
                </div>

                <div class="row">
                  <div class="col-md-3">
                <div id="featured-slide1">
                <ul id="gal1" class="pl-0 mb-0">
                <a class="big-img" href="{!! asset($product->featured_image) !!}">
                  <li class="bbbb my-2">
                    <a href="#" data-image="{!! asset($product->featured_image) !!}" data-zoom-image="{!! asset($product->featured_image) !!}">
                    <img id="img_01" src="{!! asset($product->featured_image) !!}" alt="" onerror="this.src='https://dentclues.com/img/not-found.png';">
                    </a>
                  </li>
                </a>
                  @if(isset($gallery_imgs))
                  @foreach($gallery_imgs as $gallery_img)
                  <li class="bbbb my-2">
                  <a href="#" data-image="{!! asset($gallery_img->product_image) !!}" data-zoom-image="{!! asset($gallery_img->product_image) !!}">
                  <img id="img_01" src="{!! asset($gallery_img->product_image) !!}" onerror="this.src='https://dentclues.com/img/not-found.png';">
                  </a>
                  </li>
                  @endforeach
                  @endif
                </ul>
              </div>
              </div>
              </div>
                
            @else  
         
                <div class="main-prod prod-img">
                  <!--   <span id="img_ft_zoom" class="hide_phone">
                    <img class="mn-img img-fluid mx-auto d-block" src="{!! asset($product->featured_image) !!}" alt="">
                    </span> -->
                    <img class="img-fluid mx-auto d-block" src="{!! asset($product->featured_image) !!}" alt="">
                </div>
            @endif 

          </div>
          <div class="col-lg-6">
                <div class="details-heading">
                <h2 class="product-name">{{ $product->name }}</h2>
                @if($product->product_type != 2)
                <div class="price_box">
                <p><del>${{ $product->regular_price }}</del> ${{ $product->offer_price }}</p>
                </div>
                @else
@php
$group_price = get_group_price($product->id);
@endphp
                <div class="price_box">
                <p>${{ number_format($group_price['min_price'], 2) }} - ${{ number_format($group_price['max_price'], 2) }}</p>
                </div>
                @endif
                <div class="short_description"> 
                  @if($product->description != '<br>')
                    {!! $product->description !!}
                  @endif  
                </div>
          
                <div class="clearfix"></div>
                @if($product->product_type == 2)           

<form action="{{ route('save-cart') }}" method="post" enctype="multipart/form-data">
  {{ csrf_field() }}
  <table class="" style="width: 100%;">
  <tbody>
  @foreach($configure_products as $configure_product)      
  <tr>
    <td style="width: 130px;">  
      <div class="quantity buttons_added">
        <input type="hidden" value="{{ $configure_product->id }}" name="product_id[]">
        <input type="button" onclick="qtu_decrease({{ $configure_product->id }});" value="-" class="minus button is-form">        
        <input type="number" id="pid{{ $configure_product->id }}" class="input-text qty text" step="1" min="0" max="" name="quantity[{{ $configure_product->id }}]" value="0" title="Qty" size="4" placeholder="0" inputmode="numeric" style="width: 50px;">
        <input type="button" value="+" onclick="qtu_increase({{ $configure_product->id }});" value="{{ $configure_product->id }}" class="plus button is-form"> </div>
    </td>
    <td><label style="margin-bottom: 0;font-weight: 500;">{{ $configure_product->name }}</label>
    </td>

    <td>
@php
if(\Auth::check()){
    $s_price = get_discounted_price($configure_product->id);
} else {
    $s_price = $configure_product->offer_price;
}
@endphp
      <span><bdi><span>$</span>{{ round($s_price, 2) }}</bdi></span>

    </td>
    </tr>  
  @endforeach


   </tbody>
        <thead>
        <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><strong>Each</strong></td>
        </tr>
        </thead>
  </table>

    <button type="submit" class="single_add_to_cart_button button alt">Add to cart</button>
    
  </form>



                @else
                <div class="add-to-cart">
                    @if($product->quantity != 0)
                    <h5>In Stock</h5>
                    <div class="clearfix"></div>
                    <h6 style="float: left; margin-right: 15px; color: #6d6d6d; font-weight: 400; margin-top: 13px;">Quantity:</h6>
                    <input class="qtybox qty{{ $product->id }}" type="number" value="1">
                    <button onclick="addToCart(this.value)" value="{{ $product->id }}" class="single_add_to_cart_button btn">Add to Cart <i class="fa-solid fa-plus"></i></button>
                  <!--     @if(Auth::id()) 
                      @php get_wishlist($product->id) @endphp
                        @if(empty(get_wishlist($product->id)))
                          <a href="{{ route('addWishlist', $product->id) }}" title="Add To Wishlist" class="wish-btn"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
                        @else
                          <a href="{{ route('deleteWishlist', $product->id)}}" title="Remove From Wishlist" class="wish-btn"><i class="fa fa-heart" aria-hidden="true"></i></a>
                      @endif
                      @endif -->
                    @else
                    <button value="{{ $product->id }}" onclick="notifyme(this.value)" class="btn">Out Of Stock</button>
                    @endif
                  </div>
                @endif
                <div class="clearfix"></div> 
                <div class="sku-details" style="margin-top: 15px;">
                  <p><b>SKU:</b> {{ $product->sku }}</p>
                  <p><b>Categories:</b> <a href="{{ route('categoryDetail', $product->cat_url) }}">{{ $product->category }}</a>
                  @if($product->cat2)
                  , <a href="{{ route('categoryDetail', $product->url2) }}">{{ $product->cat2 }}</a>
                  @endif
                  @if($product->cat3)
                  , <a href="{{ route('categoryDetail', $product->url3) }}">{{ $product->cat3 }}</a>
                  @endif
                  @if($product->cat4)
                  , <a href="{{ route('categoryDetail', $product->url4) }}">{{ $product->cat4 }}</a>
                  @endif
                  @if($product->cat5)
                  , <a href="{{ route('categoryDetail', $product->url5) }}">{{ $product->cat5 }}</a>
                  @endif
                  </p>
                  <p style="float: left;margin-right: 7px;"><b>Share Now:</b></p>
                  <ul class="socl-share">
                    <li><a class="fb" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ route('productDetail', $product->url) }}"><i class="fa fa-facebook"></i></a></li>
                    <li><a class="tw" target="_blank" href="https://twitter.com/intent/tweet?text=my share text&amp;url={{ route('productDetail', $product->url) }}"><i class="fa fa-twitter"></i></a></li>
                    <li><a class="gp" target="_blank" href="https://wa.me/?text={{ route('productDetail', $product->url) }}" class="social-button " id=""><i class="fa fa-whatsapp"></i></a></li>
                  </ul>

                  </div>
              </div>
           </div>
        </div>

<section class="product-desc-area pb-60">

    <div class="row">
    
            
<div class="col-xl-12 col-lg-12 mb-30">
  <div class="woocommerce-tabs wc-tabs-wrapper">
    <div class="bakix-details-tab">
      <ul class="nav tabs wc-tabs" role="tablist">
        <li class="nav-item description_tab active" id="tab-title-description" role="tab" aria-controls="tab-description">
        <a class="nav-link active" data-toggle="tab" role="tab" aria-controls="medical-equipment" aria-selected="false" href="#tab-description">Description</a>
        </li>
      <!--   <li class="nav-item reviews_tab" id="tab-title-reviews" role="tab" aria-controls="tab-reviews"><a class="nav-link" data-toggle="tab" role="tab" aria-controls="medical-equipment" aria-selected="false" href="#tab-reviews">Reviews (1) </a>
        </li> -->
      </ul>
    </div>
<div class="prod_des panel entry-content wc-tab active" id="tab-description" role="tabpanel" aria-labelledby="tab-title-description">      
<p>{!! $product->product_description !!}</p>
              </div>

  <div class="prod_des woocommerce-Tabs-panel woocommerce-Tabs-panel--reviews panel entry-content wc-tab" id="tab-reviews" role="tabpanel" aria-labelledby="tab-title-reviews">
  <div id="reviews" class="woocommerce-Reviews">
  <div id="comments">
    <h2 class="woocommerce-Reviews-title">1 review for <span>Medical Microscope</span></h2>
    <ol class="commentlist">
    <li class="review even thread-even depth-1" id="li-comment-11">
  <div id="comment-11" class="comment_container">

    <img alt="" src="https://secure.gravatar.com/avatar/3384f98a21c5dce2051e8f5a20928b05?s=60&amp;d=mm&amp;r=g" srcset="https://secure.gravatar.com/avatar/3384f98a21c5dce2051e8f5a20928b05?s=120&amp;d=mm&amp;r=g 2x" class="avatar avatar-60 photo" height="60" width="60" loading="lazy" decoding="async">
    <div class="comment-text">

      <div class="star-rating" role="img" aria-label="Rated 5 out of 5"><span style="width:100%"> <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></span></div>
  <p class="meta">
    <strong class="woocommerce-review__author">admin </strong>
        <span class="woocommerce-review__dash">–</span> <time class="woocommerce-review__published-date" datetime="2020-09-15T05:31:50+00:00">September 15, 2020</time>
  </p>

  <div class="description"><p>Sed perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium</p>
</div>
    </div>
  </div>
</li><!-- #comment-## -->
      </ol>

            </div>

 <!--      <div id="review_form_wrapper">
      <div id="review_form">
      <div id="respond" class="comment-respond">
      <span id="reply-title" class="comment-reply-title">Add a review <small><a rel="nofollow" id="cancel-comment-reply-link" href="#" style="display:none;">Cancel reply</a></small></span>
     
</div>
</div>
</div> -->
  
  <div class="clear"></div>
</div>
        </div>
        </div>
        </div>


        <!-- <div class="col-xl-4 col-lg-4">
          <div class="product-desc-imgmb-30">
            <a href="#">
                <img src="https://sspl23.in/wishmed/wp-content/uploads/2020/09/pr-banner.png" alt="banner">
              </a>
          </div>
        </div> -->
        
            
    </div>

</section>



         @if($product->product_description)
         <section class="description_detail" style="display: none;">

        <h4>Description</h4>
        {!! $product->product_description !!}

  </section>



@endif

  
<section class="new_products rel_pro" id="category-list">
<h2>Related Products</h2>
<div class="clearfix"></div>
<div id="new_products_related" class="owl-carousel owl-theme">
@foreach($new_products as $new_product)	
<div class="row item">
<div class="col-md-12">
  <div class="prod-box">
<a href="{{ route('productDetail', $new_product->url) }}"><img src="{!! asset($new_product->featured_image) !!}" class="img-fluid mx-auto d-block" alt="{{ $new_product->name }}"></a>
<div class="txt mt-3" style="padding: 6px;">

<h6 class="mb-3"><a href="{{ route('productDetail', $new_product->url) }}">{{ $new_product->name }}</a></h6>

@if($new_product->product_type == 1)
@php
if(\Auth::check()){
    $s_price = get_discounted_price($new_product->id);
} else {
    $s_price = $new_product->offer_price;
}
@endphp

<p>@if($new_product->regular_price > $s_price)<del>${{ $new_product->regular_price }}</del> @endif ${{ $s_price }} </p>

@else
@php
$group_price = get_group_price($new_product->id);
@endphp

<p>${{ number_format($group_price['min_price'], 2) }} - ${{ number_format($group_price['max_price'], 2) }} </p>

@endif

</div>
<div class="product-action">

                  @if(Auth::id()) 
                  @php get_wishlist($new_product->id) @endphp
                  @if(empty(get_wishlist($new_product->id)))
                  <a href="{{ route('addWishlist', $new_product->id) }}" title="Add To Wishlist" class="action-btn button"><i class="fa-solid fa-heart"></i></a>
                  @else
                  <a title="Remove From Wishlist" href="{{ route('deleteWishlist', $new_product->id) }}" class="action-btn button"><i class="fa-solid fa-heart"></i></a>
                  @endif
                  @endif
                    <a href="{{ route('productDetail', $new_product->url) }}" class="action-btn button"><i class="fa-solid fa-cart-shopping"></i></a>
                    <a href="{{ route('productDetail', $new_product->url) }}" class="action-btn button"><i class="fa-solid fa-magnifying-glass"></i></a>
                  </div>
</div>
</div>
</div>
@endforeach
</div>
</section>
  
      </div>
      </div>
      </div>
  </section>
 

  <!-- INDEX-MAIN ENDS -->

  <div class="clearfix"></div>
 
@endsection
