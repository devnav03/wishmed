@extends('admin.layouts.master')
@section('content')
@php
    $route  = \Route::currentRouteName();    
@endphp
<div class="agile-grids">   
    <div class="grids">       
        <div class="row">
            <div class="col-md-10">
                <h1 class="page-header">Customer <a class="btn btn-sm btn-primary pull-right" href="{!! route('customer') !!}"> <i class="fa fa-arrow-left"></i> All Customers </a></h1>
                <div class="panel panel-widget forms-panel" style="float: left;width: 100%; padding-bottom: 20px;">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>Customer Information</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'customer.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('customer.store'), 'id' => 'ajaxSave', 'class' => '')) !!}
                                @elseif($route == 'customer.edit')
                                    {!! Form::model($result, array('route' => array('customer.update', $result->id), 'method' => 'PATCH', 'id' => 'customer-form', 'class' => '')) !!}
                                @else
                                    Nothing
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group"> 
                                            {!! Form::label('first_name', lang('First Name'), array('class' => '')) !!}
                                            @if(!empty($result->id))
                                                {!! Form::text('first_name', null, array('class' => 'form-control', 'required'=> 'true')) !!}
                                            @else
                                                {!! Form::text('first_name', null, array('class' => 'form-control', 'required'=> 'true')) !!}
                                            @endif 
                                        </div> 
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"> 
                                            {!! Form::label('last_name', lang('Last Name'), array('class' => '')) !!}
                                            @if(!empty($result->id))
                                                {!! Form::text('last_name', null, array('class' => 'form-control', 'required'=> 'true')) !!}
                                            @else
                                                {!! Form::text('last_name', null, array('class' => 'form-control', 'required'=> 'true')) !!}
                                            @endif 
                                        </div> 
                                    </div>
                                    <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            {!! Form::label('email', lang('common.email'), array('class' => '')) !!}
                                            @if(!empty($result->id))
                                                {!! Form::email('email', null, array('class' => 'form-control','readonly')) !!}
                                            @else
                                                {!! Form::email('email', null, array('class' => 'form-control', 'required'=> 'true')) !!}
                                            @endif 
                                            @if($errors->has('email'))
                                             <span class="text-danger">{{$errors->first('email')}}</span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            {!! Form::label('mobile', lang('Mobile'), array('class' => '')) !!}
                                            
                                            @if(!empty($result->id))
                                                {!! Form::number('mobile', null, array('class' => 'form-control', 'required'=> 'true')) !!}
                                            @else
                                                <input class="form-control" pattern="\d*" maxlength="10" minlength="10" required="true" name="mobile" type="text" id="mobile">
                                                
                                            @endif
                                            @if($errors->has('mobile'))
                                             <span class="text-danger">{{$errors->first('mobile')}}</span>
                                            @endif
                                            
                                        </div> 
                                    </div>
                                    
                                    <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                        <label>User Type</label>
                                            <select name="user_type" class="select2 form-control1" required="true">
                                                <option value="">-Select-</option>
                                                @if(isset($result))
                                                <option @if($result->user_type==3) selected @endif value="3">Admin</option>
                                                <option @if($result->user_type==2) selected @endif} value="2">Customer</option>
                                                @else
                                                <option {{ old('user_type') == '3' ? 'selected' : '' }} value="3">Admin</option>
                                                <option {{ old('user_type') == '2' ? 'selected' : '' }} value="2">Customer</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    @if(isset($result))
                                    @else

                               <!--      <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group" style="position: relative;"> 
                                          {!! Form::label('password', lang('Password'), array('class' => '')) !!}
                                            {!! Form::password('password', null, array('class' => 'form-control', 'required'=> 'true')) !!}
                                            <i class="far fa-eye" id="togglePassword" style="margin-left: -30px; position: absolute; right: 10px; top: 30px; cursor: pointer;"></i>
                                            <span style="font-size: 12px;">Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters</span>
                                            @if($errors->has('password'))
                                             <span class="text-danger"><br>{{$errors->first('password')}}</span>
                                            @endif
                                        </div> 
                                    </div> -->
                                    @endif
                                    <input type="hidden" value="normal" name="provider">
                                    <div class="col-md-12" style="margin-top: 20px;"> 
                                    <button type="submit" class="btn btn-default w3ls-button">Submit</button> 
                                    </div> 
                            </div>
                                    
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>

                @if(isset($user_address))
                <div class="panel panel-widget forms-panel" style="float: left;width: 100%; padding-bottom: 20px;">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>Customer Address</h4>                        
                            </div>
                            <div class="form-body">
                              
                              <div class="row" id="dform">
              @php $i = 0;  @endphp
              @foreach($user_address as $user_address)
              @php $i++;  @endphp
              @if(isset($user_address->name))
              <div class="col-md-4">
                <div class="user_addresse_box">
                <h6> {{ $user_address->name }} </h6>
                <p> {{ $user_address->address }}, {{ $user_address->city }}, 
                  {{ $user_address->state }} - {{ $user_address->pincode }} <br> Phone: {{ $user_address->mobile }} </p>
              </div>
              </div>
              @endif
              @endforeach
              
            </div>
            @if($i == 0)
            <p>No shipping address added</p>
            @endif
                            </div>
                        </div>
                    </div>
                </div>
              @endif  

          @if(isset($Orders))   
          <div class="panel panel-widget forms-panel" style="float: left;width: 100%; padding-bottom: 20px;">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>Customer Recent Orders</h4>                        
                            </div>
                            <div class="form-body">
                              
                              <div class="row" id="dform">
                <table style="width: 100%">                
                @php $k = 0;  @endphp
                    <tr>
                        <td style="border: 1px solid #f3f3f3; padding: 10px;text-align: center;"><b>#</b></td>
                        <td style="border: 1px solid #f3f3f3; padding: 10px;text-align: center;"><b>Order No.</b></td>
                        <td style="border: 1px solid #f3f3f3; padding: 10px;text-align: center;"><b>Price</b></td>
                        <td style="border: 1px solid #f3f3f3; padding: 10px;text-align: center;"><b>Status</b></td>
                 
                        <td style="border: 1px solid #f3f3f3; padding: 10px;text-align: center;"><b>Action</b></td>
                    </tr>
              @foreach($Orders as $Order)
              @php $k++;  @endphp
              @if(isset($Order->order_nr))
            <tr>
               <td style="border: 1px solid #f3f3f3; padding: 10px;text-align: center;">{{ $k }}</td>
               <td style="border: 1px solid #f3f3f3; padding: 10px;text-align: center;">{{ $Order->order_nr }}</td>
               <td style="border: 1px solid #f3f3f3; padding: 10px;text-align: center;"> <i class="fa fa-dollar-sign"></i>{{ $Order->total_price }}</td>
               <td style="border: 1px solid #f3f3f3; padding: 10px;text-align: center;"> {!! $Order->type !!} </td>
     
               <td style="border: 1px solid #f3f3f3; padding: 10px;text-align: center;"><a class="btn btn-xs btn-primary" href="{{ route('order.edit', [$Order->id]) }}"><i class="fa fa-edit"></i></a></td>
            </tr>

              @endif
              @endforeach
             
            </table>
            </div>
            @if($k == 0)
            <p>No Order</p>
            @endif

                            </div>
                        </div>
                    </div>
                </div>
          @endif

            </div>
        </div>
    </div>
</div>

<script>
const togglePassword = document.querySelector('#togglePassword');
  const password = document.querySelector('#password');

  togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
});
</script>
@stop

