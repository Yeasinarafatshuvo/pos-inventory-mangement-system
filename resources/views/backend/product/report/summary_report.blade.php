@extends('backend.layouts.app')
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@section('content')
<style>
    
    table th{
        background-color: #f2f2f2;
        font-weight: bold;
        font-size: 14px;
    }
    table td{
        text-align: center;
    }
    .time_period{
        font-weight: bold;
        font-size: 14px;
    }
    #divName {
     overflow-x: scroll;
    }
    
</style>
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

<div class="row" style="text-align: center" id="all_serch_btn">
    <div class="col-md-4"><button class="btn btn-primary" id="date">Search By Date</button></div>
    <div class="col-md-4"><button class="btn btn-primary" id="month">Search By Month</button></div>
    <div class="col-md-4"><button class="btn btn-primary" id="year">Search By Year</button></div>
</div>

<div class="card" style="display: none" id="search_date">
    <form action="{{route('summary.report.bydate')}}" method="POST">
        @csrf
           <div class="row pb-2" style="background-color: #F2F2F2;margin-left:0; margin-right:0">
                <div class="col-md-2 pt-3 font-weight-bold" style="font-size: 20px"> Report By Date</div>
               <div class="col-md-1 pt-3 font-weight-bold" style="padding-right: 0px;">Start Date:</div>
               <div class="col-md-2 pt-2" style="padding-left:0"><input type="text" id="datepicker" name="start_date" required class="form-control"></div>
               <div class="col-md-1 pt-3 font-weight-bold" style="padding-right: 0px;">End Date:</div>
               <div class="col-md-2 pt-2" style="padding-left:0"><input type="text" id="datepickertwo" name="end_date" required class="form-control"></div>
               <div class="col-md-2 pt-2"><button class="btn btn-primary btn-md">Search By Date</button></div>
               <div class="col-md-2 pt-2"><button type="button" onclick="delete_date_search()" class="btn btn-success btn-md"><i class="material-icons">&#xe8ba;</i></button></div>
           </div>
    </form>
</div>
<div class="card" style="display: none" id="search_month">
    <form action="{{route('summary.report.bymonth')}}" method="POST">
        @csrf
           <div class="d-flex justify-content-start pb-2" style="background-color: #F2F2F2;margin-left:0; margin-right:0">
              <div class=" pt-3 pr-6 pl-3 font-weight-bold" style="font-size: 20px">Report By Month</div>
               <div class=" pt-3 pl-0 font-weight-bold" style="padding-right: 0px;">Start Month:</div>
               <div class=" pt-2" style="padding-left:0">
                <div class="form-group pr-2">
                    <select class="form-control aiz-selectpicker" name="start_month" data-live-search="true">
                      <option value="1">January</option>
                      <option value="2">February</option>
                      <option value="3">March</option>
                      <option value="4">April</option>
                      <option value="5">May</option>
                      <option value="6">June</option>
                      <option value="7">July</option>
                      <option value="8">August</option>
                      <option value="9">September</option>
                      <option value="10">October</option>
                      <option value="11">November</option>
                      <option value="12">December</option>
                    </select>
                </div>
                </div>
               <div class=" pt-3 font-weight-bold" style="padding-right: 0px;">End Month:</div>
               <div class=" pt-2" style="padding-left:0">
                <div class="form-group pr-2">
                    <select class="form-control aiz-selectpicker" name="end_month" data-live-search="true">
                      <option value="1">January</option>
                      <option value="2">February</option>
                      <option value="3">March</option>
                      <option value="4">April</option>
                      <option value="5">May</option>
                      <option value="6">June</option>
                      <option value="7">July</option>
                      <option value="8">August</option>
                      <option value="9">September</option>
                      <option value="10">October</option>
                      <option value="11">November</option>
                      <option value="12">December</option>
                    </select>
                </div>
               </div>
               <div class=" pt-3 font-weight-bold" style="padding-right: 0px;">Select Year:</div>
               <div class=" pt-2" style="padding-left:0">
                <div class="form-group">
                    <select class="form-control aiz-selectpicker" name="year" data-live-search="true">
                        @foreach ($all_year as $year)
                            <option value="{{$year}}">{{$year}}</option>
                        @endforeach
                    </select>
                </div>
               </div>
               <div class=" pt-2 pl-1 pr-1"><button class="btn btn-primary btn-md">Search By Month</button></div>
               <div class=" pt-2 pl-1 pr-1"><button type="button" onclick="delete_month_search()" class="btn btn-success btn-md"><i class="material-icons">&#xe8ba;</i></button></div>
            </div>
    </form>
</div>
<div class="card" style="display: none" id="search_year">
    <form action="{{route('summary.report.byyear')}}" method="POST">
        @csrf
           <div class="row pb-2" style="background-color: #F2F2F2;margin-left:0; margin-right:0">
               <div class="col-md-2 pt-3 font-weight-bold" style="font-size: 20px">Report By Year</div>
               <div class="col-md-1 pt-4 pl-0 font-weight-bold" style="padding-right: 0px;">Start Year:</div>
               <div class="col-md-2 pt-3" style="padding-left:0">
                <div class="form-group">
                    <select class="form-control aiz-selectpicker" name="start_year" data-live-search="true">
                      @foreach ($all_year as $year)
                        <option value="{{$year}}">{{$year}}</option>
                      @endforeach
                    </select>
                </div>
                </div>
               <div class="col-md-1 pt-4 font-weight-bold" style="padding-right: 0px;">End Year:</div>
               <div class="col-md-2 pt-3" style="padding-left:0">
                <div class="form-group">
                    <select class="form-control aiz-selectpicker" name="end_year" data-live-search="true">
                        @foreach ($all_year as $year)
                            <option value="{{$year}}">{{$year}}</option>
                        @endforeach
                    </select>
                </div>
               </div>
               <div class="col-md-2 pt-3 pl-1 pr-1"><button class="btn btn-primary btn-md">Search By Year</button></div>
               <div class=" col-md-2 pt-3"><button type="button" onclick="delete_year_search()" class="btn btn-success btn-md"><i class="material-icons">&#xe8ba;</i></button></div>
            </div>
    </form>
</div>

<div class="container-fluid p-0"  id="divName">
   
    <table class="table table-bordered">
        <thead>
            <tr>
                <th id="heading_part" class="bg-primary" colspan="18" style="text-align: center;font-size:15px;color:black;">Last 30 Days Report</th>
            </tr>
            <tr>
                <th style="text-align: center">Time Period</th>
                <th style="text-align: center">Register in our Site</th>
                <th style="text-align: center">Received Order</th>
                <th style="text-align: center">Received Order Value</th>
                <th style="text-align: center">Confirmed Order</th>
                <th style="text-align: center">Confirmed Order Value</th>
                <th style="text-align: center">Cancel Order</th>
                <th style="text-align: center">Cancel Order Value</th>
                <th style="text-align: center">Delivered Order</th>
                <th style="text-align: center">Delivered Order Value</th> 
                <th style="text-align: center">Paid Order</th>
                <th style="text-align: center">Paid Order Value</th> 
                <th style="text-align: center">Unpaid Order</th>
                <th style="text-align: center">Unpaid Order Value</th>
                <th style="text-align: center">NO Response Order</th>
                <th style="text-align: center">NO Response Order Value</th>
                <th style="text-align: center">Shipped order</th>
                <th style="text-align: center">Shipped order Value</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="time_period" style="text-align: center">Today</td> 
                <td style="text-align: center">{{$_today_register_in_site}}</td>
                <td style="text-align: center">{{$_today_received_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_today_received_order_value,2)}}</td>
                <td style="text-align: center">{{$_today_confirmed_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_today_confirmed_order_value,2)}}</td>
                <td style="text-align: center">{{$_today_cancelled_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_today_cancelled_order_value,2)}}</td>
                <td style="text-align: center">{{$_today_delivered_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_today_delivered_order_value,2)}}</td>
                <td style="text-align: center">{{$_today_paid_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_today_paid_order_value,2)}}</td>
                <td style="text-align: center">{{$_today_unpaid_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_today_unpaid_order_value,2)}}</td>
                <td style="text-align: center">{{$_today_no_response_customer_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_today_no_response_customer_order_value,2)}}</td>
                <td style="text-align: center">{{$_today_product_shipped}}</td>
                <td style="text-align: center">{{"৳".number_format($_today_product_shipped_value,2)}}</td>
            </tr>
            <tr>
                <td class="time_period" style="text-align: center">Yesterday</td>
                <td style="text-align: center">{{$_yesterday_register_in_site}}</td>
                <td style="text-align: center">{{$_yesterday_received_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_yesterday_received_order_value,2)}}</td>
                <td style="text-align: center">{{$_yesterday_confirmed_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_yesterday_confirmed_order_value,2)}}</td>
                <td style="text-align: center">{{$_yesterday_cancelled_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_yesterday_cancelled_order_value,2)}}</td>
                <td style="text-align: center">{{$_yesterday_delivered_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_yesterday_delivered_order_value,2)}}</td>
                <td style="text-align: center">{{$_yesterday_paid_order}}</td>
                <td style="text-align: center">{{$_yesterday_paid_order_value}}</td>
                <td style="text-align: center">{{$_yesterday_unpaid_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_yesterday_unpaid_order_value,2)}}</td>
                <td style="text-align: center">{{$_yesterday_no_response_customer_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_yesterday_no_response_customer_order_value,2)}}</td>
                <td style="text-align: center">{{$_yesterday_poduct_shipped}}</td>
                <td style="text-align: center">{{"৳".number_format($_yesterday_poduct_shipped_value,2)}}</td>
            </tr>
            <tr>
                <td class="time_period" style="text-align: center">Last 7 days</td>
                <td style="text-align: center">{{$_last_seven_days_register_in_site}}</td>
                <td style="text-align: center">{{$_last_seven_days_received_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_last_seven_days_received_order_value,2)}}</td>
                <td style="text-align: center">{{$_last_seven_days_confirmed_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_last_seven_days_confirmed_order_value,2)}}</td>
                <td style="text-align: center">{{$_last_seven_days_cancelled_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_last_seven_days_cancelled_order_value,2)}}</td>
                <td style="text-align: center">{{$_last_seven_days_delivered_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_last_seven_days_delivered_order_value,2)}}</td>
                <td style="text-align: center">{{$_last_seven_days_paid_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_last_seven_days_paid_order_value,2)}}</td>
                <td style="text-align: center">{{$_last_seven_days_unpaid_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_last_seven_days_unpaid_order_value,2)}}</td>
                <td style="text-align: center">{{$_last_seven_days_customer_no_response_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_last_seven_days_customer_no_response_order_value,2)}}</td>
                <td style="text-align: center">{{$_last_seven_days_product_shipeed_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_last_seven_days_product_shipeed_order_value,2)}}</td>
            </tr>
            <tr>
                <td class="time_period" style="text-align: center">Last 15 Days</td>
                <td style="text-align: center">{{$_fifteen_days_register_in_site}}</td>
                <td style="text-align: center">{{$_fifteen_days_received_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_fifteen_days_received_order_value,2)}}</td>
                <td style="text-align: center">{{$_fifteen_days_confirmed_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_fifteen_days_confirmed_order_value,2)}}</td>
                <td style="text-align: center">{{$_fifteen_days_cancelled_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_fifteen_days_cancelled_order_value,2)}}</td>
                <td style="text-align: center">{{$_fifteen_days_delivered}}</td>
                <td style="text-align: center">{{"৳".number_format($_fifteen_days_delivered_value,2)}}</td>
                <td style="text-align: center">{{$_fifteen_days_paid_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_fifteen_days_paid_order_value,2)}}</td>
                <td style="text-align: center">{{$_fifteen_days_unpaid_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_fifteen_days_unpaid_order_value,2)}}</td>
                <td style="text-align: center">{{$_fifteen_days_customer_no_response_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_fifteen_days_customer_no_response_order_value,2)}}</td>
                <td style="text-align: center">{{$_fifteen_days_product_shipeed_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_fifteen_days_product_shipeed_order_value,2)}}</td>
            </tr>
            <tr>
                <td class="time_period" style="text-align: center">Last 30 days</td>
                <td style="text-align: center">{{$_thirty_days_register_in_site}}</td>
                <td style="text-align: center">{{$_thirty_days_received_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_thirty_days_received_order_value,2)}}</td>
                <td style="text-align: center">{{$_thirty_days_confirmed_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_thirty_days_confirmed_order_value,2)}}</td>
                <td style="text-align: center">{{$_thirty_days_cancelled_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_thirty_days_cancelled_order_value,2)}}</td>
                <td style="text-align: center">{{$_thirty_days_delivered_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_thirty_days_delivered_order_value,2)}}</td>
                <td style="text-align: center">{{$_thirty_days_paid_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_thirty_days_paid_order_value,2)}}</td>
                <td style="text-align: center">{{$_thirty_days_unpaid_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_thirty_days_unpaid_order_value,2)}}</td>
                <td style="text-align: center">{{$_thirty_days_customer_no_response_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_thirty_days_customer_no_response_order_value,2)}}</td>
                <td style="text-align: center">{{$_thirty_days_product_shipeed_order}}</td>
                <td style="text-align: center">{{"৳".number_format($_thirty_days_product_shipeed_order_value,2)}}</td>
            </tr>
        </tbody>
    </table>
</div>
<div class="text-center">
    <button class="btn btn-primary btn-sm" onclick="printpage('divName')">print</button>
    <button class="btn btn-primary btn-sm" onclick="exportReportToExcel()">Excel</button>
</div>
@endsection

@section('script')
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

<script type="text/javascript">

function exportReportToExcel() {
  let table = document.getElementsByTagName("table"); // you can use document.getElementById('tableId') as well by providing id to the table tag
  TableToExcel.convert(table[0], { // html code may contain multiple tables so here we are refering to 1st table tag
    name: `summary_report.xlsx`, // fileName you could use any name
    sheet: {
      name: 'Sheet 1' // sheetName
    }
  });
}

function printpage(divName){

    var css = '@page { size: landscape; }',
    head = document.head || document.getElementsByTagName('head')[0],
    style = document.createElement('style');

    style.type = 'text/css';
    style.media = 'print';

    if (style.styleSheet){
        style.styleSheet.cssText = css;
    } else {
        style.appendChild(document.createTextNode(css));
    }
    head.appendChild(style);
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;

}

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
$('#month').click(function (e) { 
    e.preventDefault();
    $('#search_month').show();
    $('#all_serch_btn').hide();
    
});
function delete_month_search()
{
    $('#search_month').hide();
    $('#all_serch_btn').show();
}
$('#year').click(function (e) { 
    e.preventDefault();
    $('#search_year').show();
    $('#all_serch_btn').hide();
    
});
function delete_year_search()
{
    $('#search_year').hide();
    $('#all_serch_btn').show();
}
$('input').click(function (e) { 
    e.preventDefault();
    $('#ui-datepicker-header').css("margin-top", "150px") 
});




</script>
@endsection
