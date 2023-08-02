@extends('admin.layouts.master')
@section('content')

<div class="agile-grids">   
    <div class="grids">       
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header" style="font-size: 22px;font-weight: 500;">Out Of Stock Products</h1>
                
                <div class="panel panel-widget forms-panel">
                    <div class="forms">
                        <div class="form-grids widget-shadow" data-example-id="basic-forms"> 
                            <div class="form-body">
                                
                                    
                         
<table class="table table-hover" style="border-top: 2px solid #DDDDDD;border-bottom: 2px solid #DDDDDD;">                          
<thead>
<tr>
    <th style="text-align: left;border-right: 1px solid #DDDDDD;border-left: 1px solid #DDDDDD;text-align: center;">#</th>
    <th style="text-align: left;border-right: 1px solid #DDDDDD;">Name</th>
    <th style="text-align: left;border-right: 1px solid #DDDDDD;">Code</th>
    <th style="text-align: left;border-right: 1px solid #DDDDDD;">Type</th>
    <th style="text-align: left;border-right: 1px solid #DDDDDD;">Brand</th>
    <th style="text-align: left;border-right: 1px solid #DDDDDD;">Category</th>
    <th style="text-align: center;border-right: 1px solid #DDDDDD;">Action</th>
</tr>
</thead>
<tbody>
<?php $i = 1; ?>

@foreach($products as $product)
<tr>
    <td style="border-right: 1px solid #DDDDDD;border-left: 1px solid #DDDDDD;text-align: center;">{{ $i }}</td>
    <td style="border-right: 1px solid #DDDDDD;">{{ $product->name }}</td>
    <td style="border-right: 1px solid #DDDDDD;">{{ $product->code }}</td>
    <td style="border-right: 1px solid #DDDDDD;">{{ $product->type }}</td>
    <td style="border-right: 1px solid #DDDDDD;">{{ $product->brand }}</td>
    <td style="border-right: 1px solid #DDDDDD;">{{ $product->category }}</td>
    <td style="text-align: center;"><a class="btn btn-xs btn-primary" href="{{ route('product.edit', [$product->pid]) }}"><i class="fa fa-edit"></i></a></td>
</tr>
<?php $i++; ?>
@endforeach
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