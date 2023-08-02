@extends('admin.layouts.master')
@section('content')
  <div class="social grid" style="float: left;padding-bottom: 15px;">
    <h3 style="padding: 15px;background: #1E1D1B;color: #fff;font-size: 20px;">Finance Report</h3>
    <div class="grid-info">
        <div class="col-md-3 top-comment-grid">
            <div class="comments">
                <div class="comments-icon">
                    <i class="fa fa-inr" style="color: #fff;"></i>
                </div>
                <div class="comments-info">
                    <h3>{!! $total_price !!}</h3>
                    <a href="#">Total Sale</a>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
        <div class="col-md-3 top-comment-grid">
            <div class="comments tweets">
                <div class="comments-icon">
                    <i class="fa fa-inr" style="color: #fff;"></i>
                </div>
                <div class="comments-info tweets-info">
                    <h3>{{ $current_price }}</h3>
                    <a href="#">Current Month Sale</a>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
        <div class="col-md-3 top-comment-grid">
            <div class="comments skyb" style="margin-top: 0px;">
                <div class="comments-icon">
                    <i class="fa fa-inr" style="color: #9DCB !important;"></i>
                </div>
                <div class="comments-info views-info">
                    <h3> {{ $total_tax }} </h3>
                    <a href="#" style="color: #9DCB !important;">Total Tax</a>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
        <div class="col-md-3 top-comment-grid">
            <div class="comments likes">
                <div class="comments-icon">
                    <i class="fa fa-inr"></i>
                </div>
                <div class="comments-info likes-info">
                    <h3>{{ $current_tax }}</h3>
                    <a href="#">Current Month<br> Tax</a>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
        <div class="col-md-3 top-comment-grid" style="margin-top: 15px;">
            <div class="comments tweets">
                <div class="comments-icon">
                   <i class="fa fa-inr" style="color: #fff;"></i>
                </div>
                <div class="comments-info tweets-info">
                    <h3>{{ $shipping }}</h3>
                    <a href="#">Total Shipping</a>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
        <div class="col-md-3 top-comment-grid">
            <div class="comments views">
                <div class="comments-icon">
                    <i class="fa fa-inr"></i>
                </div>
                <div class="comments-info views-info">
                    <h3> {{ $current_shipping }} </h3>
                    <a href="#">Current Month<br> Shipping</a>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
        <div class="col-md-3 top-comment-grid">
            <div class="comments likes">
                <div class="comments-icon">
                    <i class="fa fa-inr"></i>
                </div>
                <div class="comments-info likes-info">
                    <h3>{!! $discount !!}</h3>
                    <a href="#">Total Discount</a>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
        <div class="col-md-3 top-comment-grid">
            <div class="comments">
                <div class="comments-icon">
                    <i class="fa fa-inr" style="color: #fff;"></i>
                </div>
                <div class="comments-info">
                    <h3>{!! $current_discount !!}</h3>
                    <a href="#">Current Month Discount</a>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
        <div class="clearfix"> </div>
    </div>
    <h3 style="padding: 15px;background: #1E1D1B;color: #fff;font-size: 20px;">Payment</h3>
    <div class="grid-info">
        <div class="col-md-3 top-comment-grid">
            <div class="comments likes">
                <div class="comments-icon">
                    <i class="fa fa-inr"></i>
                </div>
                <div class="comments-info likes-info">
                    <h3>{!! $deposit !!}</h3>
                    <a href="#">Bank Deposits</a>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
        <div class="col-md-3 top-comment-grid">
            <div class="comments">
                <div class="comments-icon">
                    <i class="fa fa-inr" style="color: #fff;"></i>
                </div>
                <div class="comments-info">
                    <h3>{!! $cash !!}</h3>
                    <a href="#">Cash</a>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
      </div>
  </div>
@endsection