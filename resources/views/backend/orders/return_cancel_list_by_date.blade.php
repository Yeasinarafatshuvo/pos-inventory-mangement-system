@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/> 
@section('content')
<style>

</style>

<div id="print_area">
    
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
            <h2 class="bg-primary  text-center" style="color:white;">PRODUCT CANCEL REASON LIST FROM {{$start_date}} TO {{$end_date}}</h2>
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
                    <th scope="col">Invoice Number</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Customer Phone</th>
                    <th scope="col">Reason Of Cancel</th>
                    <th scope="col">Date</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($order_cancel_details_by_date as $key =>$cancel_reason_item)
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
