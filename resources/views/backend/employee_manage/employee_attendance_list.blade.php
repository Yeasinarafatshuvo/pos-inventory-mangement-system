@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
@section('content')
<style>

</style>
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
            <h2 class="bg-primary  text-center" style="color:white;">Employee Attendance List</h2>
                @if (session()->has('status'))
                <div class=" notification alert alert-success col-md-12">
                    {{ session('status') }}
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="pull-right clearfix">

            </div>
        </div>
        <div class="card-body"> 
        <div class="row">
        <div class="col-md-12 pr-3">
            <table id="dtBasicExample" class="table table-striped table-bordered table-sm p-2 text-center" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th scope="col">SL</th>
                    <th scope="col">Order Number</th>
                    <th scope="col">Employee Name</th>
                    <th scope="col">Created Date</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($employee_attendance_data as $key => $attendance_item)
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{$attendance_item->attendance_id}}</td>
                        <td>{{$attendance_item->name}}</td>
                        <td>{{$attendance_item->updated_at->format('Y-m-d')}}</td>
                        <td>
                          <a href="javascript:void(0)" onclick="print_invoice('{{route('employee.attendance.print', $attendance_item->attendance_id)}}')"  class="btn btn-primary btn-sm">Print</a>
                          <a href="{{route('employee.attendance.edit', $attendance_item->attendance_id)}}" class="btn btn-info btn-sm">Edit</a>
                          <a href="{{route('employee.attendance.delete', $attendance_item->attendance_id)}}" class="btn btn-danger btn-sm delete-confirm">Delete</a>
                        </td>
                      </tr> 
                    @endforeach
                </tbody>
              </table>
        </div>
    </div>
        </div>
    </div>
@endsection




@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">

function print_invoice(url) {
    var h = $(window).height();
    var w = $(window).width();
    window.open(url, '_blank', 'height=' + h + ',width=' + w + ',scrollbars=yes,status=no');
}

$(document).ready(function () {
    $('#dtBasicExample').DataTable();
    $('.dataTables_length').addClass('bs-select');


//delete Confirmation code
$('.delete-confirm').click(function(event){
   event.preventDefault();
   var url = $(this).attr('href');
   console.log(url);
    swal({
    title: "Are you sure?",
    text: "Once deleted, you will not be able to recover !",
    icon: "warning",
    buttons: true,
    dangerMode: true,
    })
    .then((willDelete) => {
    if (willDelete) {
        window.location.href = url;
        swal("Employee attendance deleted successfully!", {
        icon: "success",
        });
    } else {
        swal("Your data is safe!");
    }
    });

});


});




//remove notification after save data to db
removeNotification();
function removeNotification(){
  setTimeout(() => {
    $('.notification').remove();
  }, 3000);
}


</script>
@endsection
