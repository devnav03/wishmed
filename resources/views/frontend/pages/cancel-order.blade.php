@extends('frontend.layouts.app')
@section('content')

  <section class="bredcrum">
    <div class="container-fluid">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('my-orders') }}">My Orders</a></li>
        <li>Cancel Order</li>
      </ul>
    </div>
  </section>
  <div class="clearfix"></div>

 <!-- MY ORDERS STARTS -->
    <section class="myorders py-5">
      <div class="container">
        <div class="row">
      
          <div class="col-xl-10 col-lg-10 col-md-8 col-12 mt-md-0 mt-4">
            <div class="txt-box return-request-page">
                {!! Form::open(array('method' => 'POST', 'route' => array('cancel-order-submit'), 'class' => '', 'files' => 'true')) !!}
                {{ csrf_field() }}
                @if(session()->has('return'))
                <p>Your return request has been submit</p>
                @endif
                @if(session()->has('already_return'))
                <p>You have already submit request</p>
                @endif
                <div class="hd">
                  <h4 class="text-uppercase mb-0">Cancel Order</h4>
                </div>
                <div class="account-orders">
                  <h5>Choose Reason</h5>
                  <ul>
                  @php $i =0; @endphp  
                  @if($return_cancel)
                  @foreach($return_cancel as $ReturnReason)
                  @php $i++; @endphp 
                  <li><input type="radio" name="reason" @if($i == 1) checked="" @endif value="{{ $ReturnReason->message }}"><span>{{ $ReturnReason->message }}</span></li>
                  @endforeach
                  @endif
                  </ul>
                  
                  <input type="hidden" name="order_id" value="{{ $order_id }}">
                  <button type="submit">Submit</button>
                </div>
              {!! Form::close() !!}
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
  <!-- MY ORDERS ENDS -->

<style type="text/css">
.myorders h5 {
    font-size: 18px;
    font-family: 'Poppins', sans-serif;
    font-weight: 400;
} 
.myorders ul li{
    margin-bottom: 8px;
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
}
</style>

  @endsection  