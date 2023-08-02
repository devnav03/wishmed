@extends('frontend.layouts.app')
@section('content')  
@php
    $route  = \Route::currentRouteName();    
@endphp

<div id="add-cart"></div>

  <section class="bredcrum">
    <div class="container">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>&nbsp;/&nbsp;<a href="{{ route('cartDetail') }}">Cart</a></li>
        <li>&nbsp;/&nbsp;Shipping Address</li>
      </ul>
    </div>
  </section>
@if($user_addresses_count != 0)
<section class="shipping-page" style="padding-top: 40px;">
  <div class="container">
    <form action="{{ route('delivery-address') }}" method="post">
      {{ csrf_field() }}
    <div class="row">  
      <div class="col-md-10 offset-md-1">
        <div class="checkout-page manage-address-main">
          <h2>Delivery Confirmation</h2>
          <div class="delivery_line"></div>
          <div class="clearfix"></div>
          <div class="row">
              @php $i = 0;  @endphp
              @foreach($user_addresses as $user_address)
              @php $i++;  @endphp
              @if(isset($user_address->billing_first_name))
              <div class="col-md-4">
                <div class="user_addresse_box">
                  <a href="{{ route('default_address', $user_address->id) }}" title="Make Default Address">
                @if($df_address)
                <input type="radio" name="address" @if($df_address->address_id == $user_address->id) checked=""  @endif value="{{ $user_address->id }}" class="chk_address">
                @else
                 <input type="radio" name="address" @if($i == 1) checked=""  @endif value="{{ $user_address->id }}" class="chk_address">
                @endif
                <h6> {{ $user_address->billing_first_name }} {{ $user_address->billing_last_name }} </h6>
                <h6 style="font-weight: normal; font-size: 15px;"> {{ $user_address->billing_company_name }} </h6>
                <p> {{ $user_address->billing_street_address }} {{ $user_address->billing_street_address2 }}, {{ $user_address->billing_suburb }}, 
@php
 $select = DB::table('shipping_zones')->where('id', $user_address->billing_state)->select('name')->first();
@endphp
                  {{ $select->name }} - {{ $user_address->billing_postcode }} <br> Phone: {{ $user_address->billing_phone }}<br> Email: {{ $user_address->billing_email_address }} </p>
@if($user_address->ship_different_address == 1)

<p style="margin-bottom: 5px;">Ship to a different address</p>
 <h6> {{ $user_address->shipping_first_name }} {{ $user_address->shipping_last_name }} </h6>
 <h6 style="font-weight: normal; font-size: 15px;"> {{ $user_address->shipping_company_name }} </h6>
                <p> {{ $user_address->shipping_street_address }} {{ $user_address->billing_street_address2 }}, {{ $user_address->shipping_suburb }}, 
@php
 $select = DB::table('shipping_zones')->where('id', $user_address->shipping_state)->select('name')->first();
@endphp
                  {{ $select->name }} - {{ $user_address->shipping_postcode }} </p>

@else
<p>Shipping address is also same</p>

@endif
                </a>
                <a class="address_del" href="{{ route('delAddress', $user_address->id) }}"><i class="fa-solid fa-trash"></i></a>
             
                <a class="address_edit" href="{{ route('user-address.edit', $user_address->id)}}#address-update">Edit</a>
              </div>
              </div>
              @endif
              @endforeach
              </div> 
          <div class="clearfix"></div>
          @if($cart)
          <button type="submit" class="deliver_address">Deliver to the address</button>
          @endif
     
        </div>
      </div>
    </div>
  </form>
  </div>
</section>
@endif

  <div class="clearfix"></div>

    @if($route == 'user-address.edit')
    <section class="aad-address-main" id="address-update">
      <div class="container">
        <div class="row">  
          <div class="col-md-8 offset-md-2">
            <div class="form">
              <div style="position: relative;">
              <h2>Edit Billing Address</h2>
              <div class="delivery_line"></div>
              <div class="clearfix"></div>
              <form action="{{ route('save-address.update')}}" method="post">
                {{ csrf_field() }}
                <div class="row">
                  <div class="col-md-6">
                    <label style="font-weight: 500;">First Name<span>*</span></label>
                    <input type="text" name="billing_first_name" value="{{ $result->billing_first_name }}" maxlength="40" class="form-control" required="true">
                    @if($errors->has('billing_first_name'))
                      <span class="text-danger">{{$errors->first('billing_first_name')}}</span>
                    @endif
                  </div>
                  <div class="col-md-6">
                    <label style="font-weight: 500;">Last Name<span>*</span></label>
                    <input type="text" name="billing_last_name" value="{{ $result->billing_last_name }}" maxlength="40"  class="form-control" required="true">
                    @if($errors->has('billing_last_name'))
                      <span class="text-danger">{{$errors->first('billing_last_name')}}</span>
                    @endif
                  </div>
                  <div class="col-md-12">
                    <label style="font-weight: 500;">Company name (optional)</label>
                    <input type="text" name="billing_company_name" maxlength="100" value="{{ $result->billing_company_name }}" class="form-control">
                    @if($errors->has('billing_company_name'))
                      <span class="text-danger">{{$errors->first('billing_company_name')}}</span>
                    @endif
                  </div>

                  <div class="col-md-12" style="margin-top: 15px;margin-bottom: 15px;">
                    <p id="shipping_country_field1" style="margin-bottom: 0;">
                      <label for="shipping_country" style="color: #000; font-weight: 500;margin-bottom: 3px;" class="">Country / Region *</label><br>
                      <span><strong>Australia</strong></span></p>
                  </div>

                  <div class="col-md-6">
                      <label style="font-weight: 500;">Street address<span>*</span></label>
                      <input type="text" name="shipping_street_address" value="{{ $result->shipping_street_address }}" class="form-control" required="true">
                  </div>
                  <div class="col-md-6">
                      <label style="min-height: 24px;"></label>
                      <input type="text" name="shipping_street_address2" value="{{ $result->shipping_street_address2 }}" class="form-control">
                  </div>
                  <div class="col-md-6">
                      <label style="min-height: 24px;font-weight: 500;">Suburb<span>*</span></label>
                      <input type="text" value="{{ $result->shipping_suburb }}" name="shipping_suburb" required="true" class="form-control">
                  </div>
                  <div class="col-md-6">
                      <label style="min-height: 24px;font-weight: 500;">State<span>*</span></label>
                      <select class="form-control" id="shipping_zone" onChange="ShippingChange(this);" name="shipping_state" required="true"> 
                        <option value="">Select</option> 
                        @foreach($states as $state)
                          <option value="{{ $state->id }}" @if($state->id == $result->shipping_state) selected @endif >{{ $state->name }}</option>
                        @endforeach
                      </select>
                  </div>
                  <div class="col-md-6">
                    <label style="font-weight: 500;">Postcode<span>*</span></label>
                    <input type="text" name="shipping_postcode" maxlength="6" value="{{ $result->shipping_postcode }}" class="form-control" required="true"> 
                    @if($errors->has('shipping_postcode'))
                      <span class="text-danger">{{$errors->first('shipping_postcode')}}</span>
                    @endif
                  </div>
                  <div class="col-md-6">
                    <label style="font-weight: 500;">Phone<span>*</span></label>
                    <input type="number" value="{{ $result->billing_phone }}" placeholder="Address" class="form-control" required="true" name="billing_phone">
                    @if($errors->has('billing_phone'))
                      <span class="text-danger">{{$errors->first('billing_phone')}}</span>
                    @endif
                  </div>

                  <div class="col-md-6">
                    <label style="font-weight: 500;">Email<span>*</span></label>
                    <input type="email" value="{{ $result->billing_email_address }}" class="form-control" required="true" name="billing_email_address">
                    @if($errors->has('billing_email_address'))
                      <span class="text-danger">{{$errors->first('billing_email_address')}}</span>
                    @endif
                  </div>

                  <div class="col-md-12" style="margin-top: 15px;">
              <label style="font-weight: normal;"><input onchange="stickyheaddsadaer(this)" id="ship-to-different-address-checkbox" class="input-checkbox" @if($result->ship_different_address == 1) checked="checked" @endif type="checkbox" name="ship_different_address" value="1"> <span>Ship to a different address?</span>
              </label>
            </div>

            <div class="col-md-12" id="sform" @if($result->ship_different_address != 1) style="display:none;" @endif >
            <div class="row">
            <div class="col-md-6">
                <label style="font-weight: 500;">First name<span>*</span></label>
                <input type="text" name="shipping_first_name" value="{{ $result->shipping_first_name }}" class="form-control" @if($result->ship_different_address == 1) required="true" @endif >
            </div>
            <div class="col-md-6">
                <label style="font-weight: 500;">Last name<span>*</span></label>
                <input type="text" name="shipping_last_name" value="{{ $result->shipping_last_name }}" class="form-control" @if($result->ship_different_address == 1) required="true" @endif >
            </div>
            <div class="col-md-12">
                <label style="font-weight: 500;">Company name (optional)</label>
                <input type="text" name="shipping_company_name" value="{{ $result->shipping_company_name }}" class="form-control">
            </div>
            <div class="col-md-12" style="margin-top: 15px; margin-bottom: 15px;">
              <p id="shipping_country_field" style="margin-bottom: 0;">
                <label for="shipping_country" style="color: #000; font-weight: 500;margin-bottom: 3px;" class="">Country / Region *</label><br>
                <span><strong>Australia</strong></span></p>
            </div>
            <div class="col-md-6">
                <label style="font-weight: 500;">Street address<span>*</span></label>
                <input type="text" name="shipping_street_address" value="{{ $result->shipping_street_address }}" placeholder="House number and street name" class="form-control" @if($result->ship_different_address == 1) required="true" @endif >
            </div>
            <div class="col-md-6">
                <label style="min-height: 24px;font-weight: 500;"></label>
                <input type="text" name="shipping_street_address2" value="{{ $result->shipping_street_address2 }}" placeholder="Apartment, suite, unit, etc. (optional)" class="form-control">
            </div>
            <div class="col-md-6">
                <label style="min-height: 24px;font-weight: 500;">Suburb<span>*</span></label>
                <input type="text" name="shipping_suburb" value="{{ $result->shipping_suburb }}" @if($result->ship_different_address == 1) required="true" @endif class="form-control">
            </div>
            <div class="col-md-6">
                <label style="min-height: 24px;font-weight: 500;">State<span>*</span></label>
                <select class="form-control" id="shipping_zone" onChange="ShippingChange(this);" name="shipping_state" @if($result->ship_different_address == 1) required="true" @endif > 
                  <option value="">Select</option> 
                  @foreach($states as $state)
                    <option value="{{ $state->id }}" @if($state->id == $result->shipping_state) selected @endif >{{ $state->name }}</option>
                  @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label style="min-height: 24px;font-weight: 500;">Postcode<span>*</span></label>
                <input type="number" id="shipping_postcode" value="{{ $result->shipping_postcode }}" name="shipping_postcode" @if($result->ship_different_address == 1) required="true" @endif class="form-control">
            </div>
            </div>
            </div>
                 <input type="hidden" name="id" value="{{ $result->id }}">
                  <div class="col-md-12 text-right">
                    <button id="submitButtonId" type="submit">Update Address</button>
                  </div>
                </div>
              </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    @else

    <!-- MANAGE-ADDRESS-MAIN STARTS -->
    <section class="aad-address-main">
      <div class="container">
        <div class="row">  
          <div class="col-md-8 offset-md-2">
            <div class="form">
              <h2>Add Billing Address</h2>
              <div class="delivery_line"></div>
              <div class="clearfix"></div>
              <form action="{{ route('save-addresses') }}" method="post">
                {{ csrf_field() }}
                <div class="row">
                  <div class="col-md-6">
                    <input type="text" name="billing_first_name" maxlength="40" placeholder="First Name*" class="form-control" required="true">
                    @if($errors->has('billing_first_name'))
                      <span class="text-danger">{{$errors->first('billing_first_name')}}</span>
                    @endif
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="billing_last_name" maxlength="40" placeholder="Last Name*" class="form-control" required="true">
                    @if($errors->has('billing_last_name'))
                      <span class="text-danger">{{$errors->first('billing_last_name')}}</span>
                    @endif
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="billing_company_name" maxlength="100" placeholder="Company name (optional)" class="form-control">
                    @if($errors->has('billing_company_name'))
                      <span class="text-danger">{{$errors->first('billing_company_name')}}</span>
                    @endif
                  </div>

                  <div class="col-md-12">
                    <p id="billing_country_field" style="margin-bottom: 0;">
                      <label for="billing_country" style="color: #000; font-weight: 500;margin-bottom: 3px;" class="">Country / Region *</label><br>
                      <span><strong>Australia</strong></span></p>

                      <label style="font-weight: 600; margin-top: 15px;">Street address<span>*</span></label>
                  </div>

                  <div class="col-md-6">
                    <input type="text" required="true" placeholder="House number and street name*" name="billing_street_address" class="form-control"> 
                    @if($errors->has('billing_street_address'))
                      <span class="text-danger">{{$errors->first('billing_street_address')}}</span>
                    @endif
                  </div>

                  <div class="col-md-6">
                    <input type="text" placeholder="Apartment, suite, unit, etc. (optional)" name="billing_street_address2" class="form-control"> 
                    @if($errors->has('billing_street_address2'))
                      <span class="text-danger">{{$errors->first('billing_street_address2')}}</span>
                    @endif
                  </div>

                  <div class="col-md-6">
                    <input type="text" placeholder="Suburb*" required="true" name="billing_suburb" class="form-control"> 
                    @if($errors->has('billing_suburb'))
                      <span class="text-danger">{{$errors->first('billing_suburb')}}</span>
                    @endif
                  </div>

         
                  <div class="col-md-6">
                    <select class="form-control" name="billing_state" required="true">
                      <option value="">Select State*</option>
                      @foreach($states as $state)
                      <option value="{{ $state->id }}">{{ $state->name }}</option>
                      @endforeach
                    </select>
                    @if($errors->has('billing_state'))
                      <span class="text-danger">{{$errors->first('billing_state')}}</span>
                    @endif
                  </div>
          
                  <div class="col-md-6">
                    <input type="number" name="billing_postcode" placeholder="Postcode*" maxlength="6" class="form-control" required="true">
                    @if($errors->has('billing_postcode'))
                      <span class="text-danger">{{$errors->first('billing_postcode')}}</span>
                    @endif
                  </div>
                  <div class="col-md-6">
                    <input type="number" class="form-control" required="true" placeholder="Phone*" name="billing_phone">
                    @if($errors->has('billing_phone'))
                      <span class="text-danger">{{$errors->first('billing_phone')}}</span>
                    @endif
                  </div>
                  <div class="col-md-6">
                    <input type="email" name="billing_email_address" placeholder="Email address*" class="form-control" required="true">
                    @if($errors->has('billing_email_address'))
                      <span class="text-danger">{{$errors->first('billing_email_address')}}</span>
                    @endif
                  </div>

                  <div class="col-md-12" style="margin-top: 5px;">
                    <label style="font-weight: normal;"><input onchange="stickyheaddsadaer(this)" id="ship-to-different-address-checkbox" class="input-checkbox" checked="checked" type="checkbox" name="ship_different_address" value="1"> <span>Ship to a different address?</span>
                    </label>
                  </div>


            <div class="col-md-12" id="sform" style="margin-top: 20px;">
            <div class="row">
            <div class="col-md-6">
                <input type="text" placeholder="First name" name="shipping_first_name" class="form-control" required="true">
            </div>
            <div class="col-md-6">
                <input type="text" placeholder="Last name*" name="shipping_last_name" class="form-control" required="true">
            </div>
            <div class="col-md-6">
                <input type="text" placeholder="Company name (optional)" name="shipping_company_name" class="form-control">
            </div>
            <div class="col-md-12">
              <p id="shipping_country_field" style="margin-bottom: 0;">
                <label for="shipping_country" style="color: #000; font-weight: 500;margin-bottom: 3px;" class="">Country / Region *</label><br>
                <span><strong>Australia</strong></span></p>
            </div>
            <div class="col-md-12">
              <label style="font-weight: 600; margin-top: 15px;">Street address<span>*</span></label>
            </div>
            <div class="col-md-6">
                <input type="text" name="shipping_street_address" placeholder="House number and street name" class="form-control" required="true">
            </div>
            <div class="col-md-6">
                <input type="text" name="shipping_street_address2" placeholder="Apartment, suite, unit, etc. (optional)" class="form-control">
            </div>
            <div class="col-md-6">
                <label style="min-height: 24px;">Suburb<span>*</span></label>
                <input type="text" name="shipping_suburb" required="true" class="form-control">
            </div>
            <div class="col-md-6">
                <label style="min-height: 24px;">State<span>*</span></label>
                <select class="form-control" id="shipping_zone" onChange="ShippingChange(this);" name="shipping_state" required="true"> 
                  <option value="">Select</option> 
                  @foreach($states as $state)
                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                  @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label style="min-height: 24px;">Postcode<span>*</span></label>
                <input type="number" id="shipping_postcode" name="shipping_postcode" required="true" class="form-control">
            </div>
          </div>

        </div>

                  
                  <div class="col-md-12">
                    <button id="submitButtonId" type="submit">Add Address</button>
                  </div>
                </div>
              </form>
          </div>
          </div>
       </div>
      </div>      
      
    </section>
  <!-- MANAGE-ADDRESS-MAIN ENDS -->

  @endif

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

function getCity(val) {
  $.ajax({
    type: "GET",
    url: "{{ route('getCity') }}",
    data: {'state_id' : val},
    success: function(data){
        $("#city-list").html(data);
    }
  });
}  


function HideBilling1(val) {

   $('#add-address123').show();
   smoothScrollTo('#add-address123', 1500, 100);


}
</script>