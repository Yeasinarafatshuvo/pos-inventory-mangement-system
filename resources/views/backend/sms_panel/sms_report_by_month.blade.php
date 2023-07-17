@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@section('content')
<style>


</style>
    <div class="aiz-titlebar text-left mt-2 mb-1 pb-0">
        <div class="row align-items-center">
            <div class="col-md-12">
            <h2 class="bg-primary  text-center" style="color:white;">Marketing Sms Sending Report By Month</h2>
            </div>
        </div>
    </div>
    <div>
        @php
            $current_year = date("Y");
            $all_year = [];
            for($year = $current_year; $year >= $current_year -50; $year--)
            {
                $all_year[] = $year;
            
            }
        @endphp
    </div>
    
    <div class="card"  id="search_month">
        <form action="{{route('sms_sending_report.month_wise')}}" method="POST">
            @csrf
               <div class="d-flex justify-content-start pb-2" style="background-color: #F2F2F2;margin-left:0; margin-right:0">
                  <div class=" pt-3 pr-6 pl-3 font-weight-bold" style="font-size: 20px">Report By Month</div>
                   <div class=" pt-3 pl-0 font-weight-bold" style="padding-right: 0px;">Start Month:</div>
                   <div class=" pt-2" style="padding-left:0">
                    <div class="form-group pr-2">
                        <select class="form-control aiz-selectpicker" name="start_month" data-live-search="true">
                          <option value="1" {{$all_month[0] == 1 ? "selected": ""}}>January</option>
                          <option value="2" {{$all_month[0] == 2 ? "selected": ""}}>February</option>
                          <option value="3" {{$all_month[0] == 3 ? "selected": ""}}>March</option>
                          <option value="4" {{$all_month[0] == 4 ? "selected": ""}}>April</option>
                          <option value="5" {{$all_month[0] == 5 ? "selected": ""}}>May</option>
                          <option value="6" {{$all_month[0] == 6 ? "selected": ""}}>June</option>
                          <option value="7" {{$all_month[0] == 7 ? "selected": ""}}>July</option>
                          <option value="8" {{$all_month[0] == 8 ? "selected": ""}}>August</option>
                          <option value="9" {{$all_month[0] == 9 ? "selected": ""}}>September</option>
                          <option value="10" {{$all_month[0] == 10 ? "selected": ""}}>October</option>
                          <option value="11" {{$all_month[0] == 11 ? "selected": ""}}>November</option>
                          <option value="12" {{$all_month[0] == 12 ? "selected": ""}}>December</option>
                        </select>
                    </div>
                    </div>
                   <div class=" pt-3 font-weight-bold" style="padding-right: 0px;">End Month:</div>
                   <div class=" pt-2" style="padding-left:0">
                    <div class="form-group pr-2">
                        <select class="form-control aiz-selectpicker" name="end_month" data-live-search="true">
                          <option value="1" {{$all_month[count($all_month)-1] == 1? "selected": ""}}>January</option>
                          <option value="2" {{$all_month[count($all_month)-1] == 2? "selected": ""}}>February</option>
                          <option value="3" {{$all_month[count($all_month)-1] == 3? "selected": ""}}>March</option>
                          <option value="4" {{$all_month[count($all_month)-1] == 4? "selected": ""}}>April</option>
                          <option value="5" {{$all_month[count($all_month)-1] == 5? "selected": ""}}>May</option>
                          <option value="6" {{$all_month[count($all_month)-1] == 6? "selected": ""}}>June</option>
                          <option value="7" {{$all_month[count($all_month)-1] == 7? "selected": ""}}>July</option>
                          <option value="8" {{$all_month[count($all_month)-1] == 8? "selected": ""}}>August</option>
                          <option value="9" {{$all_month[count($all_month)-1] == 9? "selected": ""}}>September</option>
                          <option value="10" {{$all_month[count($all_month)-1] == 10? "selected": ""}}>October</option>
                          <option value="11" {{$all_month[count($all_month)-1] == 11? "selected": ""}}>November</option>
                          <option value="12" {{$all_month[count($all_month)-1] == 12? "selected": ""}}>December</option>
                        </select>
                    </div>
                   </div>
                   <div class=" pt-3 font-weight-bold" style="padding-right: 0px;">Select Year:</div>
                   <div class=" pt-2" style="padding-left:0">
                    <div class="form-group">
                        <select class="form-control aiz-selectpicker" name="year" data-live-search="true">
                            @foreach ($all_year as $year_item)
                                <option value="{{$year_item}}" {{$_year == $year_item ? "selected":""}}>{{$year_item}}</option>
                            @endforeach
                        </select>
                    </div>
                   </div>
                   <div class=" pt-2 pl-1 pr-1"><button class="btn btn-primary btn-md">Search By Month</button></div>
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
                        <th scope="col">Customer Mobile Number</th>
                        <th scope="col">SMS Status</th>
                        <th scope="col">SMS BODY</th>
                        <th scope="col">Created Date</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_sms_report_by_month as $key => $sms_data)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$sms_data->phone_number}}</td>
                                <td>{{$sms_data->send_status}}</td>
                                <td>{{$sms_data->sms_body}}</td>
                                <td>{{$sms_data->created_at->format('Y-m-d')}}</td>
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
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript">


$(document).ready(function () {
    $('#dtBasicExample').DataTable();
    $('.dataTables_length').addClass('bs-select');

    
});

$( function() {
    $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
    
});
$( function() {
    $( "#datepickertwo" ).datepicker({ dateFormat: 'yy-mm-dd' });
});


$('#date').click(function (e) { 
    e.preventDefault();
    $('#search_date').show();
    $('#all_serch_btn').hide();
    
});
function delete_date_search()
{
    $('#search_date').hide();
    $('#all_serch_btn').show();
}


$('input').click(function (e) { 
    e.preventDefault();
    $('#ui-datepicker-header').css("margin-top", "150px") 
});

</script>
@endsection
