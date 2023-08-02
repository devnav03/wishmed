@extends('frontend.layouts.app')
@section('content')

  <section class="bredcrum">
    <div class="container">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>&nbsp;/&nbsp;Place Order</li>
      </ul>
    </div>
  </section>
  <div class="clearfix"></div>

  <!-- ABOUT-MAIN STARTS -->
    <section class="place-us">
      <div class="container">
        <h3>Product List</h3>
        @if(session()->has('not_added_in_cart'))
            <li class="alert alert-danger" style="list-style: none; margin-top: 0px;">Please choose the quantity of items you wish to add to your cart</li>
        @endif 
        <form action="{{ route('save-cart-and-place-order') }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
        <table> 
          <tr>  
            <th style="text-align: center;">Sr. No</th>
            <th style="text-align: center;">Product Image</th>
            <th>Product Name</th>
            <th>SKU</th>
            <th style="text-align: center;">Price</th>
            <th style="text-align: center;">Discounted Price</th>
            <th style="text-align: center;">Quantity</th>
          </tr>
          @php
            $i = 0;
          @endphp

          @foreach($products as $product)
          @php
            $i++;
          @endphp
          <tr>  
            <td style="text-align: center;">{{ $i }}</td>
            <td style="text-align: center;"><img src="{!! asset($product->thumbnail) !!}" style="max-height: 120px;"></td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->sku }}</td>
            <td style="text-align: center;">${{ $product->regular_price }}</td>
            <td style="text-align: center;">${{ $product->price }}</td>
            <td style="text-align: center;"><div class="quantity buttons_added">
        <input type="hidden" value="{{ $product->id }}" name="product_id[]">
        <input type="button" onclick="qtu_decrease({{ $product->id }});" value="-" class="minus button is-form">        
        <input type="number" id="pid{{ $product->id }}" class="input-text qty text" step="1" min="0" max="" name="quantity[{{ $product->id }}]" value="0" title="Qty" size="4" placeholder="0" inputmode="numeric" style="width: 50px;">
        <input type="button" value="+" onclick="qtu_increase({{ $product->id }});" value="{{ $product->id }}" class="plus button is-form"> </div></td>
          </tr>
          @endforeach 
        </table>
        <input type="submit" name="submit" value="Checkout">
      </form>

      </div>
    </section>
  <!-- CONTACT-MAIN ENDS -->
@endsection  