@extends('frontend.layouts.app')
@section('content')
<div id="add-cart"></div>
<div id="add-cart_full"></div>
<section class="bredcrum">
    <div class="container">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>&nbsp;/&nbsp;Wishlist</li>
      </ul>
    </div>
  </section>
  <div class="clearfix"></div>
        <section class="wishlist_products">
    <div class="container">  
          <div class="deliver-box mt-3 col-md-10 offset-md-1" style="margin-top: 50px !important;">
            <div class="table-responsive">
              @if(count($wishlist_products) != 0) 
                    <table class="table table-bordered text-center mb-4">
                      <thead>
                        <tr class="text-uppercase" style="background: #009bdf; color: #fff;">
                          <th>
                            <p class="mb-0">Product</p>
                          </th>
                          <th>
                            <p class="mb-0" style="text-align: left;">Name</p>
                          </th>
                          <th style="">
                            <p class="mb-0">Action</p>
                          </th>
                        </tr>
                      </thead>
                      <tbody> 
                   @if(isset($wishlist_products))
                        @foreach($wishlist_products as $wishlist_product)
                        <tr style="border-bottom: 1px solid #EAEAEA;">
                          <td style="border-left: 1px solid #EAEAEA;">
                            <a href="{{ route('productDetail', $wishlist_product->url)}}">
                                <img class="img-fluid mx-auto d-block max-h-100" src="{!! asset($wishlist_product->thumbnail) !!}" alt=""></a>
                          </td>
                          <td class="text-left" style="vertical-align: middle; border-left: 1px solid #EAEAEA;">
                            <h6 class="mb-0" style="font-family: 'Poppins', sans-serif;color: #5B5B5B;font-size: 16px;text-align: left;">{{ $wishlist_product->name }}</h6>
                          </td>
                      
                          <td style="vertical-align: middle; border-left: 1px solid #EAEAEA;">
                            @if($wishlist_product->quantity != 0) 
                            <button class="move_to_cart" onclick="addToCart(this.value)" value="{{ $wishlist_product->id }}">Add To Cart</button>  
                            @else
                            <button class="move_to_cart">Sold Out</button>
                            @endif
                            <br>
                          <a href="{{ route('deleteWishlist', $wishlist_product->id)}}" class="rem_wis">Remove</a>
                          </td>
                        </tr>
                        @endforeach
                        @endif
                            </tbody>
                    </table>
                @endif
                  </div>
                </div>
        
        @if($count == 0)
        <h5 style="color: #f00; padding: 30px 0;text-align: center;">Products not added in wishlist</h5>
        @endif
      </div>
    </section>
  <!-- WISHLIST-MAIN ENDS -->

  <div class="clearfix"></div>

@endsection  