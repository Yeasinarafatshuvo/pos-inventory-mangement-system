@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/> 
@section('content')
<style>

</style>
<div id="divName">
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
            <h2 class="bg-primary  text-center" style="color:white;">PRODUCT WISE RECEIVE AMOUNT REPORT {{$start_date}} To {{$end_date}}</h2>
            </div>
        </div>
    </div>
    <div class="card">
     
        <div class="card-body"> 
            <div class="row">
                <div class="col-md-12 pr-3">
                    <table id="dtBasicExample" class="table table-striped table-bordered table-sm p-2 text-center" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                            <th scope="col">SL</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Sale QTY</th>
                            <th scope="col">Total Price</th>
                          </tr>
                        </thead>
                        <tbody>
                            @php 
                                $total_quantity =0;
                                $total_sale =0;
                                $special_discount_sub = 0;
                            @endphp
                            
                            @foreach ($product_highest_sale_data_by_date_range as $key => $item)
                            @php 
                                $total_quantity += $item->quantity;
                                $total_sale += $item->total *$item->quantity;
                                if(!empty($item->special_discount)){
                                   $special_discount_sub += $item->special_discount;
                                }
                                
                            @endphp
                            <tr>
                               <th scope="row">{{$key +1}}</th>
                               {{-- <td>{{$item->product->name}}</td> --}}
                               <td>{{$item->name}}</td>
                               <td>{{$item->quantity}}</td>
                               <td>{{"৳".$item->total * $item->quantity }}</td>
                             </tr>
                            @endforeach
                             <tr>
                               <th scope="row"></th>
                               <td style="text-align:right;"><b>Without Special Discount sub total</b></td>
                               <td>{{$total_quantity}}</td>
                               <td>{{"৳".$total_sale}}</td>
                             </tr>
                             
                             <tr>
                               <th scope="row"></th>
                               <td style="text-align:right;"><b>With Special Discount sub total</b></td>
                               <td>{{$total_quantity}}</td>
                               <td>{{"৳".($total_sale - $special_discount_sub )}}</td>
                             </tr>
                           </tbody>
                      </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="text-center">
    <button class="btn btn-primary btn-sm" onclick="printpage('divName')">print</button>
</div>
@endsection




@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script type="text/javascript">
// $(document).ready(function () {
//     $('#dtBasicExample').DataTable();
//     $('.dataTables_length').addClass('bs-select');

// });


function printpage(divName){


var printContents = document.getElementById(divName).innerHTML;
var originalContents = document.body.innerHTML;

document.body.innerHTML = printContents;
$('.dataTables_filter').remove();
$('#dtBasicExample_length').remove();
$('#dtBasicExample_info').remove();
$('#dtBasicExample_paginate').remove();
window.print();

document.body.innerHTML = originalContents;

}





</script>
@endsection
