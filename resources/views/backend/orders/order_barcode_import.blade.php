@extends('backend.layouts.app')

@section('content')
<style>

    
    
     
</style>
    <div>
        @php
            function cehcked_order_status($online_order_delivery_status){
                    switch ($online_order_delivery_status) {
                        case 'confirmed':
                            return false;
                            break;
                        case 'delivered':
                            return false;
                            break;
                        case 'shipped':
                            return false;
                            break;
                        case 'processed':
                            return false;
                            break;
                        case 'order_placed':
                            return true;
                            break;
                            
                        default:
                            return true;
                            break;
                    }
            }
            
        @endphp
        <div class="card">
            <div class="card-title text-center">Barcode Import Panel Order Wise</div>
            <form action="{{route('order_barcode_import.post', $online_order_id)}}" method="POST">
                @csrf
                <table class="table table-bordered">
                    <thead>
                        <th>SL</th>
                        <th class="text-center">Product Name</th>
                        <th class="text-center">Product Order QTY</th>
                        <th class="text-center">Product Serial Number</th>
                        @if (cehcked_order_status($online_order_delivery_status))
                            <th class="text-center">Bracode QTY</th>
                        @endif
                    </thead>
                    <tbody>
                        <?php
                            $total_product_item = 0;
                            foreach ($order_details_data as $key => $single_order_details) {
                                $total_product_item += $single_order_details->quantity;
                            }
                        ?>
                        @foreach ($order_details_data as $key =>  $order_details_item)
                        <tr>
                            <td class="text-center">{{$key+1}}</td>
                            <input type="hidden" name="product_id[]" value="{{$order_details_item->product_id}}">
                            <td class="text-center">{{$order_details_item->product->name}}</td>
                            <input type="hidden" name="order_qty[]" value="{{$order_details_item->quantity}}">
                            <td class="text-center">{{$order_details_item->quantity}}</td>
                                @if (!cehcked_order_status($online_order_delivery_status))
                                    <td style="margin: 0; padding:0" class="text-center">
                                        @if (!empty($order_details_item->prod_serial_num))
                                            @php
                                                $si_no = json_decode($order_details_item->prod_serial_num);
                                                $total_products_count =  count($si_no);
                                                if($total_products_count <62 && $total_products_count > 44){
                                                    $total_products_count = 62; 
                                                }
                                                for($i = 0; $i < $total_products_count; $i++ ){
                                                    if(array_key_exists($i, $si_no)){
                                                        echo $si_no[$i].','."<br>";
                                                        
                                                    }else{
                                                        echo ''."<br>";
                                                    }
                                                
                                                }
                                            @endphp
                                        @else
                                            <textarea  id="serial_id{{$order_details_item->product_id}}" name="prod_serial_num[]" onKeyup="checInventoryData(<?php echo $order_details_item->product_id ?>,<?php echo $order_details_item->quantity ?>);CheckDuplicate(<?php echo $order_details_item->product_id ?>,<?php echo $order_details_item->quantity ?>)" id="" style="width: 100%;box-sizing: border-box;border: none; margin:0;"></textarea>
                                        @endif
                                    </td>
                                @else
                            <td style="margin: 0; padding:0"><textarea  id="serial_id{{$order_details_item->product_id}}" name="prod_serial_num[]" onKeyup="checInventoryData(<?php echo $order_details_item->product_id ?>,<?php echo $order_details_item->quantity ?>);CheckDuplicate(<?php echo $order_details_item->product_id ?>,<?php echo $order_details_item->quantity ?>)" id="" style="width: 100%;box-sizing: border-box;border: none; margin:0;"></textarea></td>
                            @endif
                            @if (cehcked_order_status($online_order_delivery_status))
                                <td class="qty_span_class_{{$order_details_item->product_id}}" id="qty_field{{$key}}"></td>
                            @endif
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if (!cehcked_order_status($online_order_delivery_status) && $online_order_delivery_status != 'confirmed' &&  $online_order_delivery_status != 'processed' &&  $online_order_delivery_status != 'delivered'  &&  $online_order_delivery_status != 'shipped' || $online_order_delivery_status == 'order_placed')
                    <button class="btn btn-primary" onclick="enable_texarea()"  style="width: 100%">Submit Barcode</button>
                @endif
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
    //start code for check inventory serial 
    var previous_si="";
    function checInventoryData(prod_id, product_qty_id){
   
        setTimeout(function() { 
            var serial_n = $('#serial_id'+prod_id).val();
            if(serial_n.slice(-1) == ","){
                if(previous_si == serial_n){
                   
                }else{
                    checInventoryDatas(prod_id, product_qty_id); 
                }

                previous_si = $('#serial_id'+prod_id).val();
            }
        }, 1000);
    }
    var not_found_data = [];
    function checInventoryDatas(prod_id,product_qty_id){
        var serial_data =  $('#serial_id'+prod_id).val();
        var data = {
        'id':prod_id,
        'serial_no': serial_data
        }
       
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
         $.ajax({
            type: "POST",
            url: "{{route('pos.match_inventory_product')}}",
            data: data,
            success: function (response) { 
                if(response == 1){

                }else{
                    
                    Swal.fire({
                        title: 'Not Found?',
                        text: "pleease insert his serial no into inventory product!!!",
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#serial_id'+prod_id).prop('disabled',false);

                            var serial_data =  $('#serial_id'+prod_id).val();
                            var array_serial = serial_data.split(',');
                            not_found_data.push(response);

                            if(not_found_data !== null){
                                var new_array_serial = array_serial.filter(function(item) {
                                return !not_found_data.includes(item); 
                                })
                            }else{
                                new_array_serial = array_serial;
                            }

                            var result = new_array_serial.filter(function (currentValue) {  
                                return  currentValue !== "" && currentValue !== response;
                            });
                          

                            if(result == ""){
                                $('#serial_id'+prod_id).val('');
                                $('#serial_id'+prod_id).val(result);
                                $('.qty_span_class_'+prod_id).html(result.length); 

                            }else{
                                $('#serial_id'+prod_id).val('');
                                $('#serial_id'+prod_id).val(result+',');
                                $('.qty_span_class_'+prod_id).html(result.length);    
                            } 
                            
                            
                        }
                    }) 
                }                    
            }
        }); 
}

//find  product duplicate  serial number 
function CheckDuplicate(prod_id, product_qty_id){
  window.event.preventDefault();

    var valuesoftext = $('#serial_id'+prod_id).val();
    setTimeout(() => {
        $('#serial_id'+prod_id).val(valuesoftext.trim().concat(','));
    }, 500);

    var names =  $('#serial_id'+prod_id).val();
    var nameArr = names.split(',');

    obj = {};
    for(let i of nameArr){
        obj[i] = true;
    }
    let unique_serial = Object.keys(obj);
    if(nameArr.length > unique_serial.length){
        //play warning audio when got duplicate serial
        let audio = new Audio("/audio/warning.mp3");
        audio.play();
        Swal.fire({
            title: 'Duplicate Serial Found!',
            text: "Already scaned done!",
            icon: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#serial_id'+prod_id).val('');
                if(this.event.pointerType == 'mouse')
                {
                    $('#serial_id'+prod_id).val(unique_serial.toString()+',').focus();
                }else{
                    $('#serial_id'+prod_id).val(unique_serial.toString()).focus();
                }

            }
            
        })
        
    }
    
    if(unique_serial.length == product_qty_id){
        setTimeout(() => {
            $('#serial_id'+prod_id).attr("disabled", "disabled");
        }, 1000);
    }
    $('.qty_span_class_'+prod_id).html(unique_serial.length);
    
           
}

function enable_texarea(){
    $('textarea').prop('disabled',false);
}


    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
