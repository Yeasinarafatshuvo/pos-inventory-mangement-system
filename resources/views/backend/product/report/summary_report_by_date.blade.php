@extends('backend.layouts.app')
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">

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
<div class="card"  id="search_date">
    <form action="{{route('summary.report.bydate')}}" method="POST">
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

<div class="container-fluid p-0"  id="divName">
   
    <table class="table table-bordered" id="tblData">
        <thead>
            <tr>
                <th id="heading_part" class="bg-primary" colspan="18" style="text-align: center;font-size:15px;color:black;">Summary Report From {{$all_date[0]}} to {{$all_date[count($all_date)-1]}}</th>
            </tr>
            <tr>
                <th style="text-align: center">Date</th>
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
            {{-- start total sum of column wise --}}
            @php
                $total_register_item = 0;
                foreach ($_register_in_site as  $register_value) {
                   $total_register_item += $register_value->total;
                }
                $total_received_item = 0;
                foreach ($_received_order as  $received_order_value) {
                    $total_received_item += $received_order_value->total;
                }

                $total_received_order_value = 0;
                foreach ($_received_order_value as $key => $_received_order_value_item) {
                    $total_received_order_value += $_received_order_value_item->grand_total;
                }

                $total_confirm_order_item = 0;
                foreach ($_confirmed_order as $key => $_confirmed_order_item) {
                    $total_confirm_order_item += $_confirmed_order_item->total;
                }

                $toal_confirm_order_value = 0;
                foreach ($_confirmed_order_value as $key => $_confirmed_order_value_item) {
                    $toal_confirm_order_value += $_confirmed_order_value_item->grand_total;
                }

                $total_cancel_order_item = 0;
                foreach ($_cancelled_order as $key => $_cancelled_order_item) {
                    $total_cancel_order_item += $_cancelled_order_item->total;
                }
                
                $total_cancel_order_value = 0;
                foreach ($_cancelled_order_value as $key => $_cancelled_order_value_item) {
                    $total_cancel_order_value +=  $_cancelled_order_value_item->grand_total; 
                }

                $total_deliver_order_item = 0;
                foreach ($_delivered_order as $key => $_delivered_order_item) {
                    $total_deliver_order_item += $_delivered_order_item->total;
                }

                $total_delivered_order_value = 0;
                foreach ($_delivered_order_value as $key => $_delivered_order_value_item) {
                    $total_delivered_order_value += $_delivered_order_value_item->grand_total;
                }

                $total_paid_order_item = 0;
                foreach ($_paid_order as $key => $_paid_order_item) {
                    $total_paid_order_item += $_paid_order_item->total;
                }

                $total_paid_order_value = 0;
                foreach ($_paid_order_value as $key => $_paid_order_value_item) {
                    $total_paid_order_value += $_paid_order_value_item->grand_total;
                }

                $toal_unpaid_order_item = 0;
                foreach ($_unpaid_order as $key => $_unpaid_order_item) {
                    $toal_unpaid_order_item += $_unpaid_order_item->total;
                }

                $total_unpaid_order_value = 0;
                foreach ($_unpaid_order_value as $key => $_unpaid_order_value_item) {
                    $total_unpaid_order_value += $_unpaid_order_value_item->grand_total;
                }
                $total_customer_no_response_order = 0;
                foreach ($_customer_no_response_order as $key => $total__customer_no_response_order_item) {
                    $total_customer_no_response_order += $total__customer_no_response_order_item->total;
                }
                $total_customer_no_response_order_value = 0;
                foreach ($_customer_no_response_order_value as $key => $total_customer_no_response_order_value_item) {
                    $total_customer_no_response_order_value += $total_customer_no_response_order_value_item->grand_total;
                }
                
                $total__product_shipped_order = 0;
                foreach ($_product_shipped_order as $key => $total__product_shipped_order_item) {
                    $total__product_shipped_order += $total__product_shipped_order_item->total;
                }
                $total__product_shipped_order_value = 0;
                foreach ($_product_shipped_order_value as $key => $total__product_shipped_order_value_item) {
                    $total__product_shipped_order_value += $total__product_shipped_order_value_item->grand_total;
                }

            @endphp
             {{-- end total sum of column wise --}}
            @foreach ($all_date as $date_item)
            <tr>
                <td class="time_period" style="text-align: center"><nobr>{{$date_item}}</nobr></td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_register_in_site as $key => $register_value) {
                           if($register_value->date == $date_item){
                               echo $register_value->total;
                               break;
                           }
                           else{
                            if(count($_register_in_site)-1 == $key)
                            {
                                echo 0;
                            }
                           }
                           
                           
                           
                        }
                        
                    ?>
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_received_order as $key => $received_order_item) {
                            if($received_order_item->date == $date_item){
                                echo $received_order_item->total;
                                break;
                            }else{
                                if(count($_received_order)-1 == $key)
                                {
                                    echo 0;
                                }
                            }
                        }
                    ?>
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_received_order_value as $key => $_received_order_value_item) {
                            if($_received_order_value_item->date == $date_item){
                                echo "৳".number_format($_received_order_value_item->grand_total,2);
                                break;
                            }else{
                                if(count($_received_order_value)-1 == $key){
                                    echo 0;
                                }
                            }
                        }
                    ?>
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_confirmed_order as $key => $_confirmed_order_item) {
                            if($_confirmed_order_item->date == $date_item){
                                echo $_confirmed_order_item->total;
                                break;
                            }else{
                                if(count($_confirmed_order)-1 == $key){
                                    echo 0;
                                }
                            }
                        }
                    ?>
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_confirmed_order_value as $key => $_confirmed_order_value_item) {
                            if($_confirmed_order_value_item->date == $date_item){
                                echo "৳".number_format($_confirmed_order_value_item->grand_total,2);
                                break;
                            }else{
                                if(count($_confirmed_order_value)-1 == $key)
                                {
                                    echo 0;
                                }
                            }
                        }
                    ?>
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_cancelled_order as $key => $_cancelled_order_item) {
                            if($_cancelled_order_item->date == $date_item){
                                echo $_cancelled_order_item->total;
                                break;
                            }else{
                                if(count($_cancelled_order)-1 == $key)
                                {
                                    echo 0;
                                }
                            }
                        }
                    ?>
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_cancelled_order_value as $key => $_cancelled_order_value_item) {
                            if($_cancelled_order_value_item->date == $date_item){
                                echo "৳".number_format($_cancelled_order_value_item->grand_total,2);
                                break;
                            }else{
                                if(count($_cancelled_order_value)-1 == $key)
                                {
                                    echo 0;
                                }
                            }
                        }
                    ?>
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_delivered_order as $key => $_delivered_order_item) {
                            if($_delivered_order_item->date == $date_item){
                                echo $_delivered_order_item->total;
                                break;
                            }else{
                                if(count($_delivered_order)-1 == $key)
                                {
                                    echo 0;
                                }
                            }
                        }
                    ?>
                </td>   
                <td style="text-align: center">
                    <?php 
                        foreach ($_delivered_order_value as $key => $_delivered_order_value_item) {
                            if($_delivered_order_value_item->date == $date_item){
                                echo "৳".number_format($_delivered_order_value_item->grand_total,2);
                                break;
                            }else{
                                if(count($_delivered_order_value)-1 == $key)
                                {
                                    echo 0;
                                }
                            }
                        }
                    ?>
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_paid_order as $key => $_paid_order_item) {
                            if($_paid_order_item->date == $date_item){
                                echo $_paid_order_item->total;
                                break;
                            }else{
                                if(count($_paid_order)-1 == $key)
                                {
                                    echo 0;
                                }
                            }
                        }
                    ?>
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_paid_order_value as $key => $_paid_order_value_item) {
                            if($_paid_order_value_item->date == $date_item){
                                echo "৳".number_format($_paid_order_value_item->grand_total,2);
                                break;
                            }else{
                                if(count($_paid_order_value)-1 == $key)
                                {
                                    echo 0;
                                }
                            }
                        }
                    ?>
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_unpaid_order as $key => $_unpaid_order_item) {
                            if($_unpaid_order_item->date == $date_item){
                                echo $_unpaid_order_item->total;
                                break;
                            }else{
                                if(count($_unpaid_order)-1 == $key)
                                {
                                    echo 0;
                                }
                            }
                        }
                    ?>
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_unpaid_order_value as $key => $_unpaid_order_value_item) {
                            if($_unpaid_order_value_item->date == $date_item){
                                echo "৳".number_format($_unpaid_order_value_item->grand_total,2);
                                break;
                            }else{
                                if(count($_unpaid_order_value)-1 == $key)
                                {
                                    echo 0;
                                }
                            }
                        }
                    ?>
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_customer_no_response_order as $key => $customer_no_respose_item) {
                            if($customer_no_respose_item->date == $date_item)
                            {
                                echo $customer_no_respose_item->total;
                                break;
                            }else{
                                if(count($_customer_no_response_order)-1 == $key)
                                {
                                    echo 0;
                                }
                            }
                        }
                    ?>
                   
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_customer_no_response_order_value as $key => $customer_no_respose_value_item) {
                            if($customer_no_respose_value_item->date == $date_item)
                            {
                                echo "৳".number_format($customer_no_respose_value_item->grand_total);
                                break;
                            }else{
                                if(count($_customer_no_response_order_value)-1 == $key)
                                {
                                    echo 0;
                                }
                            }
                        }
                    ?>
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_product_shipped_order as $key => $product_shipped_order_item) {
                            if($product_shipped_order_item->date == $date_item)
                            {
                                echo $product_shipped_order_item->total;
                                break;
                            }else{
                                if(count($_product_shipped_order)-1 == $key)
                                {
                                    echo 0;
                                }
                            }
                        }
                    ?>
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_product_shipped_order_value as $key => $product_shipped_order_value_item) {
                            if($product_shipped_order_value_item->date == $date_item)
                            {
                                echo "৳".number_format($product_shipped_order_value_item->grand_total);
                                break;
                            }else{
                                if(count($_product_shipped_order_value)-1 == $key)
                                {
                                    echo 0;
                                }
                            }
                        }
                    ?>
                </td>
            </tr>
            @endforeach
            <tr>
                <td style="text-align: center;font-weight:bold" class="pl-0 pr-0">
                    SUB TOTAL
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{$total_register_item}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{$total_received_item}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{"৳".number_format( $total_received_order_value,2)}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{$total_confirm_order_item}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{"৳".number_format($toal_confirm_order_value,2)}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{$total_cancel_order_item}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{"৳".number_format($total_cancel_order_value,2)}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{$total_deliver_order_item}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{"৳".number_format($total_delivered_order_value,2)}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{$total_paid_order_item}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{"৳".number_format($total_paid_order_value,2)}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{$toal_unpaid_order_item}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{"৳".number_format($total_unpaid_order_value,2)}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{$total_customer_no_response_order}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{"৳".number_format($total_customer_no_response_order_value,2)}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{$total__product_shipped_order}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{"৳".number_format($total__product_shipped_order_value,2)}}
                </td>
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

 
</script>
@endsection