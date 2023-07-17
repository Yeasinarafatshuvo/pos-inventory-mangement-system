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
    
    
    .tableFixHead{ overflow: auto; height: 1000px; }
    .tableFixHead thead tr th { position: sticky; top: 0; z-index: 1; }
    
    /* Just common table stuff. Really. */
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 8px 16px; }
    th     { background:#eee; }
    
/* .inner td:nth-child(1){
    position:sticky;
    left:0;
    background:white;
    z-index: 2;
}
.inner td:nth-child(2){
    position:sticky;
    left: 38px;
    background:white;
    z-index: 2;
}
.inner td:nth-child(3){
    position:sticky;
    left: 112px;
    background:white;
    z-index: 2;
} */
</style>

<div class="container-fluid p-0 tableFixHead outer"  id="divName">
<div class="inner">

    <table class="table table-bordered">
        <thead>
            <tr style="line-height: 4px;border:1px solid black !importnant;">
                <th id="heading_part"  colspan="22" style="text-align: center;font-size:20px; background-color:#6c50e1;">Stock Report</th>
            </tr>
            <tr style="line-height:14px;font-size:12px;border:1px solid black;">
                <th style="text-align: center">SI</th>
                <th style="text-align: center">Name</th>
                <th style="text-align: center">Quantity</th>
                <th style="text-align: center">Purchase Price(Each)</th>
                <th style="text-align: center">Total Purchase Price Amount</th>
                <th style="text-align: center">Sale Price(Each)(Regular)</th>
                <th style="text-align: center">Total Sale Price Amount(Regular)</th>
                <th style="text-align: center">Total Profit Amount(Regular)</th>
                <th style="text-align: center">Total Profit Percent(Regular)</th>
                                
                <th style="text-align: center">Sale Price(Each)(Corporate)</th>
                <th style="text-align: center">Total Sale Price Amount(Corporate)</th>
                <th style="text-align: center">Total Profit Amount(Corporate)</th>
                <th style="text-align: center">Total Profit Percent(Corporate)</th>
                
                <th style="text-align: center">Sale Price(Each)(Dealer)</th>
                <th style="text-align: center">Total Sale Price Amount(Dealer)</th>
                <th style="text-align: center">Total Profit Amount(Dealer)</th>
                <th style="text-align: center">Total Profit Percent(Dealer)</th>

            </tr>
        </thead>
        <tbody>
            @php
                $total_purchase_sum = 0;
                
                $total_sale_sum = 0;
                $total_profit_amount_sum = 0;
                $total_profit_percent_sum = 0;
                
                $total_dealer_sale_sum = 0;
                $total_dealer_profit_amount_sum = 0;
                $total_dealer_profit_percent_sum = 0;
                
                $total_corporate_sale_sum = 0;
                $total_corporate_profit_amount_sum = 0;
                $total_corporate_profit_percent_sum = 0;
                
                $last_loop_iteration = 0;
                $calculation = 0;
                foreach ($stock_report as $prices) {
                    if(!empty($prices->total_purchase_amount) && $prices->total_purchase_amount !== NULL){
                     $total_purchase_sum += $prices->total_purchase_amount;
                    }
                    
                    if(!empty($prices->total_sale_amount) && $prices->total_sale_amount !== NULL){
                        $total_sale_sum += $prices->total_sale_amount;
                    }
                    
                    if(!empty($prices->total_sale_dealer_amount) && $prices->total_sale_dealer_amount !== NULL){
                        $total_dealer_sale_sum += $prices->total_sale_dealer_amount;
                    }
                    
                    if(!empty($prices->total_sale_corporate_amount) && $prices->total_sale_corporate_amount !== NULL){
                        $total_corporate_sale_sum += $prices->total_sale_corporate_amount;
                    }
                }
               
            @endphp
            @foreach($stock_report as $stock)
                       <?php 
                            $profit = $stock->total_sale_amount-$stock->total_purchase_amount;
                            $total_profit_amount_sum +=$profit;
                            
                            if(!empty($stock->total_purchase_amount) && $stock->total_purchase_amount !== NULL){
                                 $in = $loop->iteration;
                                 $cost = (float)$stock->total_purchase_amount;
                                 if($cost !== 0 && !empty($cost)){
                                     $calculation = ($profit/$cost)*100;
                                 }
                                 
                            }else{
                                $cost = 0;
                                $calculation = 0;
                            }
                            
                            $total_profit_percent_sum += $calculation;
    
                            if($calculation !== 0 && !empty($calculation)){
                                $last_loop_iteration += 1;
                            }
                            
                            
                            // dealer calculation
                            $profit_dealer = $stock->total_sale_dealer_amount-$stock->total_purchase_amount;
                            $total_dealer_profit_amount_sum +=$profit_dealer;
                            
                            if(!empty($stock->total_purchase_amount) && $stock->total_purchase_amount !== NULL){
                                 $cost = (float)$stock->total_purchase_amount;
                                 if($cost !== 0 && !empty($cost)){
                                     $calculation_dealer = ($profit_dealer/$cost)*100;
                                 }
                            }else{
                                $cost = 0;
                                $calculation_dealer = 0;
                            }
                            $total_dealer_profit_percent_sum += $calculation_dealer;
                            
                            
                            // corporate calculation
                            $profit_corporate = $stock->total_sale_corporate_amount-$stock->total_purchase_amount;
                            $total_corporate_profit_amount_sum +=$profit_corporate;
                            
                            if(!empty($stock->total_purchase_amount) && $stock->total_purchase_amount !== NULL){
                                 $cost = (float)$stock->total_purchase_amount;
                                 if($cost !== 0 && !empty($cost)){
                                     $calculation_corporate = ($profit_corporate/$cost)*100;
                                 }
                            }else{
                                $cost = 0;
                                $calculation_corporate = 0;
                            }
                            $total_corporate_profit_percent_sum += $calculation_corporate;
                       ?>
            <tr style="line-height: 14px;font-size:12px;<?php if($calculation<0 || $calculation_dealer<0 || $calculation_corporate <0){echo "color:red;";} ?>">
                <td style="text-align: center" >{{$loop->iteration}}</td> 
                <td style="text-align: left" class="fix">{{$stock->name}}</td>
                <td style="text-align: center"  >{{$stock->stock_quantity}}</td>
                <td style="text-align: center">{{"৳".number_format($stock->purchase_price,2)}}</td>
                <td style="text-align: center">{{"৳".number_format($stock->total_purchase_amount,2)}}</td>
                
                <td style="text-align: center">{{"৳".number_format($stock->highest_price,2)}}</td>
                <td style="text-align: center">{{"৳".number_format($stock->total_sale_amount,2)}}</td>
                <td style="text-align: center">{{"৳".number_format($stock->total_sale_amount-$stock->total_purchase_amount,2)}}</td>
                <!-- "Profit/Cost Price × 100";echo "%" -->
                <td style="text-align: center">
                    <?php 
                      echo number_format($calculation,2);echo "%"; 
                    ?>
                </td>
                                
                <td style="text-align: center">{{"৳".number_format($stock->corporate_price,2)}}</td>
                <td style="text-align: center">{{"৳".number_format($stock->total_sale_corporate_amount,2)}}</td>
                <td style="text-align: center">{{"৳".number_format($stock->total_sale_corporate_amount-$stock->total_purchase_amount,2)}}</td>
                <!-- "Profit/Cost Price × 100";echo "%" -->
                <td style="text-align: center">
                    <?php 
                      echo number_format($calculation_corporate,2);echo "%"; 
                    ?>
                </td>
                
                <td style="text-align: center">{{"৳".number_format($stock->dealer_price,2)}}</td>
                <td style="text-align: center">{{"৳".number_format($stock->total_sale_dealer_amount,2)}}</td>
                <td style="text-align: center">{{"৳".number_format($stock->total_sale_dealer_amount-$stock->total_purchase_amount,2)}}</td>
                <!-- "Profit/Cost Price × 100";echo "%" -->
                <td style="text-align: center">
                    <?php 
                      echo number_format($calculation_dealer,2);echo "%"; 
                    ?>
                </td>

            </tr>
            @endforeach
            <tr style="line-height: 2px;font-size:12px;font-weight:bold;">
                <td style="text-align: center"></td> 
                <td colspan="3" style="text-align: right;">Total Amount=</td> 
                <td style="text-align: center">{{"৳".number_format($total_purchase_sum,2)}}</td>
                
                <td style="text-align: center"></td> 
                <td style="text-align: center">{{"৳".number_format($total_sale_sum,2)}}</td>
                <td style="text-align: center">{{"৳".number_format($total_profit_amount_sum,2)}}{{"("}}{{number_format(($total_profit_amount_sum/$total_purchase_sum)*100,2)}}{{"%)"}}</td>
                <td style="text-align: center"><nobr>{{number_format($total_profit_percent_sum/$last_loop_iteration,2)."%"."(Avg.)"}}</nobr></td>
                 
                <td style="text-align: center"></td> 
                <td style="text-align: center">{{"৳".number_format($total_corporate_sale_sum,2)}}</td>
                <td style="text-align: center">{{"৳".number_format($total_corporate_profit_amount_sum,2)}}{{"("}}{{number_format(($total_corporate_profit_amount_sum/$total_purchase_sum)*100,2)}}{{"%)"}}</td>
                <td style="text-align: center"><nobr>{{number_format($total_corporate_profit_percent_sum/$last_loop_iteration,2)."%"."(Avg.)"}}</nobr></td>
                
                
                <td style="text-align: center"></td> 
                <td style="text-align: center">{{"৳".number_format($total_dealer_sale_sum,2)}}</td>
                <td style="text-align: center">{{"৳".number_format($total_dealer_profit_amount_sum,2)}}{{"("}}{{number_format(($total_dealer_profit_amount_sum/$total_purchase_sum)*100,2)}}{{"%)"}}</td>
                <td style="text-align: center"><nobr>{{number_format($total_dealer_profit_percent_sum/$last_loop_iteration,2)."%"."(Avg.)"}}</nobr></td>

            </tr>
        </tbody>
    </table>

</div>
</div>
<div class="text-center">
    <button class="btn btn-primary btn-sm" onclick="printpage('divName')">print</button>
</div>
@endsection

<script type="text/javascript">

window.setInterval(function(){
    let scroll_left = $("#divName").scrollLeft();
    let scroll_top = $("#divName").scrollTop();

    if(scroll_left>0){
        
        $('.inner td:nth-child(1)').css('position','sticky');
        $('.inner td:nth-child(1)').css('left','0');
        $('.inner td:nth-child(1)').css('background','white');
        
        $('.inner td:nth-child(2)').css('position','sticky');
        $('.inner td:nth-child(2)').css('left','46');
        $('.inner td:nth-child(2)').css('background','white');
        
        $('.inner td:nth-child(3)').css('position','sticky');
        $('.inner td:nth-child(3)').css('left','160');
        $('.inner td:nth-child(3)').css('background','white');
        $('.inner td:nth-child(3)').css('border-left','1px solid');
        // $('.inner td:nth-child(1)').css('z-index','2');



        $('.inner th:nth-child(1)').css('position','sticky !important');
        $('.inner th:nth-child(1)').css('left','0');
        $('.inner th:nth-child(1)').css('z-index','2');

        $('.inner th:nth-child(2)').css('position','sticky !important');
        $('.inner th:nth-child(2)').css('left','46');
        $('.inner th:nth-child(2)').css('z-index','2');

        $('.inner th:nth-child(3)').css('position','sticky !important');
        $('.inner th:nth-child(3)').css('left','160');
        $('.inner th:nth-child(3)').css('z-index','2');
    }else{
        $('.inner th:nth-child(1)').css('z-index','1');
        $('.inner th:nth-child(2)').css('z-index','1');
        $('.inner th:nth-child(3)').css('z-index','1');
    }

    if(scroll_top>0){
        $('.inner th:nth-child(1)').css('z-index','1');
        $('.inner th:nth-child(2)').css('z-index','1');
        $('.inner th:nth-child(3)').css('z-index','1');
    }
}, 200);

function printpage(divName){

    // var printContents = document.getElementById(divName).innerHTML;
    // var originalContents = document.body.innerHTML;

    // document.body.innerHTML = printContents;
    
    // window.print();

    // document.body.innerHTML = originalContents;
    
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
