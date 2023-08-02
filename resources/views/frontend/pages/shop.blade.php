@extends('frontend.layouts.app')
@section('content')
<!-- BANNER STARTS -->
<!--     <section class="banners">
      <img src="{!! asset('assets/frontend/images/breadcrumb.jpg') !!}" class="img-fluid d-inline-block" alt="">
    </section> -->
<input type="hidden" name="search_key" id="search_cat" value="9009">
    <div class="klb-shop-breadcrumb breadcrumb-area pt-125 pb-125">
      <div class="container">
        <div class="klb-breadcrumb-wrapper">
          <div class="row">
            <div class="col-xl-12">
              <div class="breadcrumb-text">
                  <h2>Products</h2>
              </div>
            </div>
          </div>
          <nav class="woocommerce-breadcrumb">
          <ul class="breadcrumb-menu">
            <li class="fc_child"><a href="{{ route('home') }}">Home</a></li>
            <li><a class="bl_color">Shop</a></li>
          </ul>
          </nav>             
        </div>
      </div>
    </div>


  <!-- BANNER ENDS -->
  <div class="clearfix"></div>
  <!-- PRODUCT-LISTING-PANEL STARTS -->
    <section class="product-listing-panel">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-3 d-md-block d-none">
            <aside>
              <div class="accordion" id="accordionExample">              
<div class="card">
  <div class="card-header" id="headingOne1">
    <h5 class="mb-0">Category</h5>
  </div>
  <div id="collapseOne" class="in collapse show" aria-labelledby="headingOne1" data-parent="#accordionExample" style="">
    <div class="card-body">
      <ul id="selective">
        @if($Categorys)
          @foreach($Categorys as $category)
            <li><a href="{{ route('categoryDetail',  $category->url) }}">{{ $category->name}}</a>
            @php
              $subcats = get_sub_cat($category->id);
            @endphp
            @if($subcats)
            <ul>
              @foreach($subcats as $subcat) 
              <li><a style="font-weight: 500; font-size: 14px;" href="{{ route('categoryDetail', $subcat->url) }}">{{ $subcat->name }}</a> 
            @php
              $subcats = get_sub_cat($subcat->id);
            @endphp
            @if($subcats)
            <ul>
              @foreach($subcats as $subcat) 
                <li><a href="{{ route('categoryDetail', $subcat->url) }}">{{ $subcat->name }}</a>
                </li>
              @endforeach
            </ul>
            @endif
              </li>
              @endforeach
            </ul>
            @endif

            </li>
          @endforeach
        @endif
      </ul>
    </div>
  </div>
</div>
      
 
<!-- <div class="card">
  <div class="card-header" id="headingOne6">
    <h5 class="mb-0"><button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne5" aria-expanded="true" aria-controls="collapseOne5">Availability</button></h5>
  </div>
  <div id="collapseOne5" class="in collapse" aria-labelledby="headingOne6" data-parent="#accordionExample" style="">
    <div class="card-body">
      <ul class="pl-0 mb-0">
        <li><input value="1" name="availability_stock" onChange="getAvailabilityID(this.value);" type="radio"> <span>In Stock</span></li>
        <li><input value="0" name="availability_stock" onChange="getAvailabilityID(this.value);" type="radio"> <span>Out Of Stock</span></li>
      </ul>
    </div>
  </div>
</div> -->
           
              </div>
            </aside>
          </div>
          <div class="col-lg-9 col-md-9" id="category-list">
            <div class="row">
              <div class="col-md-4 offset-md-8">
                <select onchange="SortFilter(this);" id="sortID" class="form-control">
                  <option value="">Sort By Latest</option>
                  <option value="1">New Arrivals</option>
                  <option value="2">High Price to Low</option>
                  <option value="3">Low Price to High</option>
                  <!--<option value="4">Sellers</option>
                  <option value="5">Latest Style</option> -->
                </select>
              </div>
            </div>
            <!--<ul class="sort_filter">
              <li style="color: #212529;font-size: 15px; font-weight: 500; font-family: 'Roboto', sans-serif;margin-right: 10px; ">Sort by:</li>
              <li><button value="1" class="d-inline-block" onclick="SortFilter(this.value)">New Arrivals</button></li>
              <li><button value="2" class="d-inline-block" onclick="SortFilter(this.value)">High Price to Low</button></li>
              <li><button value="3" class="d-inline-block" onclick="SortFilter(this.value)">Low Price to High</button></li>
              <li><button value="4" class="d-inline-block" onclick="SortFilter(this.value)">Best Sellers</button></li>
              <li><button value="5" class="d-inline-block" onclick="SortFilter(this.value)">Latest Style</button></li>
            </ul> -->
            <div class="row">
            @if($products)
              @foreach($products as $product)

              <div class="col-lg-4 col-md-6 col-6 mt-md-0 mt-5">
                <div class="prod-box">
                  <a href="{!! route('productDetail', $product->url) !!}">
                    <div class="img">
                
                    <img src="{!! asset($product->featured_image) !!}" class="img-fluid mx-auto front_img" alt="">
                  
                    </div>
                  </a>
                    <div class="txt mt-3" style="padding: 6px;">
                      <h6 class="mb-3"> <a href="{!! route('productDetail', $product->url) !!}">{{ Str::limit($product->name, 30) }}</a></h6>
@if($product->product_type == 1)
@php
if(\Auth::check()){
    $s_price = get_discounted_price($product->id);
} else {
    $s_price = $product->offer_price;
}
@endphp
          <p><del>${{ $product->regular_price }}</del> ${{ $s_price }}</p>
@else
@php
$group_price = get_group_price($product->id);
@endphp

<p>${{ number_format($group_price['min_price'], 2) }} - ${{ number_format($group_price['max_price'], 2) }} </p>
@endif

                    </div>
                  <div class="product-action">
                    @if(Auth::id()) 
                    @php get_wishlist($product->id) @endphp
                    @if(empty(get_wishlist($product->id)))
                    <a href="{{ route('addWishlist', $product->id) }}" title="Add To Wishlist" class="action-btn button"><i class="fa-solid fa-heart"></i></a>
                    @else
                    <a title="Remove From Wishlist" href="{{ route('deleteWishlist', $product->id) }}" class="action-btn button"><i class="fa-solid fa-heart"></i></a>
                    @endif
                    @endif 
                    <a href="{{ route('productDetail', $product->url) }}" class="action-btn button"><i class="fa-solid fa-cart-shopping"></i></a>
                    <a href="{{ route('productDetail', $product->url) }}" class="action-btn button"><i class="fa-solid fa-magnifying-glass"></i></a>
                  </div>
                </div>
              </div>
              @endforeach
            @endif
       
            </div>
            <div class="text-center">
            @if(isset($products))
              {{ $products->links() }}
            @endif
          </div>
          </div>
        </div>
      </div>
    </section>
  <!-- PRODUCT-LISTING-PANEL ENDS -->

@endsection