@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/> 
@section('content')
<style>

</style>
<div id="divName">
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
            <h2 class="bg-primary  text-center" style="color:white;">PURCHASE DUE LIST </h2>
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
                 @foreach ($purchase_due_invoice_numbers as $key => $item)
                 @if (!empty($item->purchase_invoice))
                 <tr>
                    <th scope="row">{{$key +1}}</th>
                    <td>{{$item->purchase_invoice}}</td>
                    <td>
                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('orders.purchase_order.due_details',$item->purchase_invoice)}}" title="View">
                            <i class="las la-eye"></i>
                        </a>
                    </td>
                  </tr>
                  @endif
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



</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
