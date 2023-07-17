@extends('backend.layouts.app')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@section('content')
<div id="divName">
  <table class="table table-bordered">
    <thead class="thead-light ">
        <tr class="text-center">
            <th colspan="9" style="border: 1px solid black !important">For The Month Of {{date("M-Y",strtotime($current_month_attendance_data[0]->date_attendance))}} (Individual Statement)</th>
        </tr>
        <tr class="text-center">
            <th colspan="9" style="border: 1px solid black !important">Name:{{getEmployeeName($current_month_attendance_data[0]->user_id)}}</th>
        </tr>
     
      <tr>
        <th style="border: 1px solid black !important" class="text-center" scope="col">#</th>
        <th style="border: 1px solid black !important" class="text-center" scope="col">Attendance Date</th>
        <th style="border: 1px solid black !important" class="text-center" scope="col">In Time</th>
        <th style="border: 1px solid black !important" class="text-center" scope="col">Out Time</th>
      </tr>
    </thead>
      
    <tbody>
      
          @foreach ($current_month_attendance_data as $key => $item)
          <?php
              $timestamp = strtotime($item->date_attendance);
              $day = date('D', $timestamp);
          ?>
            <tr>
                <input type="hidden" name="id[]" value="{{$item->id}}">
                <td style="border: 1px solid black !important" class="text-center" scope="row">{{$key +1}}</td>
                  @if ($day !== 'Fri')
                      <td style="border: 1px solid black !important" class="text-center" scope="row">{{$item->date_attendance}}</td>
                      @else
                      <td style="border: 1px solid black !important; color:red" class="text-center" scope="row">Friday</td>
                  @endif
                <td style="border: 1px solid black !important" class="text-center" scope="row">{{$item->attendance_in_time}}</td>
                <td style="border: 1px solid black !important" class="text-center" scope="row">{{$item->attendance_out_time}}</td>

            </tr>
          @endforeach
          
    </tbody>
  </table>
</div>
<div class="text-center">
  <button class="btn btn-sm btn-primary" onclick="printTable()">Print</button>
</div>

  


<script type="text/javascript">
function printTable() {
  var printContents = document.getElementById("divName").innerHTML;
  var originalContents = document.body.innerHTML;
  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;
}

</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
