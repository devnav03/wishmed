@extends('frontend.layouts.app')
@section('content')
  
   <section class="bredcrum">
    <div class="container">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>&nbsp;/ &nbsp;Tradeshow</li>
      </ul>
    </div>
  </section>
  <div class="clearfix"></div>

<div id="add-cart"></div>
  <!-- PRODUCT-LISTING-PANEL STARTS -->
    <section class="product-listing tradeshows" style="margin-bottom: 55px;">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-3 d-md-block d-none" style="margin-top: 15px;">
            <aside>
            <div class="accordion" id="accordionExample">        
              <div class="card">
                <div class="card-header" id="headingOne1">
                  <h5>Categories</h5>
                </div>
                  <div class="card-body">
                    <ul id="selective">
                      @if($Categorys)
                      @foreach($Categorys as $category)
                      <li><a href="{{ route('categoryDetail', $category->url) }}">{{ $category->name}}</a></li>
                      @endforeach
                      @endif
                    </ul>
                  </div>
              </div>
              </div>
            </aside>
          </div>
          
          <div class="col-lg-9 col-md-9" id="category-list">
            <h3>Tradeshows</h3>
            @if($tradeshows)
            <table class="table table-striped">
            <tr>
              <th align="center" style="width: 230px;">Show</th>
              <th align="center">Place</th>
              <th align="center">Booth</th>
              <th align="center" style="width: 145px;">From</th>
              <th align="center" style="width: 147px;">To</th>
            </tr>
            @foreach($tradeshows as $tradeshow)
            <tr>  
              <td>{{ $tradeshow->name }}</td>
              <td>{{ $tradeshow->place }}</td> 
              <td>{{ $tradeshow->booth }}</td> 
              <td>{!! date('M d, Y', strtotime($tradeshow->from_date)) !!}</td>
              <td>{!! date('M d, Y', strtotime($tradeshow->to_date)) !!}</td>
            </tr>
            @endforeach
            </table>
            @endif
          </div>
        </div>
      </div>
</section>
@endsection