@extends('backend.layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
        #append_div {
            display: block;
            max-height: 220px;
            overflow-y: scroll;
            }

            table thead, table tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
            } 
        .main_div{
            background-color: #F0F1F7;
        }
        .serch_option input:hover{
            border: 1px solid #3498db;

        }
        #suggesstion-box, #suggesstion-box_customer li{
            list-style-type: none;
            cursor: pointer;
            padding: 5px;
            padding-left: 30px;
        }
        #suggesstion-box, #suggesstion-box_customer li:hover{
            color: #3498db;

        }
        .category_brand{
            display: flex;

        }
        .product-group .card-body{
            background-color:#F0F1F7
        }
        .category_brand  .btn{
            border: 1px solid #ccc;
            text-align: left;
        }
        .category_brand  .btn:hover{
        background-color:transparent;
        color: black;
        border: 1px solid #3498db;
        }
        .product-group .img-box{

            position: relative;
        }

        .product-group .img-box::before{
            content: attr(data-image);
            position: absolute;
            top: 0;
            left: 0;
            height: 20px;
            background-color: black;
            color: white;
            text-align: center;
        }

        .single-item .card{
            -webkit-transition: .6s;
            transition: .6s;
            border-radius: 8px;
            border: 0;
        }

        .single-item:hover .card{
            margin-top: -20px;

            box-shadow: 2px 2px 10px #ccc
        }
        .single-item p{
                font-size: 14px;
                text-align: left;
        }
        .single-item .card-body{
            height: 165px !important;
        }

        .shipping{
                background-color:#ecf0f1;
        }

        .shipping .icon-box1 a i{
            text-decoration: none;

        }
        .shipping .icon-box1 a i{
                font-size: 18px;
                padding: 10px;
                background: white;
                color: 34465D;
                margin-top: 22px;
                margin-left: -7px;
                -webkit-transition: .6s ease-in-out;
                transition: .6s ease-in-out;
        }

        .blankPage{
                background: #ECF0F1;
        }
        .blankPage .icon-box i{
            font-size: 42px;

        }
        tbody p{
                font-size: 20px;
        }

        .shipping  .btn{
                font-size: 18px;
                border: 1px solid #ccc;
                text-align: left;
                -webkit-transition: .8s ease-in-out;
                transition: .8s ease-in-out;
                border-radius: 4px;
        }
        .shipping  .cash_btn:hover{
            background-color:transparent;
            color: black;
            border: 1px solid #3498db;
        }

        .shipping select{
            border-radius: 3px;

        }
        .shipping .select-group select{
            font-size: 18px;
        }

        .shipping select:hover{
            border: 1px solid #3498db;

        }
       .modal-stack{     
         z-index: 95 !important;
       }
       .modal-dialog{
        padding-top: 100px;
        padding-bottom: 200px;
       }
       #exampleModal{
        background: #08080873;
       }
       #suggesstion-box li:hover{
            color: #6c50e1;
       }
       input.largerCheckbox{
           width: 20px;
           height: 20px;
           cursor: pointer;
       }
       .swal2-confirm{
           margin-left: 10px;
       }
       #Total_payment{
            font-size: 22px;
            font-weight: bold;
       }
       .save_order{
           margin-left: 10px;
       }
       #exampleModal{
        overflow-y: scroll;
       }
       #suggesstion-box {
        padding-top: 0px;
        padding-bottom: 0px;
        margin-left: 16px;
        top: 39px;
       }
       #suggesstion-box li{
        padding: 3px;
       }

       .search-box_popup {
            position: absolute;
            z-index: 21000;
            top: 100;
            right: 0;
            left: 0;
            width: 42%;
            border-radius: 3px;
            background: #fff;
            margin-left: 35px;
            box-shadow: 0 10px 15px rgb(0 0 0 / 20%), 0 1px 0 rgb(0 0 0 / 5%) inset, 0 -5px 0 0 #fff;
        }
        .search-box_product {
            position: absolute;
            z-index: 21000;
            top: 100;
            right: 0;
            left: 0;
            width: 80%;
            border-radius: 3px;
            background: #fff;
            margin-left: 35px;
            box-shadow: 0 10px 15px rgb(0 0 0 / 20%), 0 1px 0 rgb(0 0 0 / 5%) inset, 0 -5px 0 0 #fff;
        }
       
</style>
<div class="overflow" style="overflow: hidden;">
    <div class="container-fluid">
        <div class="row mt-3 bg-light">
                {{-- shipping --}}
                <div class="col-md-12  ">
                    <div class="shipping">
                            <div class="category_search  p-1 mb-3">
                               <div class="row">
                                <div class="col-md-12">
                                    <input id="select_product" type="text"  class="form-control"  placeholder="Scan Your Product Barcode">
                                </div>
                                
                               </div>
                            </div>
                       
                        {{-- customer data --}}
                        <form action="" id="order_create-form">
                            <div class="cart-details">
                                
                                <table class="table table-bordered text-center">
                                    <thead id="thead_product">
                                            <tr class="text-primary">

                                                <th  style="width: 140px" scope="col">Product</th>
                                                <th  style="width: 140px" scope="col">Serial No</th>
                                                <th style="width: 80px" scope="col">QTY</th>
                                                <th  style="width: 80px" scope="col">Remove</th>
                                             
                                            </tr>
                                    </thead>

                                    <tbody id="append_div" class="newclass text-center" >
                                        

                                    </tbody>
                                </table>

                                @can('can_save')
                                <button type="button" class="mr-1 mb-2 btn btn-primary  btn-sm btn-right cash_btn justify-end save_order">Return Product</button>
                                @endcan
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection




@section('script')
<script type="text/javascript">
        var Total_Payment;
        var num_p_r;
        var inc=1;

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

        //start code for product search using barcode and append sale list for sale
  
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
                                        '<tr id="data-row-id" class="data-row-class product_row_'+inc+' common_class">\
                                            <td style="width:133px;"><input type="hidden" name="product_id[]" class="check_duplicate" value="'+response.match_products.data[0].id+'" >'+response.match_products.data[0].name.substr(0,40)+'</td>\
                                            <td style="width:130px;"><textarea class="form-control"rows="3" id="serial_id'+response.match_products.data[0].id+'" name="prod_serial_num[]" "></textarea></td>\
                                            <td style="width:75px;"><span class="qty_span_class_'+response.match_products.data[0].id+'"></span><input id="qty_input'+inc+'" type="hidden" name="product_qty[]"  class="form-control qty_'+inc+' check_min_qty qty_unique_class_'+response.match_products.data[0].id+'"  value="1"></td>\
                                            <td style="width:68px;" class="text-center col-md-1 ml-0 pt-4" ><i class="fa fa-trash fa-2x" onclick="deleteRow('+inc+')" style="color:red; cursor:pointer" aria-hidden="true"></i></td>\
                                        </tr>';
                                var newValue = 0;
                                $('.check_duplicate').each(function(){
                                    if(this.value == response.match_products.data[0].id){
                                        newValue++;
                                    }
                                });

                                if(!(newValue == 1)){
                                    $('#append_div').append(row);
                                    $('#select_product').val('');
                                    $('#serial_id'+response.match_products.data[0].id).val(response.serial+',');

                                    //add qty number
                                    $('.qty_unique_class_'+response.match_products.data[0].id).val(1);
                                    $('.qty_span_class_'+response.match_products.data[0].id).html(1); 

                                    //sub total and grand total price count
                                    var total_sub_value = 0;
                                    $(".row_class_count").each(function(){

                                        total_sub_value += parseFloat(this.value);

                                    });
                                    $('#total').val(total_sub_value.toFixed(2));   
                                    
                                    $('#Total_payment').html(total_sub_value.toFixed(2));
                                    $('#grand_total').val(parseFloat(total_sub_value).toFixed(2));
                                   
                                            
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

                                    var previous_product_price = $('#product_price_id'+response.match_products.data[0].id).val();

                                    //var serial_qty = parseInt(newResult.length) * parseFloat(product_price);
                                    var serial_qty = parseInt(newResult.length) * parseFloat(previous_product_price);

                                   
            
                                    $('.common_serial_class_html'+response.match_products.data[0].id).html(parseFloat(serial_qty));
                                    $('.common_serial_class'+response.match_products.data[0].id).val(parseFloat(serial_qty));
                                    
                                    var total_sub_value = 0;
                                    $(".row_class_count").each(function(){

                                        total_sub_value += parseFloat(this.value);

                                    });
                                    $('#total').val(total_sub_value.toFixed(2));   
                                    
                                    $('#Total_payment').html(total_sub_value.toFixed(2));
                                    $('#grand_total').val(parseFloat(total_sub_value).toFixed(2));
                                    
                                               
                            }

                            num_p_r = $('.data-row-class').length;
              
                            if(num_p_r>0){
                                $('#blank_page').hide();
                            }
                            else{
                                $('#blank_page').show();
                            }
                            inc++;
                            }
                        });
                        $('#select_product').val('');
                    }, 200)
                );

        //end code for product search using barcode and append sale list for sale



        
       
      

        //remove product list function
        function deleteRow(value){
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) =>
            {
                if (result.isConfirmed) {
                   
                    $('.product_row_'+value).remove();
                    var num_p_r = $('.data-row-class').length;
                    if(num_p_r < 2){
                        $('#blank_page').show();
                    }

                     //append data to the total sub value after deleting  product
                    var total_sub_value = 0;
                    $(".row_class_count").each(function(){
                        total_sub_value += parseFloat(this.value);
                    });
                    $('#total').val(total_sub_value.toFixed(2));
                    $('#Total_payment').html(parseFloat(total_sub_value).toFixed(2));
                    $('#grand_total').val(parseFloat(total_sub_value).toFixed(2));
                    

                swalWithBootstrapButtons.fire(
                    'Deleted!',
                    'Your Product has been deleted.',
                    'success'
                )

                } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
                )
                {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your imaginary product is safe :',
                        'error')
                }
            });

        }


        $('.save_order').on('click', function (e) {
            e.preventDefault();

            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
               
      
            if($('.data-row-class').length >= 1){
         
                $.ajax({
                    type: "POST",
                    url: "{{route('purchase.return.product.store')}}",
                    data: $('#order_create-form').serialize(),
                    success: function (response) {
                        console.log(response);
                        if(response.status == 200)
                        {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                                })
                            $('#append_div').remove();
                             
                        }
                    }
                });
            }
        });

    //check duplicate serial number
    function CheckDuplicate(prod_id){
            window.event.preventDefault();
           
            var valuesoftext = $('#serial_id'+prod_id).val();
            setTimeout(() => {
                $('#serial_id'+prod_id).val(valuesoftext.trim().concat(','));
            }, 500);

            var names =  $('#serial_id'+prod_id).val();
            var nameArr = names.split(',');

            var result = nameArr.filter(function (currentValue, index, array) {  
                return  array.indexOf(currentValue) == index;
            });
            
            if(nameArr.length > result.length){
                $('#serial_id'+prod_id).val('');
                $('#serial_id'+prod_id).val(result.toString());

            }

            $('.qty_unique_class_'+prod_id).val(result.length);
            $('.qty_span_class_'+prod_id).html(result.length);    
    }

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









</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
