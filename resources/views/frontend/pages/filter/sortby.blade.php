    
            <div class="row">
              <div class="col-md-4 offset-md-8">
                <select onchange="SortFilter(this);" id="sortID" class="form-control">
                  <option value="">Sort By Latest</option>
                  <option value="1" @if($sort_by == 1) selected="" @endif >New Arrivals</option>
                  <option value="2" @if($sort_by == 2) selected="" @endif >High Price to Low</option>
                  <option value="3" @if($sort_by == 3) selected="" @endif >Low Price to High</option>
                  <!-- <option value="4">Sellers</option>
                  <option value="5">Latest Style</option> -->
                </select>
              </div>
            </div>
                   
            <div class="row new_products">
            @if($products)
            @php 

             $count = 0;
            $already_pro6 = []; @endphp
              @foreach($products as $product)
              
              @if(isset($product->offer_price))
              @php
                  $count++;
              @endphp 

               <div class="col-lg-4 col-md-6 col-6 mt-md-0 mt-5">
                  <div class="prod-box">
                  <!--  @if($product->regular_price > $product->offer_price)<span class="product-tag">{{ round(100-($product->offer_price/$product->regular_price)*100) }}%</span>@endif -->
                  <a href="{{ route('productDetail', $product->url) }}"><img src="{!! asset($product->featured_image) !!}" style="max-height: 235px;" class="img-fluid mx-auto d-block" alt="{{ $product->name }}"></a>
                  <div class="txt mt-3" style="padding: 6px;">
                  <h6 class="mb-3"><a href="{{ route('productDetail', $product->url) }}">{{ $product->name }}</a></h6>
@if($product->product_type == 1)
@php
if(\Auth::check()){
    $s_price = get_discounted_price($product->id);
} else {
    $s_price = $product->offer_price;
}
@endphp
                <p>@if($product->regular_price > $s_price)<del>${{ $product->regular_price }}</del>@endif ${{ $s_price }}</p>
@else
@php
$group_price = get_group_price($product->id);
@endphp

<p>${{ number_format($group_price['min_price'], 2) }} - ${{ number_format($group_price['max_price'], 2) }} </p>

@endif


                  <!--   <button onclick="addToCart(this.value)" value="{{ $product->id }}" class="btn">Add to Cart</button> -->
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
                @endif
              @endforeach
            @endif
            </div>
            @if($count == 0)
           <div class="text-center" style="width: 100%;">
                  <img src="{!! asset('assets/frontend/images/No-Product-found.jpg') !!}" class="img-fluid d-inline-block max-height-400" alt=""> 
              </div>
            @endif
       