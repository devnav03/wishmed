@extends('admin.layouts.master')
@section('content')
@include('admin.layouts.messages')
@php
    $route  = \Route::currentRouteName();    
@endphp
<div class="agile-grids">   
    <div class="grids">       
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">{!! lang('offer.offer') !!} <a class="btn btn-sm btn-primary pull-right" href="{!! route('offer.index') !!}"> <i class="fa fa-solid fa-arrow-left"></i> All {!! lang('offer.offer') !!}</a></h1>
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-title">
                                <h4>{!! lang('offer.offer_info') !!}</h4>                        
                            </div>
                            <div class="form-body">
                                @if($route == 'offer.create')
                                    {!! Form::open(array('method' => 'POST', 'route' => array('offer.store'), 'id' => 'ajaxSave', 'class' => '', 'files'=>'true')) !!}
                                @elseif($route == 'offer.edit')
                                    {!! Form::model($result, array('route' => array('offer.update', $result->id), 'method' => 'PATCH', 'id' => 'offer-form', 'class' => '', 'files'=>'true')) !!}
                                @else
                                    Nothing
                                @endif
                                
                                <div class="row">
                                    <div class="col-md-6">
                                         <div class="form-group"> 
                                            {!! Form::label('title', lang('Title'), array('class' => '')) !!}
                                            {!! Form::text('title', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                        </div> 
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" > 
                                            {!! Form::label('type_id', lang('Offer Type'), array('class' => '')) !!}
                                            <select class="select2 form-control1" required="true" onChange="typeid(this);" id="type_id" name="type_id">
                                            <option value="">- Select -</option> 

                                            @if(isset($result))
                                                <option @if($result->type_id == 1) selected @endif value="1">Price Based </option>  <option @if($result->type_id == 2) selected @endif value="2">Percentage Based </option>
                                            @else
                                                <option {{ old('type_id') == '1' ? 'selected' : '' }} value="1">Price Based </option>  <option {{ old('type_id') == '2' ? 'selected' : '' }} value="2">Percentage Based </option>      
                                            @endif    
                                        </select>
                                        </div>
                                    </div> 
                                    <div class="col-md-6 category" style="margin-top: 20px;display: none;">
                                        <div class="form-group"> 
                                        <label for="category_id" class="">Category</label>
                                        <select name="category_id" id="category-list" class="select2 form-control1">
                                        <option value="">-Select-</option>
                                        @foreach($Category as $cat)
                                        <option value="{{ $cat->id }}" @if(isset($result)) @if($result->category_id == $cat->id) selected @endif @endif>{{ $cat->name }}</option>
                                        @endforeach
                                        </select>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-6 discount_type" style="margin-top: 20px;display: none;">
                                        <div class="form-group"> 
                                        <label for="discount_type" class="">Discount Type</label>
                                        <select name="discount_type" onChange="yesnoCheck5(this);" class="select2 form-control1">
                                        <option value="">-Select-</option>
                                        @if(isset($result))
                                        <option @if($result->discount_type=='Price') selected @endif value="Price">Price</option>
                                        <option @if($result->discount_type=='Percentage') selected @endif} value="Percentage">Percentage</option>
                                        @else
                                        <option {{ old('discount_type') == 'Price' ? 'selected' : '' }} value="Price">Price</option>
                                        <option {{ old('discount_type') == 'Percentage' ? 'selected' : '' }} value="Percentage">Percentage</option>
                                        @endif
                                       </select>
                                        </div>
                                    </div>           
                                    <div class="col-md-6 off_amount" style="margin-top: 20px;display: none;">
                                        <div class="form-group"> 
                                            {!! Form::label('off_amount', lang('Discount Amount'), array('class' => '')) !!}
                                            {!! Form::number('off_amount', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('off_amount'))
                                             <span class="text-danger">{{$errors->first('off_amount')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 min_amount" style="margin-top: 20px;display: none;">
                                        <div class="form-group"> 
                                            {!! Form::label('min_amount', lang('Min Order Amount'), array('class' => '')) !!}
                                            {!! Form::number('min_amount', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('min_amount'))
                                             <span class="text-danger">{{$errors->first('min_amount')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 off_percentage" style="margin-top: 20px;display: none;">
                                        <div class="form-group"> 
                                            {!! Form::label('off_percentage', lang('Off Percentage'), array('class' => '')) !!}
                                            {!! Form::number('off_percentage', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('off_percentage'))
                                             <span class="text-danger">{{$errors->first('off_percentage')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 max_discount" style="margin-top: 20px;display: none;">
                                        <div class="form-group"> 
                                            {!! Form::label('max_discount', lang('Max Discount'), array('class' => '')) !!}
                                            {!! Form::number('max_discount', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('max_discount'))
                                             <span class="text-danger">{{$errors->first('max_discount')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 promo_code" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            {!! Form::label('promo_code', lang('Promo Code'), array('class' => '')) !!}
                                            {!! Form::text('promo_code', null, array('class' => 'form-control')) !!}
                                            @if ($errors->has('promo_code'))
                                             <span class="text-danger">{{$errors->first('promo_code')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            {!! Form::label('per_user', lang('Per User'), array('class' => '')) !!}
                                            {!! Form::number('per_user', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                            @if ($errors->has('per_user'))
                                             <span class="text-danger">{{$errors->first('per_user')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            {!! Form::label('max_user', lang('Max User'), array('class' => '')) !!}
                                            {!! Form::number('max_user', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                            @if ($errors->has('max_user'))
                                             <span class="text-danger">{{$errors->first('max_user')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            {!! Form::label('valid_from', lang('Valid From'), array('class' => '')) !!}
                                            {!! Form::date('valid_from', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                            @if ($errors->has('valid_from'))
                                             <span class="text-danger">{{$errors->first('valid_from')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            {!! Form::label('valid_to', lang('Valid To'), array('class' => '')) !!}
                                            {!! Form::date('valid_to', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                            @if ($errors->has('valid_to'))
                                             <span class="text-danger">{{$errors->first('valid_to')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="margin-top: 20px;">
                                        <div class="form-group"> 
                                            {!! Form::label('Message', lang('Message'), array('class' => '')) !!}
                                            {!! Form::text('message', null, array('class' => 'form-control', 'required' => 'true')) !!}
                                            @if ($errors->has('message'))
                                             <span class="text-danger">{{$errors->first('message')}}</span>
                                            @endif
                                        </div>
                                    </div>                                    
                                     
                                </div>
                                <div class="clearfix"></div>
                                <div class="row">
                                    <p>&nbsp;</p>
                                    <div class="col-md-12">
                                         <button type="submit" class="btn btn-default w3ls-button">Submit</button> 
                                    </div>
                                </div>
                                    
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function getSubcategory(val) {
  $.ajax({
    type: "GET",
    url: "{{ route('getSubcategory') }}",
    data: {'main_id' : val},
    success: function(data){
        $("#category-list").html(data);
    }
  });
}
</script>
<script type="text/javascript">
function typeid(that) {
    if (that.value == "3") {
        $(".product").show();
        $(".discount_type").show();
        $(".off_amount").hide();
        $(".min_amount").hide();
        $(".off_percentage").hide();
        $(".max_discount").hide();
        $(".brand").hide();
        $(".category").hide();
        $(".sub_product").hide();

    } else if (that.value == "2") {
        $(".product").hide();
        $(".brand").hide();
        $(".category").hide();
        $(".off_percentage").show();
        $(".max_discount").show();
        $(".off_amount").hide();
        $(".discount_type").hide();
        $(".min_amount").show();
        $(".sub_product").hide();

    } else if (that.value == "4") {
        $(".product").hide();
        $(".brand").hide();
        $(".off_percentage").hide();
        $(".category").show();
        $(".discount_type").show();
        $(".max_discount").hide();
        $(".off_amount").hide();
        $(".min_amount").hide();
        $(".sub_product").hide();
    }
    else if (that.value == "1") {
        $(".off_amount").show();
        $(".min_amount").show();
        $(".product").hide();
        $(".category").hide();
        $(".brand").hide();
        $(".discount_type").hide();
        $(".off_percentage").hide();
        $(".max_discount").hide();
        $(".sub_product").hide();

    } else if (that.value == "7") {
        $(".off_amount").hide();
        $(".min_amount").hide();
        $(".brand").show();
        $(".product").hide();
        $(".category").hide();
        $(".discount_type").show();
        $(".off_percentage").hide();
        $(".max_discount").hide();
        $(".sub_product").hide();
    }
     else if (that.value == "6") {
        $(".off_amount").hide();
        $(".min_amount").hide();
        $(".brand").hide();
        $(".product").show();
        $(".category").hide();
        $(".discount_type").hide();
        $(".off_percentage").hide();
        $(".max_discount").hide();
        $(".sub_product").show();
    }
}


</script>

<script type="text/javascript">
function yesnoCheck5(that) {
    if (that.value == "Price") {
        $(".off_amount").show();
        $(".min_amount").show();
        $(".off_percentage").hide();
        $(".max_discount").show();
   
    } else {
        $(".off_amount").hide();
        $(".min_amount").show();
        $(".off_percentage").show();
        $(".max_discount").show();
    }
}
</script>
@if(isset($result))
@if($result->type_id == 1)
<script type="text/javascript">
$(".off_amount").show();
$(".min_amount").show();
$(".max_discount").show();
</script>
@endif
@if($result->type_id == 2)
<script type="text/javascript">
$(".off_percentage").show();
$(".max_discount").show();
$(".min_amount").show();
</script>
@endif
@if($result->type_id == 3)
<script type="text/javascript">
$(".product").show();
$(".discount_type").show();
</script>
@if($result->discount_type == 'Price')
<script type="text/javascript">
$(".off_amount").show();
$(".min_amount").show();
$(".max_discount").show();
</script>
@else
<script type="text/javascript">
$(".off_percentage").show();
$(".max_discount").show();
$(".min_amount").show();
</script>
@endif
@endif
@if($result->type_id == 4)
<script type="text/javascript">
$(".category").show();
$(".discount_type").show();
</script>
@if($result->discount_type == 'Price')
<script type="text/javascript">
$(".off_amount").show();
$(".min_amount").show();
$(".max_discount").show();
</script>
@else
<script type="text/javascript">
$(".off_percentage").show();
$(".max_discount").show();
$(".min_amount").show();
</script>
@endif
@endif
@if($result->type_id == 6)
<script type="text/javascript">
$(".product").show();
$(".sub_product").show();
</script>
@endif
@if($result->type_id == 7)
<script type="text/javascript">
$(".brand").show();
$(".discount_type").show();
</script>
@if($result->discount_type == 'Price')
<script type="text/javascript">
$(".off_amount").show();
$(".min_amount").show();
$(".max_discount").show();
</script>
@else
<script type="text/javascript">
$(".off_percentage").show();
$(".max_discount").show();
$(".min_amount").show();
</script>
@endif
@endif
@endif


@stop

