@extends('backend.layouts.app')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@section('content')
<form action="{{route('employee.automate_attendance.store_edited_in_out_time_data')}}" method="POST">
  @csrf
  <table class="table table-bordered">
    <thead class="thead-light ">
        <tr class="text-center">
            <th colspan="9" style="border: 1px solid black !important">For The Month Of {{date("M-Y",strtotime($month_wise_attendance_data[0]->date_attendance))}} (Individual Statement)</th>
        </tr>
        <tr class="text-center">
            <th colspan="9" style="border: 1px solid black !important">Name:{{getEmployeeName($month_wise_attendance_data[0]->user_id)}}</th>
        </tr>
     
      <tr>
        <th class="text-center" scope="col">#</th>
        <th class="text-center" scope="col">Attendance Date</th>
        <th class="text-center" scope="col">In Time</th>
        <th class="text-center" scope="col">Out Time</th>
      </tr>
    </thead>
      
    <tbody>
      
          @foreach ($month_wise_attendance_data as $key => $item)
          <?php
              $timestamp = strtotime($item->date_attendance);
              $day = date('D', $timestamp);
          ?>
            <tr>
                <input type="hidden" name="id[]" value="{{$item->id}}">
                <td class="text-center" scope="row">{{$key +1}}</td>
                  @if ($day !== 'Fri')
                      <td class="text-center" scope="row">{{$item->date_attendance}}</td>
                      @else
                      <td class="text-center" scope="row">Friday</td>
                  @endif
                <td class="text-center" scope="row"><input type="text" class="form-control text-center" name="attendance_in_time[]" value="{{$item->attendance_in_time}}"></td>
                <td class="text-center" scope="row"><input type="text" class="form-control text-center" name="attendance_out_time[]" value="{{$item->attendance_out_time}}"></td>

            </tr>
          @endforeach
          
    </tbody>

  </table>
  <button type="submit" class="btn btn-lg btn-primary text-center" style="display: block; margin:0 auto">submit</button>
</form>
  


<script type="text/javascript">


</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
