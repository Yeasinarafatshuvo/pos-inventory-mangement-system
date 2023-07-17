@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
@section('content')
<style>

</style>
    <div class="card">
        <div class="card-header">
            <div class=" clearfix text-center" style="margin: 0 auto; font-size:18px; font-weight:bold">
              List Of Employee Attendance
            </div>
        </div>
        <div class="card-body"> 
        <div class="row">
        <div class="col-md-12 pr-3">
            <table id="dtBasicExample" class="table table-striped table-bordered table-sm p-2 text-center" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <tr>
                      <th class="text-center" scope="col">#</th>
                      <th class="text-center" scope="col">User Name</th>
                      <th class="text-center" scope="col">Month</th>
                      <th class="text-center" scope="col">Year</th>
                      <th class="text-center" scope="col">Day</th>
                      <th class="text-center" scope="col">Action</th>
                    </tr>
                  </tr>
                </thead>
                <tbody>
                  @php
                      function getMonthName($monthNumber) {
                          $months = [
                            1 => 'January',
                            2 => 'February',
                            3 => 'March',
                            4 => 'April',
                            5 => 'May',
                            6 => 'June',
                            7 => 'July',
                            8 => 'August',
                            9 => 'September',
                            10 => 'October',
                            11 => 'November',
                            12 => 'December'
                          ];
                          
                          return $months[$monthNumber] ?? '';
                        }
                  @endphp
                  @foreach ($employee_list_data as $key => $item)
                  
                    <tr>
                        <td class="text-center" scope="row">{{$key +1}}</td>
                        <td class="text-center">{{getEmployeeName($item->user_id)}}</td>
                        <td class="text-center">{{getMonthName($item->month)}}</td>
                        <td class="text-center">{{$item->year}}</td>
                        <td class="text-center">{{$item->attendance_count}}</td>
                        <td class="text-center">
                          @if ($item->salary_generate_value == 1)
                            <a href="javascript:void(0)" onclick="print_invoice('{{route('employee.automate_attendance.print', ['user_id' => $item->user_id, 'year' => $item->year,'month' =>$item->month])}}')"  class="btn btn-primary btn-sm">Print</a>
                          @endif
                          @if ($item->in_out_edit_value == 1)
                            <a href="{{route('employee.automate_attendance.generate_salary', ['user_id' => $item->user_id, 'year' => $item->year,'month' =>$item->month])}}" class="btn btn-primary btn-sm ">Generate Salary Sheet</a>
                          @endif
          
                          @if ($item->in_out_edit_value == 0)
                            <a href="{{route('employee.automate_attendance.edit', ['user_id' => $item->user_id, 'year' => $item->year,'month' =>$item->month])}}" class="btn btn-info btn-sm">Edit</a>
                          @else
                            <a href="{{route('employee.automate_attendance.edit', ['user_id' => $item->user_id, 'year' => $item->year,'month' =>$item->month])}}" class="btn btn-info btn-sm">Edited</a>
                          @endif
          
                          @if ($item->salary_generate_value == 1)
                            @if ($item->payslip_create == 1)
                              <a href="{{route('payslip.edit',['user_id' => $item->user_id, 'year' => $item->year,'month' =>$item->month])}}" class="btn btn-primary btn-sm ">Update Payslip</a>
                              <a href="javascript:void(0)" onclick="print_invoice('{{route('payslip.print',['user_id' => $item->user_id, 'year' => $item->year,'month' =>$item->month])}}')" class="btn btn-primary btn-sm ">Print Payslip</a>
                            @else
                                <a href="{{route('payslip.create',['user_id' => $item->user_id, 'year' => $item->year,'month' =>$item->month])}}" class="btn btn-primary btn-sm ">Manage Payslip</a>
                            @endif
                          @endif
                         
                          <a href="{{route('employee.automate_attendance.delete', ['user_id' => $item->user_id, 'year' => $item->year,'month' =>$item->month])}}" class="btn btn-danger btn-sm delete-confirm">Delete</a>
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        Swal.fire({
          title: 'Deleted!',
          text: 'Employee attendance deleted successfully!',
          icon: 'success',
          timer: 2000 // Set the timer to 2 seconds
        })
      }
    })

});



</script>
@endsection
