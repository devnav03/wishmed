@extends('frontend.layouts.app')
@section('content')
@php
    $route  = \Route::currentRouteName();    
@endphp
@php
$offer_id = null;

@endphp

@php
  $sale_price = 0;
  $list_price = 0;
  $case_deal_price = 0;
  $tax = 0;
  $discount = 0;
  $stich_price = 0;
  $offer_based = \Session::get('offer_based');
  if($offer_based){
  $discount_type = \Session::get('discount_type');
  $off_percentage = \Session::get('off_percentage');
  $off_amount = \Session::get('off_amount');
  $min_amount = \Session::get('min_amount');
  $max_discount = \Session::get('max_discount');
  $sub_product = \Session::get('sub_product');
  $product_id = \Session::get('product_id');
  $offer_id = \Session::get('offer_id');

    if($offer_based == 'brand' || $offer_based == 'category'){
             $name = \Session::get('name'); @endphp
            <div class="alert alert-info" role="alert">
            @php 
            echo 'You get '; 
            if($discount_type == 'Price'){
                echo 'Rs. '.$off_amount.' off in minimum amount Rs.'.$min_amount.' ';
            }
            if($discount_type == 'Percentage'){
                echo ''.$off_percentage.'% off in minimum amount Rs.'.$min_amount.' and max discount is Rs. '.$max_discount.'';
            }
              echo ' on all '.$name.' products';
            @endphp
          </div>
            @php 
            }

             if($offer_based == 'product'){
             $name = \Session::get('name'); @endphp
          <div class="alert alert-info" role="alert">
            @php 
            echo 'You get '; 
            if($discount_type == 'Price'){
                echo 'Rs. '.$off_amount.' off in minimum amount Rs.'.$min_amount.' and max discount is Rs. '.$max_discount.'';
            }
            if($discount_type == 'Percentage'){
                echo ''.$off_percentage.'% off in minimum amount Rs.'.$min_amount.' and max discount is Rs. '.$max_discount.'';
            }
              echo ' in '.$name.'';

            @endphp
          </div>
            @php 
            }
             if($offer_based == 'get_one'){
             $name = \Session::get('name'); 
             $sub_name = \Session::get('sub_name');
             @endphp
          <div class="alert alert-info" role="alert">
            @php 
            echo 'Buy <b>'.$name.'</b> and get one free <b>'.$sub_name.'</b>'; 
            @endphp
          </div>
            @php 
            }

            if($offer_based == 'category'){
            $cat_id = \Session::get('cat_id');
             $discount += get_cat_discount($offer_id);
            if(get_cat_minimum($offer_id)<$min_amount) {
             $discount = 0;
            } else {
             if($discount>$max_discount){
             $discount = $max_discount;
           }
          }
            }  

          if($offer_based == 'brand'){
            $cat_id = \Session::get('brand_id');
             $discount += get_brand_discount($offer_id);
            if(get_brand_minimum($offer_id)<$min_amount) {
             $discount = 0;
            } else {
             if($discount>$max_discount){
             $discount = $max_discount;
           }
           }
          } 
          if($offer_based == 'Price'){
            $discount += $off_amount;
            if(get_price_minimum($offer_id)<$min_amount) {
             $discount = 0;
            } 
          }

          if($offer_based == 'Percentage'){
             $discount += get_percentage_discount($offer_id);
            if(get_price_minimum($offer_id)<$min_amount) {
             $discount = 0;
            } else {
             if($discount>$max_discount){
             $discount = $max_discount;
           }
           }
          } 

          if($offer_based == 'product'){
            $cat_id = \Session::get('brand_id');
             $discount += get_product_discount($offer_id);

            if(get_product_minimum($offer_id)<$min_amount) {
             $discount = 0;
            } else {
             if($discount>$max_discount){
             $discount = $max_discount;
           }
           }
          }
           }
    @endphp

<div class="clearfix"></div>
 <section class="bredcrum">
    <div class="container">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>&nbsp;/&nbsp;<a href="{{ route('cartDetail') }}">Cart</a></li>
        <li>&nbsp;/&nbsp;Checkout</li>
      </ul>
    </div>
  </section>

  <section class="checkout-page">
     <div class="container">
      <h3 style="margin-top: 35px; font-size: 23px; color: #000;">BILLING DETAILS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a style="font-size: 14px; color: #f00;" href="{{ route('manage-address') }}">Change Address</a></h3>
      <form action="{!! route('checkoutnew') !!}" method="post">
        <div class="row">
          <div class="col-md-6">
            <div class="row" id="dform">
            <div class="col-md-6">
                <label>First name<span>*</span></label>
                <input type="text" name="billing_first_name" value="{{ $user_address->billing_first_name }}" class="form-control" required="true">
            </div>
            <div class="col-md-6">
                <label>Last name<span>*</span></label>
                <input type="text" name="billing_last_name" value="{{ $user_address->billing_last_name }}" class="form-control" required="true">
            </div>
            <div class="col-md-12">
                <label>Company name (optional)</label>
                <input type="text" name="billing_company_name" value="{{ $user_address->billing_company_name }}" class="form-control">
            </div>
            <div class="col-md-12">
              <p id="billing_country_field" style="margin-bottom: 0;">
                <label for="billing_country" style="color: #000; font-weight: 500;margin-bottom: 3px;" class="">Country / Region *</label><br>
                <span><strong>Australia</strong></span></p>
            </div>
            <div class="col-md-6">
                <label>Street address<span>*</span></label>
                <input type="text" name="billing_street_address" value="{{ $user_address->billing_street_address }}" placeholder="House number and street name" class="form-control" required="true">
            </div>
            <div class="col-md-6">
                <label style="min-height: 24px;"></label>
                <input type="text" name="billing_street_address2" value="{{ $user_address->billing_street_address2 }}" placeholder="Apartment, suite, unit, etc. (optional)" class="form-control">
            </div>
            <div class="col-md-12">
                <label style="min-height: 24px;">Suburb<span>*</span></label>
                <input type="text" name="billing_suburb" value="{{ $user_address->billing_suburb }}" required="true" class="form-control">
            </div>

            <div class="col-md-12">
                <label style="min-height: 24px;">State<span>*</span></label>
                <select class="form-control" id="billing_zone" name="billing_state" onChange="BillingShippingChange(this);" required="true">
                  <option value="">Select</option> 
                  @foreach($states as $state)
                    <option value="{{ $state->id }}" @if($state->id == $user_address->billing_state) selected @endif >{{ $state->name }}</option>
                  @endforeach
                </select>
            </div>
            
            <div class="col-md-12">
                <label style="min-height: 24px;">Postcode<span>*</span></label>
                <input type="number" value="{{ $user_address->billing_postcode }}" id="billing_postcode" name="billing_postcode" required="true" class="form-control">
            </div>
            <div class="col-md-12">
                <label style="min-height: 24px;">Phone<span>*</span></label>
                <input type="number" value="{{ $user_address->billing_phone }}" name="billing_phone" required="true" class="form-control">
            </div>

            <div class="col-md-12">
                <label style="min-height: 24px;">Email address<span>*</span></label>
                <input type="email" value="{{ $user_address->billing_email_address }}" name="billing_email_address" required="true" class="form-control">
            </div>
            
            <div class="col-md-12" style="margin-top: 15px;">
              <label style="font-weight: normal;"><input onchange="stickyheaddsadaer(this)" id="ship-to-different-address-checkbox" class="input-checkbox" @if($user_address->ship_different_address == 1) checked="checked" @endif type="checkbox" name="ship_different_address" value="1"> <span>Ship to a different address?</span>
              </label>
            </div>

            <div class="col-md-12" id="sform" @if($user_address->ship_different_address != 1) style="display:none;" @endif >
            <div class="row">
            <div class="col-md-6">
                <label>First name<span>*</span></label>
                <input type="text" name="shipping_first_name" value="{{ $user_address->shipping_first_name }}" class="form-control" @if($user_address->ship_different_address == 1) required="true" @endif >
            </div>
            <div class="col-md-6">
                <label>Last name<span>*</span></label>
                <input type="text" name="shipping_last_name" value="{{ $user_address->shipping_last_name }}" class="form-control" @if($user_address->ship_different_address == 1) required="true" @endif >
            </div>
            <div class="col-md-12">
                <label>Company name (optional)</label>
                <input type="text" name="shipping_company_name" value="{{ $user_address->shipping_company_name }}" class="form-control">
            </div>
            <div class="col-md-12">
              <p id="shipping_country_field" style="margin-bottom: 0;">
                <label for="shipping_country" style="color: #000; font-weight: 500;margin-bottom: 3px;" class="">Country / Region *</label><br>
                <span><strong>Australia</strong></span></p>
            </div>
            <div class="col-md-6">
                <label>Street address<span>*</span></label>
                <input type="text" name="shipping_street_address" value="{{ $user_address->shipping_street_address }}" placeholder="House number and street name" class="form-control" @if($user_address->ship_different_address == 1) required="true" @endif >
            </div>
            <div class="col-md-6">
                <label style="min-height: 24px;"></label>
                <input type="text" value="{{ $user_address->shipping_street_address2 }}" name="shipping_street_address2" placeholder="Apartment, suite, unit, etc. (optional)" class="form-control">
            </div>
            <div class="col-md-12">
                <label style="min-height: 24px;">Suburb<span>*</span></label>
                <input type="text" name="shipping_suburb" value="{{ $user_address->shipping_suburb }}" @if($user_address->ship_different_address == 1) required="true" @endif class="form-control">
            </div>
            <div class="col-md-12">
                <label style="min-height: 24px;">State<span>*</span></label>
                <select class="form-control" id="shipping_zone" onChange="ShippingChange(this);" name="shipping_state" @if($user_address->ship_different_address == 1) required="true" @endif > 
                  <option value="">Select</option> 
                  @foreach($states as $state)
                    <option value="{{ $state->id }}" @if($state->id == $user_address->shipping_state) selected @endif >{{ $state->name }}</option>
                  @endforeach
                </select>
            </div>
            <div class="col-md-12">
                <label style="min-height: 24px;">Postcode<span>*</span></label>
                <input type="number" id="shipping_postcode" value="{{ $user_address->shipping_postcode }}" name="shipping_postcode" @if($user_address->ship_different_address == 1) required="true" @endif  class="form-control">
            </div>
          </div>

        </div>
        
        <div class="col-md-12">
            <label style="min-height: 24px;">Order notes (optional)</label>
            <textarea name="order_notes" rows="5" placeholder="Notes about your order, e.g. special notes for delivery." class="form-control"/></textarea>
        </div>



        </div>
      </div>
      <div class="col-md-5 offset-md-1">
        <div style="border: 2px solid #009bdf; padding: 30px; padding-bottom: 10px;">
        <h5 style="text-transform: uppercase; font-weight: 500; margin-bottom: 20px; color: #000; font-size: 20px;">Your Order</h5>   

        <h6 style="text-transform: uppercase; font-weight: 500; margin-bottom: 15px; color: #000; font-size: 18px; border-bottom: 4px solid #f3f3f3; padding-bottom: 7px;">Product <span style="float: right;">Subtotal</span></h6> 

          @if(isset($cart_products)) 
          @foreach($cart_products as $cart_product)
          @if(isset($cart_product->offer_price))
          @php
            if(\Auth::check()){
              $s_price = get_discounted_price($cart_product->pid);
            } else {
              $s_price = $cart_product->offer_price;
            }

            $sale_price += $s_price*$cart_product->quantity;
            $list_price += $cart_product->regular_price*$cart_product->quantity;
          @endphp 
            <ul class="check_products"> 
              <li style="width: 60%;float: left;font-weight: 400; color: #666;">{{ $cart_product->name }} x {{ $cart_product->quantity }} </li>
              <li style="width: 40%;float: left;text-align: right;font-weight: 500; color: #000;">${{ number_format($s_price*$cart_product->quantity, 2) }}</li>
            </ul>   
            <div class="clearfix"></div>
          @endif
          @endforeach
          @endif



          <div class="total-box">
@php
  $total_pay_amount =  $sale_price-$discount;
  $discount = $discount;
  $sale_price = $sale_price;
  $total_pay_amout = (int)$total_pay_amount; @endphp

            @if($sale_price != 0)
              <div class="total_checkout" style="border: 0px; padding: 0px; margin-bottom: 0px;">
             <!--  <p>Item(s) Price <span id="total_list_price"> <i class="fa fa-dollar"></i>{{ $list_price }}</span></p> -->
              <!-- <p>Discount <span id="discount"> <i class="fa fa-dollar"></i>{{ $discount + $case_deal_price + $list_price - $sale_price }}</span></p> -->

              <p style="border-bottom: 1px solid #d6d6d6; padding-bottom: 10px;font-weight: 500; color: #000; ">Subtotal <span style="float: right;font-weight: 500; color: #000;" id="total_sale_subtotal"> <i class="fa fa-dollar"></i>{{ number_format($sale_price, 2) }}</span></p>
             
           
        
               <div id="shipform"><p style="border-bottom: 1px solid #d6d6d6; padding-bottom: 10px;font-weight: 500; color: #000; ">Shipping {!! $shipform !!} <input type="hidden" id="shipping_method" name="shipping_method" value="{{ $shipping_method }}"></p></div>
          

              @php
                $shipping_price = $shipping_price;
              @endphp
             
              @php
              $shipping_tax_price = ($shipping_price/100)*$shipping_tax;
              @endphp

              <p style="border-bottom: 1px solid #d6d6d6; padding-bottom: 10px;font-weight: 500; color: #000; ">Tax <span style="float: right;font-weight: 500; color: #000;" id="total_tax">  $<span id="shipping_tax_price">{{ number_format($shipping_tax_price, 2) }}</span></span></p>

              <p style="font-size: 18px; font-weight: 600; color: #000; padding-top: 3px;border-bottom: 2px solid #ccc; padding-bottom: 10px;">Total <span  style="color: #000; float: right; ">$<span id="total_sale_price_cart1">{{ $sale_price+$shipping_price+$shipping_tax_price }}</span></span></p>
              </div>
              
              <div class="card-form">
                <div class="row">
                <div class="col-md-12">
                    <label>PO Number (optional)</label>
                    <input type="text" name="po_number">
                </div>
                </div>

                <h4 style="font-size: 18px;">Credit card <img src="{!! asset('assets/frontend/images/eway-tiny.svg')  !!}"></h4>
                <div class="row">
                  <div class="col-md-12">
                    <label>Card Holder's Name *</label>
                    <input type="text" name="card_holder_name" required="true">
                  </div>

                  <div class="col-md-12">
                    <label>Card number *</label>
                    <input type="number" pattern="[0-9\s]{13,19}" maxlength="19" name="card_number" required="true">
                  </div>

                  <div class="col-md-6">
                  <label>Expiration date *</label>  
                  <select name="expiration_month" required="true">
                      <option value="">Month</option>
                      <option value="01">January</option>
                      <option value="02">February</option>
                      <option value="03">March</option>
                      <option value="04">April</option>
                      <option value="05">May</option>
                      <option value="06">June</option>
                      <option value="07">July</option>
                      <option value="08">August</option>
                      <option value="09">September</option>
                      <option value="10">October</option>
                      <option value="11">November</option>
                      <option value="12">December</option>
                  </select>
                  <select name="expiration_year" required="true">
                      <option value="">Year</option>
                      <option value="2023">2023</option>
                      <option value="2024">2024</option>
                      <option value="2025">2025</option>
                      <option value="2026">2026</option>
                      <option value="2027">2027</option>
                      <option value="2028">2028</option>
                      <option value="2029">2029</option>
                      <option value="2030">2030</option>
                      <option value="2031">2031</option>
                      <option value="2032">2032</option>
                      <option value="2033">2033</option>
                      <option value="2034">2034</option>
                      <option value="2035">2035</option>
                      <option value="2036">2036</option>
                      <option value="2037">2037</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label>Card code *</label> 
                  <input type="number" style="max-width: 90px;" maxlength="4" minlength="3" name="cvn" required="true">
                </div>

                <div class="col-md-6">
                  <button class="payment_btn" type="submit">Place Order</button>
                </div>

                </div>
              </div>
              <p class="card-formp">Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our privacy policy.</p>


            @endif
              </div>  
              </div>
     </div>

              {{ csrf_field() }}
               
            <input type="hidden" value="{{ $sale_price }}" id="t_amount">
            <input type="hidden" value="3" id="shipping_tax">
            <input type="hidden" value="0" id="product_tax_value">
              
          </div>
    </form>
  </div>
  </section>

  
  @endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">

function stickyheaddsadaer(obj) {
  if($(obj).is(":checked")){
    $("#sform").show();
    $("#sform input").attr("required", true);
    $("#sform select").attr("required", true);

  } else{
    $("#sform input").removeAttr('required');
    $("#sform select").removeAttr('required');
    $("#sform").hide();
  }
}
             
// $('input[type=radio][name=shipping_method]').change(function() {
//     if (this.value == 'local_pickup') {
//       alert('there');
//     }
//     else if (this.value == 'flat_rate') {
//       alert('here');
//     }
// }); 

function changeShip(){
  var t_amount = $("#t_amount").val();
  var shipping_tax = $("#shipping_tax").val();
  var product_tax_value = $("#product_tax_value").val();
  var ship_tax  = 0;
  var total_price = parseInt(t_amount)+parseInt(product_tax_value);

  $("#total_sale_price_cart1").html(total_price);
  var total_tax = product_tax_value;
  $("#shipping_tax_price").html(total_tax);
}

function changeShip1(){
  var t_amount = $("#t_amount").val();
  var shipping_tax = $("#shipping_tax").val();
  var product_tax_value = $("#product_tax_value").val();

  var ship_tax  = (parseInt(45)/100)*parseInt(shipping_tax);
  
  var total_price = parseInt(t_amount)+parseInt(ship_tax)+parseInt(product_tax_value)+parseInt(25);

  $("#total_sale_price_cart1").html(total_price);
  var total_tax = parseInt(product_tax_value)+parseInt(ship_tax);
  $("#shipping_tax_price").html(total_tax);
}

// $(document).ready(function(){ 
//     $("#dform input" ).change(function() {
//     var val_ad = $("input[name=address]:checked", "#dform").val();  
    
//     $("#addValues").val(val_ad); 
 
//    });
// });

// $(document).ready(function(){ 
//     $("#dform1 input" ).change(function() {
//     var val_ad = $("input[name=billing_address]:checked", "#dform1").val();  
    
//     $("#addValues1").val(val_ad); 
 
//    });
// });



// function HideBilling(val) {

//   $('.billing_ad').show();
//   $('#add-address').hide();
//   $('#add-address123').hide(); 
//   $("#addValues2").val(0); 
//    smoothScrollTo('.billing_ad', 1500, 100);
// }

// function HideBilling1(val) {

//    $('.billing_ad').show();
//    smoothScrollTo('.billing_ad', 1500, 100);

// }

</script>

<script type="text/javascript">
function BillingShippingChange(that) {

  var zone_id = that.value;
  var pincode =  document.getElementById("billing_postcode").value;

  var lfckv = document.getElementById("ship-to-different-address-checkbox").checked;
  if(lfckv == false){
    $.ajax({
        type:'GET',
        url: "{{ route('shipping-calculation') }}",
        data:{zone_id:zone_id, pincode:pincode},
        success:function(data){
          $("#shipping_price").html(data.shipping_price);
          $("#shipping_tax_price").html(data.shipping_tax_price);
          $("#shipping_type").html(data.shipping_type);
          $("#shipform").html(data.shipform);
          $("#total_sale_price_cart1").html(data.total_price);
          $("#shipping_method").val(data.shipping_method);
          

        }
    });
  }
}


function ShippingChange(that) {

  var zone_id = that.value;
  var pincode =  document.getElementById("shipping_postcode").value;

    $.ajax({
        type:'GET',
        url: "{{ route('shipping-calculation') }}",
        data:{zone_id:zone_id, pincode:pincode},
        success:function(data){
          $("#shipping_price").html(data.shipping_price);
          $("#shipping_tax_price").html(data.shipping_tax_price);
          $("#shipping_type").html(data.shipping_type);
          $("#shipform").html(data.shipform);
          $("#total_sale_price_cart1").html(data.total_price);
        }
    });
}

$(document).on('keyup', '#billing_postcode', function(){

  var lfckv = document.getElementById("ship-to-different-address-checkbox").checked;
    if(lfckv == false){
      var pincode =  document.getElementById("billing_postcode").value;
      if(pincode.length == 4) {
        var zone_id =  document.getElementById("billing_zone").value; 

        $.ajax({
          type:'GET',
          url: "{{ route('shipping-calculation') }}",
          data:{zone_id:zone_id, pincode:pincode},
          success:function(data){
            $("#shipping_price").html(data.shipping_price);
            $("#shipping_tax_price").html(data.shipping_tax_price);
            $("#shipping_type").html(data.shipping_type);
            $("#shipform").html(data.shipform);
            $("#total_sale_price_cart1").html(data.total_price);
          }
        });


      } 
  }

})


$(document).on('keyup', '#shipping_postcode', function(){

      var pincode =  document.getElementById("shipping_postcode").value;
      if(pincode.length == 4) {
        var zone_id =  document.getElementById("shipping_zone").value; 

        $.ajax({
          type:'GET',
          url: "{{ route('shipping-calculation') }}",
          data:{zone_id:zone_id, pincode:pincode},
          success:function(data){
            $("#shipping_price").html(data.shipping_price);
            $("#shipping_tax_price").html(data.shipping_tax_price);
            $("#shipping_type").html(data.shipping_type);
            $("#shipform").html(data.shipform);
            $("#total_sale_price_cart1").html(data.total_price);
          }
        });

      } 

})


</script>

@if(session()->has('create_address_bil'))
<script type="text/javascript">
 $('.billing_ad').show();
   smoothScrollTo('.billing_ad', 1500, 100);
</script>
@endif
