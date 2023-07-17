@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/> 
@section('content')
<style>

</style>
<div id="divName">
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
            <h2 class="bg-primary  text-center" style="color:white;">RETURN PRODUCT LIST</h2>
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
                    <th scope="col">view</th>
                  </tr>
                </thead>
                <tbody>
                 @foreach ($return_product_invoices as $key => $invoices)
                 <tr>
                    <th scope="row">{{$key +1}}</th>
                    <td>{{$invoices->invoice_number}}</td>
                    <td>
                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('orders.return_product_list_details',$invoices->invoice_number)}}" title="View">
                            <i class="las la-eye"></i>
                        </a>
                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Print Return Invoice') }}" href="javascript:void(0)" onclick="print_invoice('{{ route('orders.return_product_list_invoices_print',$invoices->invoice_number) }}')">
                            <i class="las la-print"></i>
                        </a>
                        <i class="las la-trash btn-soft-danger btn-icon btn-circle btn-sm" style="cursor: pointer" onclick="delete_return_product('<?php echo $invoices->invoice_number ?>')"></i>
                    </td>
                  </tr>
                 @endforeach
                </tbody>
              </table>
        </div>
        </div>
        </div>
    </div>
</div>

@endsection




@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('#dtBasicExample').DataTable();
    $('.dataTables_length').addClass('bs-select');


});

function print_invoice(url) {
    var h = $(window).height();
    var w = $(window).width();
    window.open(url, '_blank', 'height=' + h + ',width=' + w + ',scrollbars=yes,status=no');
}

function delete_return_product(invoice_number)
{
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "GET",
                url: "{{route('orders.return_product_list_delete')}}",
                data: {'invoice_number':invoice_number},
                success: function (response) {
                    console.log(response);
                    if(response.status == 200)
                    {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                            })
                    }
                }
            });
        }
    })
  
}




</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
