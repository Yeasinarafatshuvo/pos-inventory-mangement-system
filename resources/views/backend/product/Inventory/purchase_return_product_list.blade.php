@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/> 
@section('content')
<style>

</style>
<div id="divName">
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
            <h2 class="bg-primary  text-center" style="color:white;">PURCHASE Return LIST </h2>
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
                    <th scope="col">Purchase Invoice Number</th>
                    <th scope="col">view</th>
                  </tr>
                </thead>
                <tbody>
                 @foreach ($all_return_purchase_data as $key => $item)
                 <tr>
                    <th scope="row">{{$key +1}}</th>
                    <td>{{$item->purchase_invoices}}</td>
                    <td>
                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('purchase.return.product.details', $item->purchase_invoices)}}" title="View">
                            <i class="las la-eye"></i>
                        </a>
                        <a class="btn btn-soft-danger btn-icon btn-circle btn-sm delete-confirm" href="{{route('purchase.return.product.delete', $item->purchase_invoices)}}" title="View">
                            <i class="las la-trash"></i>
                        </a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">

$(document).ready(function () {
    $('#dtBasicExample').DataTable();
    $('.dataTables_length').addClass('bs-select');

});


//delete Confirmation code
$('.delete-confirm').click(function(event){
   event.preventDefault();
   var url = $(this).attr('href');
   console.log(url);

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
        window.location.href = url;
        Swal.fire(
        'Deleted!',
        'Purhcase Return deleted successfully!.',
        'success'
        )
    }
    })
   
});





</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
