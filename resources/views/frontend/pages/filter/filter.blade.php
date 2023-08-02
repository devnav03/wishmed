          <ul class="sort_filter">
              <li><button value="4" onclick="SortFilter1(this.value)">Top Trending</button></li>
              <li><button value="3" onclick="SortFilter1(this.value)">Low Price to High</button></li>
              <li><button value="2" onclick="SortFilter1(this.value)">High Price to Low</button></li>
              <li class="active"><button value="1" onclick="SortFilter1(this.value)">New Arrivals</button></li>
              <li>Sort by:</li>
            </ul>
        
            
            @if($products)
            @php 
            $count = 0;
            @endphp
            <div class="row new_products">

               @foreach($products as $key => $product_key)
               @foreach($product_key as $key => $product)
              
              @if(isset($product->offer_price))
              @php
                  $count++;
              @endphp 

               <div class="col-md-3">
                  @if($product->regular_price > $product->offer_price)<h6>{{ round(100-($product->offer_price/$product->regular_price)*100) }}%</h6>@endif
                  <a href="{{ route('productDetail', $product->url) }}"><img src="{!! asset($product->thumbnail) !!}" class="img-fluid mx-auto d-block" alt="{{ $product->name }}"></a>
                  <p>@if($product->regular_price > $product->offer_price)<del>${{ $product->regular_price }}</del>@endif ${{ $product->offer_price }}</p>
                  <h3><a href="{{ route('productDetail', $product->url) }}">{{ $product->name }}</a></h3>
                  <div class="add_box">   
                    <select class="qty{{$product->id}}">
                    <option value="1">Qty</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    </select>
                    <button onclick="addToCart(this.value)" value="{{ $product->id }}" class="btn">Add to Cart</button>
                  </div>
                </div>
                @endif
              @endforeach
              @endforeach
            </div>
            @endif

            @if($count == 0) 
             <div class="text-center" style="width: 100%;">
                  <img src="{!! asset('assets/frontend/images/No-Product-found.jpg') !!}" class="img-fluid d-inline-block max-height-400" alt=""> 
              </div>
            @endif


            
            
       