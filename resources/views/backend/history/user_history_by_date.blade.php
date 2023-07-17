@extends('backend.layouts.app')
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/> 
@section('content')
<style>

</style>

<div id="print_area">
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h2 class="bg-primary  text-center" style="color:white;">USER HISTORY LIST LIST FROM {{$start_date}} TO {{$end_date}}</h2>
            </div>
        </div>
    </div>
    @php
    function print_invoice_number($user_history_action, $combined_order_id, $order_id)
    {
        switch ($user_history_action) {
            case "payment status":
                $combined_order_data =  \App\Models\Order::select('combined_order_id')->where('id',$order_id)->first();
                if($combined_order_data !== null){
                    $invoice_number = \App\Models\CombinedOrder::select('code')->where('id',$combined_order_data->combined_order_id)->first();
                  echo  $invoice_number->code;
                }
                break;
            case "delivery status":
                $combined_order_data =  \App\Models\Order::select('combined_order_id')->where('id',$order_id)->first();
                if($combined_order_data !== null){
                    $invoice_number = \App\Models\CombinedOrder::select('code')->where('id',$combined_order_data->combined_order_id)->first();
                  echo  $invoice_number->code;
                }
                break;
            case "Shipping cost update":
                $new_Data = \App\Models\CombinedOrder::select('code')->where('id', $combined_order_id)->first();
                if($new_Data !== null)
                {
                    echo  $new_Data->code;
                }
                
                break;
            default:
                echo '';
                break;
        }
    }
@endphp
<div class="card">
 
    <div class="card-body"> 
    <div class="row">
    <div class="col-md-12 pr-3">
        <table id="dtBasicExample" class="table table-striped table-bordered table-sm p-2 text-center" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th scope="col">SL</th>
                <th scope="col">User Name</th>
                <th scope="col">User Action</th>
                <th scope="col">Product Name</th>
                <th scope="col">Order Number</th>
                <th scope="col">Changes Information</th>
                <th scope="col">Date</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($user_all_history as $key => $history_item)
                   <tr>
                            <td>{{$key + 1}}</td>
                            <td><nobr>{{$history_item['user']['0']['name']}}</nobr></td>
                           <td><nobr>{{(!empty($history_item->user_action))?$history_item->user_action:""}}</nobr></td> 
                           @if(!empty($history_item->user_action))
                               @if ($history_item->user_action == 'product update' || 'Add Product')
                                <td>
                                    <?php
                                    if(!empty($history_item['products']['0']['name'])){
                                        echo $history_item['products']['0']['name'];
                                    }
                                    ?>
                                </td>
                               @else
                                <td></td>
                               @endif
                           @endif

                           @if(!empty($history_item->user_action))
                               @if ($history_item->user_action == 'Advance payment' || 'payment status' || 'delivery status' || 'Shipping cost update')
                                @if (!empty($history_item->invoice_id))
                                <td><nobr>{{$history_item->invoice_id}}</nobr></td>
                                @else
                                    <td>
                                        @php
                                            print_invoice_number($history_item->user_action, $history_item->combined_order_id, $history_item->order_id);
                                        @endphp
                                    </td>
                                @endif
                               
                               @else
                                <td></td>
                               @endif
                           @endif
                            <td style="text-align:left;width: 50px;word-wrap:break-word;">
                                @php
                                   $full_information = str_split($history_item->change_information);
                                   foreach ($full_information as $key => $info_item) {
                                        if($info_item == ',')
                                        {
                                            echo "<b>".$info_item."</b>"."<br>";
                                        }else {
                                            echo "<b>".$info_item."</b>";
                                        }
                                        
                                   }
     
                                @endphp
                                
                                
                            </td>
                            <td><nobr>{{date('Y-m-d h:s A',strtotime($history_item->created_at))}}</nobr></td>
                        </tr>
                @endforeach
            </tbody>
          </table>
    </div>
    </div>
    </div>
</div>
</div>
<div class="text-center">
<button class="btn btn-primary btn-sm" onclick="printpage('print_area')">print</button>
</div>
@endsection




@section('script')
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
$('#dtBasicExample').DataTable();
$('.dataTables_length').addClass('bs-select');

});

function printpage(print_area){

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
var printContents = document.getElementById(print_area).innerHTML;
var originalContents = document.body.innerHTML;

document.body.innerHTML = printContents;
$('.dataTables_filter').remove();
$('#dtBasicExample_length').remove();
$('#dtBasicExample_info').remove();
$('#dtBasicExample_paginate').remove();
$('#search_date').hide();
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
