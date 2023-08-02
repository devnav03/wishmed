        <div id="cart_modal" class="cart_modal py-3 px-2">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
                            <div class="border p-2" id="image"><img src="{!! asset($product->featured_image) !!}" class="img-fluid mx-auto" alt=""></div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-9 pl-0">
                            <h4 class="vmdl mb-0" id="product">{!! $product->name !!}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-4 my-md-0 my-2">
                    <h5 class="vmdl mb-0"><i class="fa fa-check-circle text-success mr-2"></i>Added to cart</h5>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-4 my-md-0 my-2">
                    <h4 class="vmdl mb-0"><span id="price">{{ $product->sale_price }}</span> <span style="font-size: 13px;">X </span> <span id="qty">{{ $id->quantity }}</span> = <span style="font-size: 13px;"><i class="fa fa-inr" aria-hidden="true"></i></span><span id="total_price">{{ $id->quantity*$product->sale_price }}</span></h4>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 my-md-0 my-2 text-right">
                <a class="btn btn-cart-gocart vmdl ml-2" href="{{ route('cartDetail')}}">Cart <span class="itm-count">({{ $count }})</span></a>
                </div>
            </div>
        </div>