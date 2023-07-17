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
                <th id="heading_part"  colspan="22" style="text-align: center;font-size:20px; background-color:#6c50e1;">Cash Report</th>
            </tr>
            <tr style="line-height:14px;font-size:12px;border:1px solid black;">
                <th style="text-align: center">Date</th>
                <th style="text-align: center">Order Value(Today)(Shipped/Delivered)</th>
                <th style="text-align: center">Received Value(Today)</th>
                <th style="text-align: center">Total Receivable Due(All)</th>
                <th style="text-align: center">Total Receivable Value(All)</th>
                <th style="text-align: center">Total Received Value(All)</th>
            </tr>
        </thead>
        <tbody>
            <tr style="line-height:14px;font-size:12px;border:1px solid black;">
                <th style="text-align: center"><nobr>{{date('d-m-Y')}}</nobr></th>
                <th style="text-align: center">{{$order_value}}</th>
                <th style="text-align: center">{{$total_received_today}}</th>
                <th style="text-align: center">{{$total_receivable_due}}</th>
                <th style="text-align: center">{{$total_receivable}}</th>
                <th style="text-align: center">{{$total_received_all}}</th>
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

// window.setInterval(function(){
//     let scroll_left = $("#divName").scrollLeft();
//     let scroll_top = $("#divName").scrollTop();

//     if(scroll_left>0){
        
//         $('.inner td:nth-child(1)').css('position','sticky');
//         $('.inner td:nth-child(1)').css('left','0');
//         $('.inner td:nth-child(1)').css('background','white');
        
//         $('.inner td:nth-child(2)').css('position','sticky');
//         $('.inner td:nth-child(2)').css('left','46');
//         $('.inner td:nth-child(2)').css('background','white');
        
//         $('.inner td:nth-child(3)').css('position','sticky');
//         $('.inner td:nth-child(3)').css('left','160');
//         $('.inner td:nth-child(3)').css('background','white');
//         $('.inner td:nth-child(3)').css('border-left','1px solid');
//         // $('.inner td:nth-child(1)').css('z-index','2');



//         $('.inner th:nth-child(1)').css('position','sticky !important');
//         $('.inner th:nth-child(1)').css('left','0');
//         $('.inner th:nth-child(1)').css('z-index','2');

//         $('.inner th:nth-child(2)').css('position','sticky !important');
//         $('.inner th:nth-child(2)').css('left','46');
//         $('.inner th:nth-child(2)').css('z-index','2');

//         $('.inner th:nth-child(3)').css('position','sticky !important');
//         $('.inner th:nth-child(3)').css('left','160');
//         $('.inner th:nth-child(3)').css('z-index','2');
//     }else{
//         $('.inner th:nth-child(1)').css('z-index','1');
//         $('.inner th:nth-child(2)').css('z-index','1');
//         $('.inner th:nth-child(3)').css('z-index','1');
//     }

//     if(scroll_top>0){
//         $('.inner th:nth-child(1)').css('z-index','1');
//         $('.inner th:nth-child(2)').css('z-index','1');
//         $('.inner th:nth-child(3)').css('z-index','1');
//     }
// }, 200);

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
