@extends('frontend.layouts.app')
@section('content')

<!-- BANNER STARTS -->
    <section class="banner">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 px-0">
            <img src="{!! asset('assets/frontend/images/return-banner.jpg') !!}" class="img-fluid mx-auto d-block" alt="">
          </div>
        </div>
      </div>
    </section>
  <!-- BANNER ENDS -->
  
  <div class="clearfix"></div>


<!-- BREAD-CRUMBS STARTS -->
    <section class="breadcrumbs py-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="bread-crumbs">
              <ul>
                <li><a href="{{ route('home')}}"><i class="fa fa-home"></i> Home</a></li>
                <li>/ Return Policy</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
  <!-- BREAD-CRUMBS ENDS -->

  <div class="clearfix"></div>

  <!-- RETURN-POLICY-MAIN STARTS -->
    <section class="return-policy-main py-5">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="heading text-center">
              <h5>Return Policy</h5>
              <p>Return Your Product In 14 Days</p>
            </div>
            <div class="txt">
              <p>We offer you complete peace of mind while ordering at Uphaar - you can return all items within 14 days of receipt of goods. Please ensure however that the product is unused and the tags, boxes and other packaging is intact.</p>
              <p>If you are not satisfied with what you have bought, we'll gladly take it back within 14 days from the date of delivery. If you have paid by card then we will reverse the payment. In case of Cash on Delivery or Bank Deposits as modes of payment, we will issue a cheque in the registered name of the customer.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  <!-- RETURN-POLICY-MAIN ENDS -->

@endsection  