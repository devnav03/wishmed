@extends('frontend.layouts.app')
@section('content')


<!-- BREAD-CRUMBS STARTS -->
    <section class="bredcrum">
      <div class="container">
              <ul>
                <li><a href="{{ route('home')}}">Home</a></li>
                <li>&nbsp;/&nbsp; Form</li>
              </ul>
        </div>
    </section>
  <!-- BREAD-CRUMBS ENDS -->

  <div class="clearfix"></div>

  <!-- RETURN-POLICY-MAIN STARTS -->
    <section class="form-index">
      <div class="container">
        <div class="row">
          <div class="col-md-8 offset-md-2">
            <h3>Fill Online & Download Form</h3>
            <p style="text-align: center; color: #6d6d6d;">Please fax all forms to 1.323.233.2151 Or email orders@pukacreations.com</p>
              <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="text-align: center;">S.No</th>
                      <th style="text-align: left;">Form Name</th>
                     <!-- <th>Submit Online</th> -->
                      <th>Process Offline</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- <tr>
                      <th style="vertical-align: middle;text-align: center;">1</th>
                      <td style="vertical-align: middle;">Order Form</td>
                     <td style="vertical-align: middle;text-align: center;"><a class="ps-btn new newa" href="{{ route('order-form') }}"><i class="fa fa-globe"></i>Start</a></button></td> 
                      <td style="vertical-align: middle;text-align: center;"><button class="ps-btn new2"><a class="newa" href="{{ asset('assets/frontend/images/orderform.pdf') }}" target="_blank" download><i class="fa fa-download"></i>Download</a></button></td>
                    </tr>
                    <tr>
                      <th style="vertical-align: middle;text-align: center;">2</th>
                      <td style="vertical-align: middle;">Independent Sales Rep Agreement</td>
                     <!-- <td style="vertical-align: middle;text-align: center;"><a class="ps-btn new newa" href="{{ route('independent-sales-rep-agreement') }}"><i class="fa fa-globe"></i>Start</a></td>                       <td style="vertical-align: middle;text-align: center;"><button class="ps-btn new2"><a class="newa" href="{{ asset('assets/frontend/images/Rep-Agreement.pdf') }}" target="_blank" download><i class="fa fa-download"></i>Download</a></button></td>
                    </tr>
                    <tr>
                      <th style="vertical-align: middle;text-align: center;">3</th>
                      <td style="vertical-align: middle;">Credit Card Authorization Form</td>
                     <!-- <td style="vertical-align: middle;text-align: center;"><a class="ps-btn new newa" href="{{ route('credit-card-authorization-form') }}"><i class="fa fa-globe"></i>Start</a></td>                       <td style="vertical-align: middle;text-align: center;"><button class="ps-btn new2"><a class="newa" href="{{ asset('assets/frontend/images/CreditCardAuthorizationform.pdf') }}" target="_blank" download><i class="fa fa-download"></i>Download</a></button></td>
                    </tr>
                    <tr>
                      <th style="vertical-align: middle;text-align: center;">4</th>
                      <td style="vertical-align: middle;">Credit Terms Application Form</td>
                      <!-- <td style="vertical-align: middle;text-align: center;"><a class="ps-btn new newa" href="{{ route('credit-terms-application-form') }}"><i class="fa fa-globe"></i>Start</a></td>                       <td style="vertical-align: middle;text-align: center;"><button class="ps-btn new2"><a class="newa" href="{{ asset('assets/frontend/images/CreditTermsApplication.pdf') }}" target="_blank" download><i class="fa fa-download"></i>Download</a></button></td>
                    </tr>
                    <tr>
                      <th style="vertical-align: middle;text-align: center;">5</th>
                      <td style="vertical-align: middle;">Credit Reference Form</td>
                     <!-- <td style="vertical-align: middle;text-align: center;"><a class="ps-btn new newa" href="{{ route('credit-reference-form') }}"><i class="fa fa-globe"></i>Start</a></td>                       <td style="vertical-align: middle;text-align: center;"><button class="ps-btn new2"><a class="newa" href="{{ asset('assets/frontend/images/credit-reference.pdf') }}" target="_blank" download><i class="fa fa-download"></i>Download</a></button></td>
                    </tr>
                    <tr>
                      <th style="vertical-align: middle;text-align: center;">6</th>
                      <td style="vertical-align: middle;">e.Catalog – Jewelry</td>
                      <!-- <td style="vertical-align: middle;text-align: center;"><a class="ps-btn new newa" href="https://issuu.com/pukacreations/docs/2022_catalog_jewellry" target="_blank"><i class="fa fa-globe"></i>Start</a></td>                       <td style="vertical-align: middle;text-align: center;"><button class="ps-btn new2"><a class="newa" href="https://issuu.com/pukacreations/docs/2022_catalog_jewellry" target="_blank"><i class="fa fa-download"></i>Download</a></button></td>
                    </tr>
                    <tr>
                      <th style="vertical-align: middle;text-align: center;">7</th>
                      <td style="vertical-align: middle;">e.Catalog – Novelties</td>
                      <!-- <td style="vertical-align: middle;text-align: center;"><a class="ps-btn new newa" href="https://issuu.com/pukacreations/docs/2022_catalog_novelties" target="_blank"><i class="fa fa-globe"></i>Start</a></td>                       <td style="vertical-align: middle;text-align: center;"><button class="ps-btn new2"><a class="newa" href="https://issuu.com/pukacreations/docs/2022_catalog_novelties" target="_blank"><i class="fa fa-download"></i>Download</a></button></td>
                    </tr>
                    <tr>
                      <th style="vertical-align: middle;text-align: center;">8</th>
                      <td style="vertical-align: middle;">2022 Pricelist Excel Format</td>
                     <!-- <td style="vertical-align: middle;text-align: center;"><button class="ps-btn new"><a class="newa" href="#" data-toggle="modal" data-target="#formModal"><i class="fa fa-globe"></i>Start</a></button></td>                      <td style="vertical-align: middle;text-align: center;"><button class="ps-btn new2"><a class="newa" href="{{ asset('assets/frontend/images/2022PriceList-March-7-2022.xlsx') }}" target="_blank" download><i class="fa fa-download"></i>Download</a></button></td>
                    </tr>
                    <tr>
                      <th style="vertical-align: middle;text-align: center;">9</th>
                      <td style="vertical-align: middle;">N30 Agreement</td>
                     <!-- <td style="vertical-align: middle;text-align: center;"><a class="ps-btn new newa" href="{{ route('n30-agreement') }}"><i class="fa fa-globe"></i>Start</a></td>                      <td style="vertical-align: middle;text-align: center;"><button class="ps-btn new2"><a class="newa" href="{{ asset('assets/frontend/images/N30Agreement.pdf') }}" target="_blank" download><i class="fa fa-download"></i>Download</a></button></td>
                    </tr>
                    <tr>
                      <th style="vertical-align: middle;text-align: center;">10</th>
                      <td style="vertical-align: middle;">Personal Guarantee Letter</td>
                     <!-- <td style="vertical-align: middle;text-align: center;"><a class="ps-btn new newa" href="{{ route('personal-guarantee-letter') }}"><i class="fa fa-globe"></i>Start</a></td>                       <td style="vertical-align: middle;text-align: center;"><button class="ps-btn new2"><a class="newa" href="{{ asset('assets/frontend/images/PERSONAL-GUARANTEE.pdf') }}" target="_blank" download><i class="fa fa-download"></i>Download</a></button></td>
                    </tr> -->
                    @php 
                    $i = 0;
                    @endphp
                    @foreach($forms as $form)
                    @php 
                    $i++;
                    @endphp

                    <tr>
                      <th style="vertical-align: middle;text-align: center;">{{ $i }}</th>
                      <td style="vertical-align: middle;">{{ $form->title }}</td>
                      <td style="vertical-align: middle;text-align: center;"><button class="ps-btn new2"><a class="newa" href=" @if($form->file) {{ route('home') }}{{ $form->file }} @else {{ $form->link }} @endif " target="_blank" download><i class="fa fa-download"></i>Download</a></button></td>
                    </tr> 
                    @endforeach
                    <tr>
                      <th style="vertical-align: middle;text-align: center;">{{ $i + 1 }}</th>
                      <td style="vertical-align: middle;">Instruction Videos & Presentations</td>
                     <!-- <td style="vertical-align: middle;text-align: center;"><button class="ps-btn new"><a class="newa" href="{{ route('instruction-videos') }}"><i class="fa fa-eye"></i>View</a></button></td> -->
                      <td style="vertical-align: middle;text-align: center;"><button class="ps-btn new2"><a class="newa" href="{{ route('instruction-videos') }}" target="_blank"><i class="fa fa-eye"></i>View</a></button></td>
                    </tr>
                  </tbody>
              </table>
          </div>
        </div>
      </div>
    </section>
  <!-- RETURN-POLICY-MAIN ENDS -->

@endsection  