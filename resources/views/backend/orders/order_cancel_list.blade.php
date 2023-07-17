@extends('backend.layouts.app')
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/> 
@section('content')
<style>

</style>

<div id="print_area">
    
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
            <h2 class="bg-primary  text-center" style="color:white;">PRODUCT CANCEL REASON LIST</h2>
            </div>
        </div>
    </div>
    <div class="card"  id="search_date">
        <form action="{{route('orders.cancel_search_by_date')}}" method="POST">
            @csrf
               <div class="row pb-2" style="background-color: #F2F2F2;margin-left:0; margin-right:0">
                <div class="col-md-2"></div>
                   <div class="col-md-1 pt-3 font-weight-bold" style="padding-right: 0px;">Start Date:</div>
                   <div class="col-md-2 pt-2" style="padding-left:0"><input type="text" id="datepicker" name="start_date" required class="form-control"></div>
                   <div class="col-md-1 pt-3 font-weight-bold" style="padding-right: 0px;">End Date:</div>
                   <div class="col-md-2 pt-2" style="padding-left:0"><input type="text" id="datepickertwo" name="end_date" required class="form-control"></div>
                   <div class="col-md-2 pt-2"><button class="btn btn-primary btn-md">Search By Date</button></div>
               </div>
        </form>
    </div>
    <div class="card">
     
        <div class="card-body"> 
        <div class="row">
        <div class="col-md-12 pr-3">
            <table id="dtBasicExample" class="table table-striped table-bordered table-sm p-2 text-center" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th scope="col">SL</th>
                    <th scope="col">Invoice Number</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Customer Phone</th>
                    <th scope="col">Reason Of Cancel</th>
                    <th scope="col">Date</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($order_cancel_details as $key =>$cancel_reason_item)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$cancel_reason_item->combined_order->code}}</td>
                            <td>{{$cancel_reason_item->user->name}}</td>
                            @php
                                if(!empty($cancel_reason_item->billing_address)){
                                $user_shipping_info = json_decode($cancel_reason_item->billing_address);
                                }
                            @endphp
                            <td>
                                @if(!empty($cancel_reason_item->billing_address))
                                {{$user_shipping_info->phone}}
                                @endif
                            </td>
                            <td>{{$cancel_reason_item->cancel_reason}}</td>
                            <td>{{date('Y-m-d',strtotime($cancel_reason_item->updated_at))}}</td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
        </div>
        </div>
        </div>
    </div>
</div>
<div class="text-center">
    <button class="btn btn-primary btn-sm" onclick="printpage('print_area')">print</button>
</div>

@endsection




@section('script')
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('#dtBasicExample').DataTable();
    $('.dataTables_length').addClass('bs-select');

});

//date picker package 
$( function() {
    $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
    
});
$( function() {
    $( "#datepickertwo" ).datepicker({ dateFormat: 'yy-mm-dd' });
});


function printpage(print_area){


var printContents = document.getElementById(print_area).innerHTML;
var originalContents = document.body.innerHTML;

document.body.innerHTML = printContents;
$('.dataTables_filter').remove();
$('#dtBasicExample_length').remove();
$('#dtBasicExample_info').remove();
$('#dtBasicExample_paginate').remove();
$('#search_date').hide();
window.print();

document.body.innerHTML = originalContents;

}




</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
