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
            <h2 class="bg-primary  text-center" style="color:white;">Marketing Sms Sending Report By Date</h2>
            </div>
        </div>
    </div>
    
  <div class="card"  id="search_date">
        <form action="{{route('sms_sending_report.date_wise')}}" method="POST">
            @csrf
            <div class="row pb-2" style="background-color: #F2F2F2;margin-left:0; margin-right:0">
                <div class="col-md-2 pt-3 font-weight-bold" style="font-size: 20px"> Report By Date</div>
               <div class="col-md-1 pt-3 font-weight-bold" style="padding-right: 0px;">Start Date:</div>
               <div class="col-md-2 pt-2" style="padding-left:0"><input type="text" id="datepicker" name="start_date" value="{{$all_date[0]}}" required class="form-control"></div>
               <div class="col-md-1 pt-3 font-weight-bold" style="padding-right: 0px;">End Date:</div>
               <div class="col-md-2 pt-2" style="padding-left:0"><input type="text" id="datepickertwo" name="end_date" value="{{$all_date[count($all_date)-1]}}" required class="form-control"></div>
               <div class="col-md-2 pt-2"><button class="btn btn-primary btn-md">Search By Date</button></div>
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
                        @foreach ($all_sms_report as $key => $sms_data)
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
