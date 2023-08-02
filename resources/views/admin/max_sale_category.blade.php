@extends('admin.layouts.master')
@section('content')

<div class="agile-grids">   
    <div class="grids">       
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header" style="font-size: 22px;font-weight: 500;">Max Sale Category Wise</h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-body">
                                
                                    
                         
<table class="table table-hover" style="border-top: 2px solid #DDDDDD;border-bottom: 2px solid #DDDDDD;">                          
<thead>
<tr>
    <th style="text-align: left;border-right: 1px solid #DDDDDD;border-left: 1px solid #DDDDDD;text-align: center;">#</th>
    <th style="text-align: left;border-right: 1px solid #DDDDDD;">Category Name</th>
    <th style="text-align: center;border-right: 1px solid #DDDDDD;">Total Order</th>
    <th style="text-align: center;border-right: 1px solid #DDDDDD;">Quantity</th>
    <th style="text-align: center;border-right: 1px solid #DDDDDD;">Action</th>
</tr>
</thead>
<tbody>
<?php $i = 1; ?>

@if(isset($OrderProducts))
@foreach($OrderProducts as $OrderProduct)
@php
$pro = get_max_sale_cat($OrderProduct->category_id);

@endphp

<tr>
    <td style="border-right: 1px solid #DDDDDD;border-left: 1px solid #DDDDDD;text-align: center;">{{ $i }}</td>
    <td style="border-right: 1px solid #DDDDDD;">{{ $pro->name }}</td>
    <td style="border-right: 1px solid #DDDDDD;text-align: center;">{{ $OrderProduct->total_products }}</td>
    <td style="border-right: 1px solid #DDDDDD;text-align: center;">{{ $OrderProduct->max_qty }}</td>
    <td style="text-align: center;"><a class="btn btn-xs btn-primary" href="{{ route('category.edit', [$OrderProduct->category_id]) }}"><i class="fa fa-edit"></i></a></td>
</tr>
<?php $i++; ?>
@endforeach
@endif
</tbody>
</table>                      
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection