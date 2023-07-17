@extends('backend.layouts.app')

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
        $all_year_range = [];
        for($year = $current_year; $year >= $current_year -50; $year--)
        {
            $all_year_range[] = $year;
        
        }
    @endphp
</div>
<div class="card"  id="search_year">
    <form action="{{route('summary.report.byyear')}}" method="POST">
        @csrf
           <div class="row pb-2" style="background-color: #F2F2F2;margin-left:0; margin-right:0">
               <div class="col-md-2 pt-3 font-weight-bold" style="font-size: 20px">Report By Year</div>
               <div class="col-md-1 pt-4 pl-0 font-weight-bold" style="padding-right: 0px;">Start Year:</div>
               <div class="col-md-2 pt-3" style="padding-left:0">
                <div class="form-group">
                    <select class="form-control aiz-selectpicker" name="start_year" data-live-search="true">
                      @foreach ($all_year_range as $year_value)
                        <option value="{{$year_value}}" {{$all_year[0] == $year_value?"selected":""}}>{{$year_value}}</option>
                      @endforeach
                    </select>
                </div>
                </div>
               <div class="col-md-1 pt-4 font-weight-bold" style="padding-right: 0px;">End Year:</div>
               <div class="col-md-2 pt-3" style="padding-left:0">
                <div class="form-group">
                    <select class="form-control aiz-selectpicker" name="end_year" data-live-search="true">
                        @foreach ($all_year_range as $year_value)
                            <option value="{{$year_value}}" {{$all_year[count($all_year)-1] == $year_value?"selected":""}}>{{$year_value}}</option>
                        @endforeach
                    </select>
                </div>
               </div>
               <div class="col-md-2 pt-3 pl-1 pr-1"><button class="btn btn-primary btn-md">Search By Year</button></div>
            </div>
    </form>
</div>


<div class="container-fluid p-0"  id="divName">
   
    <table class="table table-bordered">
        <thead>
            <tr>
                <th id="heading_part" class="bg-primary" colspan="14" style="text-align: center;font-size:15px;color:black;">
                    Summary Report From  {{$all_year[0]}}  to {{$all_year[count($all_year)-1]}}
                </th>
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


                $__customer_no_response_order_sub = 0;
                foreach ($_customer_no_response_order as $key => $_customer_no_response_order_item_sub) {
                    $__customer_no_response_order_sub += $_customer_no_response_order_item_sub->total;
                }

                $__customer_no_response_order_value_sub = 0;
                foreach ($_customer_no_response_order_value as $key => $_customer_no_response_order_value_item_sub) {
                    $__customer_no_response_order_value_sub += $_customer_no_response_order_value_item_sub->grand_total;
                }

                $__product_shipped_order_sub = 0;
                foreach ($_product_shipped_order as $key => $_product_shipped_order_item_sub) {
                    $__product_shipped_order_sub += $_product_shipped_order_item_sub->total;
                }

                $__product_shipped_order_value_sub = 0;
                foreach ($_product_shipped_order_value as $key => $_product_shipped_order_value_item_sub) {
                    $__product_shipped_order_value_sub += $_product_shipped_order_value_item_sub->grand_total;
                }

            @endphp
            {{-- end total sum of column wise --}}
            @foreach ($all_year as $year_item)
            <tr>
                <td class="time_period" style="text-align: center"><nobr>{{$year_item}}</nobr></td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_register_in_site as $key => $register_value) {
                           if($register_value->year == $year_item){
                               echo $register_value->total;
                               break;
                           }else{
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
                            if($received_order_item->year == $year_item){
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
                            if($_received_order_value_item->year == $year_item){
                                echo "৳".number_format($_received_order_value_item->grand_total,2);
                                break;
                            }else{
                                if(count($_received_order_value)-1 == $key)
                                {
                                    echo 0;
                                }
                           }
                        }
                    ?>
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_confirmed_order as $key => $_confirmed_order_item) {
                            if($_confirmed_order_item->year == $year_item){
                                echo $_confirmed_order_item->total;
                                break;
                            }else{
                                if(count($_confirmed_order)-1 == $key)
                                {
                                    echo 0;
                                }
                           }
                        }
                    ?>
                </td>
                <td style="text-align: center">
                    <?php 
                        foreach ($_confirmed_order_value as $key => $_confirmed_order_value_item) {
                            if($_confirmed_order_value_item->year == $year_item){
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
                            if($_cancelled_order_item->year == $year_item){
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
                            if($_cancelled_order_value_item->year == $year_item){
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
                            if($_delivered_order_item->year == $year_item){
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
                            if($_delivered_order_value_item->year == $year_item){
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
                            if($_paid_order_item->year == $year_item){
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
                            if($_paid_order_value_item->year == $year_item){
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
                            if($_unpaid_order_item->year == $year_item){
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
                            if($_unpaid_order_value_item->year == $year_item){
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
                        foreach ($_customer_no_response_order as $key => $_customer_no_response_order_item) {
                            if($_customer_no_response_order_item->year == $year_item){
                                echo $_customer_no_response_order_item->total;
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
                        foreach ($_customer_no_response_order_value as $key => $_customer_no_response_order_value_item) {
                            if($_customer_no_response_order_value_item->year == $year_item){
                                echo "৳".number_format($_customer_no_response_order_value_item->grand_total,2);
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
                        foreach ($_product_shipped_order as $key => $_product_shipped_order_item) {
                            if($_product_shipped_order_item->year == $year_item){
                                echo $_product_shipped_order_item->total;
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
                        foreach ($_product_shipped_order_value as $key => $_product_shipped_order_value_item) {
                            if($_product_shipped_order_value_item->year == $year_item){
                                echo "৳".number_format($_product_shipped_order_value_item->grand_total,2);
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
                    {{$__customer_no_response_order_sub}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{"৳".number_format($__customer_no_response_order_value_sub,2)}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{$__product_shipped_order_sub}}
                </td>
                <td style="text-align: center;font-weight:bold">
                    {{"৳".number_format($__product_shipped_order_value_sub,2)}}
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="text-center">
    <button class="btn btn-primary btn-sm" onclick="printpage('divName')">print</button>
</div>
@endsection

<script type="text/javascript">
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
 
</script>
