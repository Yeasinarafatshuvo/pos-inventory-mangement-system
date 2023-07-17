@extends('backend.layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .serch_option input:hover{
        border: 1px solid #3498db;

    }
    #suggesstion-box, #suggesstion-box_customer li{
        list-style-type: none;
        cursor: pointer;
        padding: 5px;
        padding-left: 106px;
    }
    #suggesstion-box, #suggesstion-box_customer li:hover{
        color: #3498db;

    }
  

</style>
<div class="jumbotron pl-0 pr-0 pt-0" style="padding-bottom: 10px;">
    <h3 class="text-center">Wastage Product Management</h3>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-9">
            <input id="select_product" type="text"  class="form-control"  placeholder="Search By Product Name">
        </div>
        <div class="col-md-2 pt-2 col-sm-pl-3 pl-0">
            <input type="checkbox" class="barcode_chcekbox" id="serial_search" name="serial_search" value="1">
            <label for="serial_search" style="font-size: 20px; color:#6c50e1; cursor: pointer;">Barcode</label>
        </div>
        <div id="suggesstion-box"></div>
    </div>
</div>
<div class="form-area">
    <form action="">
        <table class="table table-bordered text-center">
            <thead id="thead_product">
                    <tr class="text-primary">
                        <th scope="col">Product Name</th>
                        <th scope="col">Serial No</th>
                        <th scope="col">QTY</th>
                        <th scope="col">Action</th>
                    </tr>
            </thead>
        
            <tbody id="append_div" class="newclass text-center" >
        
            </tbody>
        </table>
        @can('can_save')
        <button type="button" style="display: block" id="wastage_save_btn" class="text-center m-auto  mr-1 mb-2 btn btn-primary  btn-sm ">Save Wastage Products</button>
        @endcan
    </form>
</div>
@endsection
   



@section('script')
<script type="text/javascript">
$('#wastage_save_btn').hide();

//start code for product search typing product name  
$('#select_product').keyup(function (e) { 
    e.preventDefault();
    let value_of_barcode = $('#select_product').val();
    if(!$('.barcode_chcekbox').is(':checked'))
    {
        $.ajax(
        {
            type:"GET",
            url:"{{route('pos.search')}}",
            data:{'search':value_of_barcode,'id':"",},
            success:function(response)
            {
                
                var data ="";
                var p_length = response == ""?0:response.match_products.data.length;
                for(i=0;i<p_length;i++)
                {
                    data += '<li onClick="selectProduct('+response.match_products.data[i]["id"]+ ')">'+ response.match_products.data[i]["name"]+'</li>';
                }

                $("#suggesstion-box").show();
                if(value_of_barcode !== "")
                {

                    $("#suggesstion-box").html(data);
                }
                else
                {
                    $("#suggesstion-box").html("");

                }
            }

        });
    }
});
//end code forproduct search typing product name  

//start code for product search using barcode and append sale list for sale

$('.barcode_chcekbox').click(function () { 
if($('.barcode_chcekbox').is(':checked'))
{
    $("#select_product").keyup(
        abort_multiple_ajax_request(function () {
            $("#suggesstion-box").html("");
            let result = $('#select_product').val();
            $.ajax({
                type: "GET",
                url:"{{route('pos.serial_search')}}",
                data:{'serial_number':result},
                success: function (response) {
                var row =
                        '<tr id="data-row-id" class="data-row-class common_class">\
                            <td style="width:145px;"><input type="hidden" id="product_id" name="product_id" class="check_duplicate" value="'+response.match_products.data[0].id+'" >'+response.match_products.data[0].name.substr(0,40)+'</td>\
                            <td style="width:145px;"><textarea class="form-control prod_serial_number"rows="3" id="serial_id'+response.match_products.data[0].id+'" name="prod_serial_num" "></textarea></td>\
                            <td style="width:40px;"><span class="qty_span_class_'+response.match_products.data[0].id+'"></span><input id="qty_input" type="hidden" name="product_qty"  class="check_min_qty qty_unique_class_'+response.match_products.data[0].id+'"  value="1"></td>\
                            <td style="width:42px;" class="text-center col-md-1 ml-0 pt-4" ><i class="fa fa-trash fa-2x" onclick="deleteRow()" style="color:red; cursor:pointer" aria-hidden="true"></i></td>\
                        </tr>';
                var newValue = 0;
                var multiple_id = 0;
                $('.check_duplicate').each(function(){
                    if(this.value == response.match_products.data[0].id){
                        newValue++;
                    }
                    if(this.value !== response.match_products.data[0].id)
                    {
                        multiple_id++; 
                    }
                });

                if(!(newValue == 1)){
                    if(!(multiple_id == 1))
                    {
                        $('#append_div').append(row);
                        $('#select_product').val('');
                        $('#serial_id'+response.match_products.data[0].id).val(response.serial+',');

                        //add qty number
                        $('.qty_unique_class_'+response.match_products.data[0].id).val(1);
                        $('.qty_span_class_'+response.match_products.data[0].id).html(1);  
                        $('#wastage_save_btn').show();
                    }               
                            
                }

                var previous_serial =  $('#serial_id'+response.match_products.data[0].id).val();

                if(newValue >= 1){
                    var previous_serial =  $('#serial_id'+response.match_products.data[0].id).val();
                    var arrays_data = previous_serial.split(",")
                    arrays_data.push(response.serial);
                    var result = [...new Set(arrays_data)];
                    var newResult = result.filter((item) => item !== "" && item !== ",");
                    $('#serial_id'+response.match_products.data[0].id).val(newResult); 
                    $('#select_product').val('');
                
                    $('.qty_unique_class_'+response.match_products.data[0].id).val(newResult.length);
                    $('.qty_span_class_'+response.match_products.data[0].id).html(newResult.length); 
                                               
                }
            }
            });
            $('#select_product').val('');
        }, 200)
    );

}

});
//end code for product search using barcode and append sale list for sale

//start function for abort multiple request at time
function abort_multiple_ajax_request(fn, delay) {
    let timeoutId;
    return function () {
        if (timeoutId) {
        clearTimeout(timeoutId);
        }
        timeoutId = setTimeout(() => {
        fn();
        }, delay);
    };
}
//end funtion for abort multiple request at time

//srtrt function after clicking product name product append in wastage list
function selectProduct(id)
{
    $.ajax(
    {
        type:"GET",
        url:"{{route('pos.search')}}",
        data:{'search':"",'id':id},
        success:function(response)
        {
                var row =
                '<tr id="data-row-id" class="data-row-class common_class">\
                    <td style="width:145px;"><input type="hidden" name="product_id" id="product_id" class="check_duplicate" value="'+response.match_products.id+'" >'+response.match_products.name.substr(0,40)+'</td>\
                    <td style="width:145px;"><textarea class="form-control prod_serial_number"rows="3" id="serial_id'+response.match_products.id+'" name="prod_serial_num" onKeyup="CheckDuplicate('+response.match_products.id+');checInventoryData('+response.match_products.id+')"></textarea></td>\
                    <td style="width:82px;"><span class="qty_span_class_'+response.match_products.id+'"></span><input id="qty_input" type="hidden" name="product_qty"  class="form-control check_min_qty qty_unique_class_'+response.match_products.id+'"  value="1"></td>\
                    <td style="width:42px;" class="text-center col-md-1 ml-0 pt-4" ><i class="fa fa-trash fa-2x" onclick="deleteRow()" style="color:red; cursor:pointer" aria-hidden="true"></i></td>\
                </tr>';

                var newValue = 0;
                var  multiple_id = 0;
                $('.check_duplicate').each(function(){
                    if(this.value == response.match_products.id){
                        newValue++;
                    }

                    if(this.value !== response.match_products.id)
                    {
                        multiple_id++; 
                    }
                });

                if(!(newValue == 1)){
                    if(!(multiple_id == 1))
                    {
                        $('#append_div').append(row);
                        $('#select_product').val('');
                        $('#wastage_save_btn').show();
                        $('.prod_serial_number').focus();
                    }
                    
                }
        },
                    
    });
    $("#suggesstion-box").hide();
}
//end function after clicking product name product append in wastage list

//find  product duplicate  serial number 
function CheckDuplicate(prod_id){
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

    $('.qty_unique_class_'+prod_id).val(unique_serial.length);
    $('.qty_span_class_'+prod_id).html(unique_serial.length);     
           
}
//end  product duplicate  serial number 

//start delete row 
function deleteRow()
{
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
        if (result.isConfirmed) {
            $('#data-row-id').remove();
            Swal.fire(
            'Deleted!',
            'Your file has been deleted.',
            'success'
            )
        }
    })
}
//end delete row

//start save wastage products
$('#wastage_save_btn').click(function (e) { 
    e.preventDefault();
    var data = {
        'product_id': $('#product_id').val(),
        'serial_num': $('.prod_serial_number').val(),
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "{{route('product.wastage_store')}}",
        data: data,
        success: function (response) {
            if(response.status == 200){
                Swal.fire({
                icon: 'success',
                title: response.message,
                showConfirmButton: false,
                timer: 1500
            })
            $('#data-row-id').remove();
            $('#wastage_save_btn').hide();
            }
        }
    });


});

    //start code for check inventory serial 
    var previous_si="";
    function checInventoryData(prod_id){
        setTimeout(function() { 
            var serial_n = $('#serial_id'+prod_id).val();
            if(serial_n.slice(-1) == ","){
                if(previous_si == serial_n){
                   
                }else{
                    checInventoryDatas(prod_id); 
                }

                previous_si = $('#serial_id'+prod_id).val();
            }
        }, 1000);
    }

    var not_found_data = [];
    function checInventoryDatas(prod_id){
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
                                $('.qty_unique_class_'+prod_id).val(result.length);
                                $('.qty_span_class_'+prod_id).html(result.length);    
                            }else{
                                $('#serial_id'+prod_id).val('');
                                $('#serial_id'+prod_id).val(result+',');
                                $('.qty_unique_class_'+prod_id).val(result.length);
                                $('.qty_span_class_'+prod_id).html(result.length);    
                            } 

                        }
                    }) 
                }                    
            }
        }); 
}
//end check inventory datas 




</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
