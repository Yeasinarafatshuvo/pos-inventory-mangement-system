@extends('backend.layouts.app')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@section('content')


  <table class="table table-bordered">
    <thead class="thead-light ">
      <tr class="text-center">
        <th colspan="9" style="border: 1px solid black !important">Current Employee Attendance</th>
      </tr>
     
      <tr>
        <th class="text-center" scope="col">#</th>
        <th class="text-center" scope="col">User Name</th>
        <th class="text-center" scope="col">Month</th>
        <th class="text-center" scope="col">Year</th>
        <th class="text-center" scope="col">Day</th>
        <th class="text-center" scope="col">Action</th>
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
        @php
            $current_month = date('n');
            $current_year = date('Y');
        @endphp
        @foreach ($employee_list_data as $key => $item)
        
          <tr>
              <td class="text-center" scope="row">{{$key +1}}</td>
              <td class="text-center">{{getEmployeeName($item->user_id)}}</td>
              <td class="text-center">{{getMonthName($current_month)}}</td>
              <td class="text-center">{{$current_year}}</td>
              <td class="text-center">{{$item->attendance_count}}</td>
              <td class="text-center">
                <a href="{{route('employee.automate_current_attendance.details', ['user_id' => $item->user_id, 'year' => $current_year,'month' =>$current_month])}}" class="btn btn-primary btn-sm ">Details</a>
              </td>
          </tr>
        @endforeach
      
     
    </tbody>
  </table>
  


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">




</script>




@endsection
