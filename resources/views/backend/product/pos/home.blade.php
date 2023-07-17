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
                {{-- start product slection option --}}
                <div class="col-sm-12 col-md-4 ">
                    <div class="content">
                        <div class="serch_option">
                            <div class="row">
                                <div class="search_box col-md-12 mt-1">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <input id="select_product" type="text"  class="form-control"  placeholder="Search By Product Name">
                                                </div>
                                                
                                                <div class="col-md-4 pt-2 pl-0">
                                                    <input type="checkbox" class="barcode_chcekbox" id="serial_search" name="serial_search" value="1">
                                                    <label for="serial_search" style="font-size: 20px; color:#6c50e1; cursor: pointer;">Barcode</label>
                                                </div>
                                                <div id="suggesstion-box" class="search-box_product"></div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <select class="form-control" id="price_category" style="cursor: pointer">
                                                          <option value="cus_01" selected>Customer</option>
                                                          <option value="deal_02">Dealer</option>
                                                          <option value="corp_03">Corporate</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="category_brand mt-2">
                            <div class="container-fluid row ml-0 mr-0 pl-0 pr-0">
                               
                                <div class="category_search col col-sm  col-md-6 col-lg col-xl pl-0 pr-0">
                                    <select id="category_id"  onchange="category_brand_search_func()" class="form-select btn btn-block pl-0" aria-label="Default select example pl-0">
                                        <option value="" selected>All Categories</option>
                                        @foreach ($categories as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="brand_search col-12 col-sm col-md-6 col-lg col-xl ">
                                    <select id="brand_id"  onchange="category_brand_search_func()" class="form-select btn btn-block pl-0"  style="margin-right:19px" aria-label="Default select example">
                                        <option value="" selected>All Brands</option>
                                        @foreach ($brands as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                        <hr class="line mr-1 mb-4">
                        <div class="product-group">
                            <div id="append_product_search" class="row" style="height: 60vh; overflow:scroll;overflow-x: hidden">
                               
                            </div>
                        </div>

                    </div>

                </div>
                {{-- shipping --}}
                <div class="col-sm-12 col-md-8  ">
                    
                    <div class="shipping">
                        <div class="container-fluid d-flex">
                            <div class="category_search flex-fill p-1 mb-3">
                               <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control serch_customer" id="customer_serch_id" placeholder="Search Customer ">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control serch_customer" id="staff_serch_id" placeholder="Search Staff ">
                                </div>
                               </div>
                               <div id="suggesstion-box_customer" class="search-box_popup"></div>
                            </div>

                            <div class="icon-box1 text-center">
                                <i class="fa fa-user-plus  ml-1 "  data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" aria-hidden="true" style="color:green; margin-top:14px;cursor: pointer"></i>
                            </div>
                        </div>
                       {{-- start  modal for adding customer --}}
                       <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title text-success" id="exampleModalLabel">Add Customer</h5>
                              <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                                <span id="close_customer_modal" aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <form>
                              
                                <div class="form-group">
                                  <label for="customer-name" class="col-form-label">Customer Name:</label>
                                  <input type="text" class="form-control customer_name" id="customer-name" >
                                </div>
                                <div class="form-group">
                                    <label for="customer-phone" class="col-form-label">Customer Phone:</label>
                                    <input type="text" class="form-control customre_phone" id="customer-phone" >
                                </div>
                                <div class="form-group">
                                    <label for="customer_address"  class="col-form-label">Customer Address:</label>
                                    <input type="text" class="form-control customer_address" id="customer_address" >
                                </div>
                                <div class="form-group">
                                    <label for="customer_postal_code" class="col-form-label">Customer Postal Code:</label>
                                    <input type="text" class="form-control customer_postal_code" id="customer_postal_code" >
                                </div>
                                <div class="form-group">
                                    <label for="country">Select State</label>
                                    <select class="form-control aiz-selectpicker" id="state" name="state" data-live-search="true">
                                        <option value="">Select State</option> 
                                        @foreach ($all_state as $state)
                                        <option value="{{$state->id}}">{{$state->name}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="country">Select City</label>
                                    <select class="form-control aiz-selectpicker" id="city" name="city" data-live-search="true">
                                        <option value="">Select City</option>
                                        @foreach ($all_bd_cities as $key =>$city_of_bd)
                                        <option value="{{$key}}">{{$city_of_bd}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <button type="button" id="add_customer" class="btn btn-primary">Submit</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                       {{-- end  modal for adding customer --}}
                        {{-- customer data --}}
                        <form action="" id="order_create-form">
                        <div class="customer_select_data">
                            <table class="table table-bordered text-center">
                               <tbody id="append_customer_data" class="newclass text-center">

                               </tbody>
                            </table>
                            <table class="table table-bordered text-center">
                                <tbody id="append_staff_data" class="newclass text-center">
 
                                </tbody>
                             </table>
                        </div>

                            <div class="cart-details">
                                
                                <table class="table table-bordered text-center">
                                    <thead id="thead_product">
                                            <tr class="text-primary">

                                                <th  style="width: 140px" scope="col">Product</th>
                                                <th  style="width: 140px" scope="col">Serial No</th>
                                                <th style="width: 80px" scope="col">QTY</th>
                                                <th  style="width: 90px" scope="col">Price</th>
                                                <th  style="width: 100px" scope="col">Total</th>
                                                <th  style="width: 80px" scope="col">Remove</th>
                                             
                                            </tr>
                                    </thead>

                                    <tbody id="append_div" class="newclass text-center" >
                                        <tr id="blank_page" style="">
                                            <td class="blankPage pt-5 pb-5 "  colspan="7">
                                                <div class="icon-box text-center">
                                                    <i class="fa fa-smile align-self-center "></i>
                                                </div>
                                                <div class="text-center">

                                                    <p>Nothing Found</p>

                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>

                                <table class="table table-bordered text-center">
                                    <thead class="product_navbar">
                                    <tr >
                                        <tr class="text-primary">

                                        <th scope="col">SubTotal</th>
                                        <th scope="col">Total Tax</th>
                                        <th scope="col" >
                                            <input class="form-check-input" onclick="check_shiping_cost()" type="checkbox" value="" id="pos_shipping_cost" style="cursor: pointer;">
                                            <label class="form-check-label" for="pos_shipping_cost" style="cursor: pointer;">
                                                 Shipping Cost
                                            </label>
                                            </th>
                                        <th scope="col">Special Discount</th>
                                        <th scope="col">FINAL TOTAL</th>
                                    </tr>
                                    </thead>
                                    <tbody id="append_div2" >          
                                        <tr id="data-row-id" class="data-row-class text-center product_row">
                                            <td><input type="text" id="total" name="total" class="form-control total" style="border:none;" disabled="disabled" value="" /></td>
                                            <td><input id="tax_input" onkeyup="text_input_calculations()" type="text" min="1" name="tax" class="form-control tax"  value=""></td>
                                            <td><input id="shipping_cost_input" onkeyup="shipping_cost_calculation()" type="text" min="1" name="shipping_cost" class="form-control shipping_cost"  value=""></td>
                                            <td><input id="dicount_input" onkeyup="discount_calculation()" type="text" min="1" name="special_discount" class="form-control discount" value=""></td>
                                            <input id="grand_total" type="hidden"  name="grand_total" value="">
                                            <td id="Total_payment"></td>
                                        </tr>

                                    </tbody>
                                </table>
                                @can('can_save')
                                <button type="button" class="mr-1 mb-2 btn btn-primary  btn-sm btn-right cash_btn justify-end save_order">Pay With Cash</button>
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
    var multiply;
        var total_tax;
        var discount;
        var Total_Payment;
        var Payment_With_Tax;
        var all_product_multiply=0;
        var num_p_r;

        //Product payment
        function calculation(inc)
        {
            //product prize
            var row_quantity = parseInt($('.qty_'+inc).val());
            if(window.event.keyCode == 38 || window.event.keyCode == 39){
                row_quantity = row_quantity+1;
            }
            if(window.event.keyCode == 40 || window.event.keyCode == 37){
                if(!(row_quantity ==1)){
                    row_quantity = row_quantity-1;
                }  

            }
            if(window.event.keyCode == 8){
                row_quantity = 1;
            }

            var row_price = $('.price_'+inc).val();
            $("input[class *= 'common_price']").each(function(){
                multiply = row_quantity*row_price;
            });

            $('#SubTotal_'+inc).html(multiply);
            $('.pro_sub_tot_'+inc).val(multiply);
            //append qty change data to the hidden input value in the delete table column
            $('#del_total'+inc).val(multiply);

            //append data to the total sub value after changing qty
            var total_sub_value = 0;
            $(".row_class_count").each(function(){
                total_sub_value += parseFloat(this.value);
            });
            $('#total').val(total_sub_value.toFixed(2));
            $('#Total_payment').html(parseFloat(total_sub_value).toFixed(2));
            $('#grand_total').val(parseFloat(total_sub_value).toFixed(2));


        }
        //end total payment


        
        //final payment based on tax input keyup
        function text_input_calculations() {

            var total_tax = parseFloat($('.tax').val());
            
            if(!total_tax){
                total_tax = 0;
            }


            var total_discount = parseFloat($('.discount').val());
            if(!total_discount){
                total_discount = 0;
            }

            var sub_total = parseFloat($('#total').val());
            //var final_total = sub_total + total_tax - total_discount;
            var shipping_cost_val = $("#shipping_cost_input").val();
            if(shipping_cost_val == "" || null){
                shipping_cost_val = 0;
            }
            var final_total = sub_total + total_tax + parseFloat(shipping_cost_val);
            $('#Total_payment').html(parseFloat(final_total-total_discount).toFixed(2));
            $('#grand_total').val(parseFloat(final_total).toFixed(2));
           
        };

        //final payment based on discount keyup
        function discount_calculation() {
            var total_tax = parseFloat($('.tax').val());
            if(!total_tax){
                total_tax = 0;
            }

            var total_discount = parseFloat($('.discount').val());
            if(!total_discount){
                total_discount = 0;
            }

            var sub_total = parseFloat($('#total').val());
            //var final_total = sub_total + total_tax - total_discount;
            var shipping_cost_val = $("#shipping_cost_input").val();
            if(shipping_cost_val == "" || null){
                shipping_cost_val = 0;
            }
            
            var final_total = sub_total + total_tax + parseFloat(shipping_cost_val);
            $('#Total_payment').html(parseFloat(final_total-total_discount).toFixed(2));
        };

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

                                // start set product price for difference category
                                var product_price = 0;
                                if($('#price_category').val() == 'cus_01'){
                                    product_price = response.match_products.data[0].highest_price
                                    //set discount price based on flat and percentage
                                    if(response.discount_date == true){
                                        if(response.match_products.data[0].discount_type == "flat"){
                                            product_price = (parseInt( response.match_products.data[0].highest_price) -  parseInt( response.match_products.data[0].discount)).toFixed(2);
                                        }else if(response.match_products.data[0].discount_type == "percent"){
                                            var discount_price = (parseInt( response.match_products.data[0].highest_price) * parseInt( response.match_products.data[0].discount)) / 100;
                                            product_price = (parseFloat(response.match_products.data[0].highest_price) - discount_price).toFixed(2);
                                        }
                                    }
                                }else if($('#price_category').val() == 'deal_02'){
                                    product_price = response.match_products.data[0].dealer_price
                                    if(!product_price){
                                        product_price = 0;
                                    }
                                }else if($('#price_category').val() == 'corp_03'){
                                    product_price = response.match_products.data[0].corporate_price
                                    if(!product_price){
                                        product_price = 0;
                                    }
                                } 
                                //end set product price for difference category

                                var row =
                                        '<tr id="data-row-id" class="data-row-class product_row_'+inc+' common_class">\
                                            <td style="width:145px;"><input type="hidden" name="product_id[]" class="check_duplicate" value="'+response.match_products.data[0].id+'" >'+response.match_products.data[0].name.substr(0,40)+'</td>\
                                            <td style="width:145px;"><textarea class="form-control"rows="3" id="serial_id'+response.match_products.data[0].id+'" name="prod_serial_num[]" "></textarea></td>\
                                            <td style="width:82px;"><span class="qty_span_class_'+response.match_products.data[0].id+'"></span><input id="qty_input'+inc+'" type="hidden" name="product_qty[]"  class="form-control qty_'+inc+' check_min_qty qty_unique_class_'+response.match_products.data[0].id+'"  value="1"></td>\
                                            <input type="hidden" name="actual_price[]" value="'+response.match_products.data[0].highest_price+'">\
                                            <td style="width:95px;" class="pt-4"><input type="number" class="form-control price_'+inc+'" id="product_price_id'+response.match_products.data[0].id+'" onKeyup="changeProductPrice('+inc+')" name="unit_price[]" value="'+product_price+'" ></td>\
                                            <input type="hidden" class="pro_sub_tot_'+inc+'" name="pro_sub_total[]" value="'+product_price+'">\
                                            <td style="width:102px;" id="SubTotal_'+inc+'" class="pt-4 common_serial_class_html'+response.match_products.data[0].id+'">'+product_price+'</td>\
                                            <td style="width:68px;" class="text-center col-md-1 ml-0 pt-4" ><input id="del_total'+inc+'"  class="form-control row_class_count common_price total_pr_'+inc+' common_serial_class'+response.match_products.data[0].id+'" type="hidden" value="'+product_price+'" style="cursor:none;border:none;" disabled="disabled" ><i class="fa fa-trash fa-2x" onclick="deleteRow('+inc+')" style="color:red; cursor:pointer" aria-hidden="true"></i></td>\
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

            }
            
        });
        //end code for product search using barcode and append sale list for sale

        function changeProductPrice(inc){
            var change_product_price_values = $('.price_'+inc).val();
            var total_product_qty = $('#qty_input'+inc).val();
            var updated_sub_total = change_product_price_values * total_product_qty;
            $('#SubTotal_'+inc).html(updated_sub_total);
            $('.pro_sub_tot_'+inc).val(updated_sub_total);
            calculation(inc);
            text_input_calculations();

            
        }

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


        
        var inc=1;
        var count = 1;
        function selectProduct(id)
        {
            $.ajax(
            {
                type:"GET",
                url:"{{route('pos.search')}}",
                data:{'search':"",'id':id},
        
                success:function(response)
                {

                    //set product price for difference category
                    var product_price = 0;
                        if($('#price_category').val() == 'cus_01'){
                            product_price = response.match_products.highest_price
                            //set discount price based on flat and percentage
                            if(response.discount_date == true){
                                if(response.match_products.discount_type == "flat"){
                                    product_price = (parseInt( response.match_products.highest_price) -  parseInt( response.match_products.discount)).toFixed(2);
                                }else if(response.match_products.discount_type == "percent"){
                                    var discount_price = (parseInt( response.match_products.highest_price) * parseInt( response.match_products.discount)) / 100;
                                    product_price = (parseFloat(response.match_products.highest_price) - discount_price).toFixed(2);
                                }
                            }
                        }else if($('#price_category').val() == 'deal_02'){
                            product_price = response.match_products.dealer_price
                            if(!product_price){
                                product_price = 0;
                            }
                        }else if($('#price_category').val() == 'corp_03'){
                            product_price = response.match_products.corporate_price
                            if(!product_price){
                                product_price = 0;
                            }
                        }

                        var row =
                        '<tr id="data-row-id" class="data-row-class product_row_'+inc+' common_class">\
                            <td style="width:145px;"><input type="hidden" name="product_id[]" class="check_duplicate" value="'+response.match_products.id+'" >'+response.match_products.name.substr(0,40)+'</td>\
                            <td style="width:145px;"><textarea class="form-control"rows="3" id="serial_id'+response.match_products.id+'" name="prod_serial_num[]" onKeyup="CheckDuplicate('+response.match_products.id+'); calculation('+inc+');checInventoryData('+response.match_products.id+')"></textarea></td>\
                            <td style="width:82px;"><span class="qty_span_class_'+response.match_products.id+'"></span><input id="qty_input" type="hidden" name="product_qty[]"  class="form-control qty_'+inc+' check_min_qty qty_unique_class_'+response.match_products.id+'"  value="1"></td>\
                            <input type="hidden" name="actual_price[]" value="'+response.match_products.highest_price+'">\
                            <td style="width:95px;" class="pt-4"><input type="hidden" class="price_'+inc+'" name="unit_price[]" value="'+product_price+'" >'+product_price+'</td>\
                            <input type="hidden" class="pro_sub_tot_'+inc+'" name="pro_sub_total[]" value="'+product_price+'">\
                            <td style="width:102px;" id="SubTotal_'+inc+'" class="pt-4">'+product_price+'</td>\
                            <td style="width:68px;" class="text-center col-md-1 ml-0 pt-4" ><input id="del_total'+inc+'"  class="form-control row_class_count common_price total_pr_'+inc+'" type="hidden" value="'+product_price+'" style="cursor:none;border:none;" disabled="disabled" ><i class="fa fa-trash fa-2x" onclick="deleteRow('+inc+')" style="color:red; cursor:pointer" aria-hidden="true"></i></td>\
                        </tr>';

                        var newValue = 0;
                        $('.check_duplicate').each(function(){
                            if(this.value == response.match_products.id){
                                newValue++;
                            }
                        });

                        if(!(newValue == 1)){
                            $('#append_div').append(row);
                            $('#select_product').val('');
                        }

                         num_p_r = $('.data-row-class').length;
                      
                            if(num_p_r>0){
                                $('#blank_page').hide();
                            }
                            else{
                                $('#blank_page').show();
                            }

                inc++;
                },
                complete: function (data) {

                    var total_sub_value = 0;
                    $(".row_class_count").each(function(){

                        total_sub_value += parseFloat(this.value);

                    });

                   $('#total').val(total_sub_value.toFixed(2));
                   $('#Total_payment').html(total_sub_value.toFixed(2));
                   $('#grand_total').val(parseFloat(total_sub_value).toFixed(2));
                   
                    
                    
                  
                    $('.qty_'+count).on('keyup', function(e){
                        
                        if(e.keyCode == 38 || e.keyCode == 39){
                            
                            this.value= parseInt(this.value) + 1;
                            
                        }
                        else if(e.keyCode == 40 || e.keyCode == 37)
                        {
                            this.value= parseInt(this.value) - 1;
                        }
                        
                        if(this.value < 1 || this.value == ""){
                           this.value = 1
                          
                        }
                    });

                    

                    count++;
                }

                

               
            });

            $("#suggesstion-box").hide();


        }

        //manually barcode submetting
        $('#select_product').keydown(function(e) {
            if (e.keyCode == 13) {
                let barcodeInputValue = $(this).val();

                $.ajax({
                    type: "GET",
                    url: "{{route('pos.barocode_search_manually')}}",
                    data:{'serial_number':barcodeInputValue},
                    success: function (response) {

                        // start set product price for difference category
                        var product_price = 0;
                        if($('#price_category').val() == 'cus_01'){
                            product_price = response.match_products.data[0].highest_price
                            //set discount price based on flat and percentage
                            if(response.discount_date == true){
                                if(response.match_products.data[0].discount_type == "flat"){
                                    product_price = (parseInt( response.match_products.data[0].highest_price) -  parseInt( response.match_products.data[0].discount)).toFixed(2);
                                }else if(response.match_products.data[0].discount_type == "percent"){
                                    var discount_price = (parseInt( response.match_products.data[0].highest_price) * parseInt( response.match_products.data[0].discount)) / 100;
                                    product_price = (parseFloat(response.match_products.data[0].highest_price) - discount_price).toFixed(2);
                                }
                            }
                        }else if($('#price_category').val() == 'deal_02'){
                            product_price = response.match_products.data[0].dealer_price
                            if(!product_price){
                                product_price = 0;
                            }
                        }else if($('#price_category').val() == 'corp_03'){
                            product_price = response.match_products.data[0].corporate_price
                            if(!product_price){
                                product_price = 0;
                            }
                        } 
                        //end set product price for difference category

                        var row =
                                '<tr id="data-row-id" class="data-row-class product_row_'+inc+' common_class">\
                                    <td style="width:145px;"><input type="hidden" name="product_id[]" class="check_duplicate" value="'+response.match_products.data[0].id+'" >'+response.match_products.data[0].name.substr(0,40)+'</td>\
                                    <td style="width:145px;"><textarea class="form-control"rows="3" id="serial_id'+response.match_products.data[0].id+'" name="prod_serial_num[]" "></textarea></td>\
                                    <td style="width:82px;"><span class="qty_span_class_'+response.match_products.data[0].id+'"></span><input id="qty_input'+inc+'" type="hidden" name="product_qty[]"  class="form-control qty_'+inc+' check_min_qty qty_unique_class_'+response.match_products.data[0].id+'"  value="1"></td>\
                                    <input type="hidden" name="actual_price[]" value="'+response.match_products.data[0].highest_price+'">\
                                    <td style="width:95px;" class="pt-4"><input type="number" class="form-control price_'+inc+'" id="product_price_id'+response.match_products.data[0].id+'" onKeyup="changeProductPrice('+inc+')" name="unit_price[]" value="'+product_price+'" ></td>\
                                    <input type="hidden" class="pro_sub_tot_'+inc+'" name="pro_sub_total[]" value="'+product_price+'">\
                                    <td style="width:102px;" id="SubTotal_'+inc+'" class="pt-4 common_serial_class_html'+response.match_products.data[0].id+'">'+product_price+'</td>\
                                    <td style="width:68px;" class="text-center col-md-1 ml-0 pt-4" ><input id="del_total'+inc+'"  class="form-control row_class_count common_price total_pr_'+inc+' common_serial_class'+response.match_products.data[0].id+'" type="hidden" value="'+product_price+'" style="cursor:none;border:none;" disabled="disabled" ><i class="fa fa-trash fa-2x" onclick="deleteRow('+inc+')" style="color:red; cursor:pointer" aria-hidden="true"></i></td>\
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
            }
        });


        
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


        
        //code start for customer section
        $(".serch_customer").on('keyup', function () {
            $("#suggesstion-box_customer").html("");
            var input_customer_value = $(this).val();
            var search_id_value = $(this).attr('id');
            if(search_id_value == 'staff_serch_id')
            {
                hit_url = "{{route('pos.staff_search')}}";
            }else if(search_id_value == 'customer_serch_id'){
                hit_url = "{{route('pos.customer_search')}}";
            }
            
            $.ajax({
                type: "GET",
                url: hit_url,
                data: {'search':input_customer_value, 'id':""},
                success: function (response) {
                   
                   var data = "";
                   var search_result_data_length = response.data.length;
                   for (let i = 0; i <search_result_data_length; i++) {
                      var customer_name = response.data[i]["name"];
                      var customer_phone = response.data[i]["phone"];
                      var phone_name = customer_name.concat(" (", customer_phone, ")")
                     
                      if(search_id_value == 'staff_serch_id'){
                        data += '<li onClick="selectStaffInfo('+response.data[i]["id"] +')">'+phone_name+'</li>'
                      }else{
                        data += '<li onClick="selectCustomerInfo('+response.data[i]["id"] +')">'+phone_name+'</li>'
                      }
                      

                   }

                   $("#suggesstion-box_customer").show();
                   if(input_customer_value !== "")
                   {
                    $("#suggesstion-box_customer").html(data);
                   }
                   else
                   {
                    $("#suggesstion-box_customer").html("");
                   }

                }
            });

        });



        //function for append data after select specefic customer
        var increments = 1;
        function selectCustomerInfo(id)
        {
            $.ajax({
                type: "GET",
                url: "{{route('pos.customer_search')}}",
                data:{'search':"",'id':id},
                success: function (response) {

                    var row = 
                        '<tr id="data-row-id_customer" class="customer_row_'+increments+' customer_common">\
                            <td class="text-bold"><input type="hidden" name="customer_id" id="customer_id" class="form-control" value="'+response.id+'">'+response.name+'</td>\
                            <td class="text-bold">'+response.phone+'</td>\
                            <td class="text-bold">Customer</td>\
                            <td class="text-center"><i class="fa fa-trash" onclick="deleteCustomer('+increments+')" style="color:red; cursor:pointer" aria-hidden="true"></i></td>\
                        </tr>';

                    if($('.customer_common').length <1){
                        $('#append_customer_data').append(row);
                        $('#customer_serch_id').val('');
                    }

                    $("#suggesstion-box_customer").hide();


                    increments++;
                }
            });

        }

        //delete customer function
        function deleteCustomer(customer_row_value)
        {


            event.preventDefault();
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
                $('.customer_row_'+customer_row_value).remove();
                Swal.fire(
                'Deleted!',
                'Customer has been deleted.',
                'success'
                )
               
            }
            });
        }

        //customer validation and add
        $('#add_customer').on('click', function (e) {
           e.preventDefault();
           var data = {
               'name' : $('.customer_name').val(),
               'phone' : $('.customre_phone').val(),
               'state' : $('#state').val(),
               'city' : $('#city').val(),
               'customer_address' : $('#customer_address').val(),
               'customer_postal_code' : $('#customer_postal_code').val(),
               
           }
           if(data.name.trim() == "")
           {
            $('.customer_name').addClass("border border-danger");
            $('.customer_name').focus();
           }
           if(data.phone.trim() == "")
           {
            $('.customre_phone').addClass("border border-danger");
            $('.customre_phone').focus();
           }
           if(data.customer_address.trim() == "")
           {
            $('#customer_address').addClass("border border-danger");
            $('#customer_address').focus();
           }
          
           if(data.name != "" && data.phone != "" && data.customer_address != "")
           {
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

            $.ajax({
                type: "POST",
                url: "{{route('pos.customer_store')}}",
                data: data,
                success: function (response) {
                    if(response.status == 200)
                    {
                        
                        $('#close_customer_modal').trigger('click');
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                            });
                       
                        $('.modal-body').find('input').val('');
                    }
                    else if(response.status == 403)
                    {
                        Swal.fire({
                            icon: 'error',
                            text: response.message
                        });
                    }

                }
            });
        }
           
        });

        //search result pop up responsive
        $('#customer_serch_id').keyup(function (e) { 
            $('#suggesstion-box_customer').css('marginLeft', '35px');
        });
    //end customer section
    //start staff section 
    function selectStaffInfo(id)
        {
            $.ajax({
                type: "GET",
                url: "{{route('pos.customer_search')}}",
                data:{'search':"",'id':id},
                success: function (response) {

                    var row = 
                        '<tr id="data-row-id_staff" class="customer_row_'+increments+' staff_common">\
                            <td class="text-bold"><input type="hidden" name="staff_id" id="staff_id" class="form-control" value="'+response.id+'">'+response.name+'</td>\
                            <td class="text-bold">'+response.phone+'</td>\
                            <td class="text-bold">Staff</td>\
                            <td class="text-center"><i class="fa fa-trash" onclick="deleteCustomer('+increments+')" style="color:red; cursor:pointer" aria-hidden="true"></i></td>\
                        </tr>';

                    if($('.staff_common').length <1){
                        $('#append_staff_data').append(row);
                        $('#staff_serch_id').val('');
                    }

                    $("#suggesstion-box_customer").hide();


                    increments++;
                }
            });

        }

        //search result pop up responsive
        $('#staff_serch_id').keyup(function (e) { 
            $('#suggesstion-box_customer').css('marginLeft', '350px');
        });
//end staff section    

        //this funcion is for sarch product as per brand and categories
        function category_brand_search_func(){
           var categoryValues = $('#category_id').val();
           var brandValues = $('#brand_id').val();
           $('#append_product_search').html("");
           var count = 0;
           $.ajax({
               type: "GET",
               url: "{{route('pos.product_select_search')}}",
               data: {'category_id':categoryValues, 'brand_id':brandValues},
               success: function (response) {
                
                   var base_url = window.location.origin;
                   $.each(response.data, function (indexInArray, product) { 
                    var new_row = 
                                    '<div class=" col-sm-3 col-md-3" style="padding: 1px;border: 1px solid #f3f1f1;">\
                                    <div class="single-item">\
                                    <div class="card" onclick="selectProduct('+product.id+')" style="cursor: pointer;">\
                                    <div class="img-box" style="min-height: 120px;padding: 5px;" data-image="'+product.highest_price+'">\
                                    <img src="'+ base_url+"/public/"+product.file_name+'" class="card-img-top" alt="...">\
                                    </div>\
                                    <div class="card-body" style="padding:8px;">\
                                    <p class="card-text" style="display: -webkit-box; overflow: hidden; -webkit-line-clamp: 7; -webkit-box-orient: vertical;">'+product.name+'</p>\
                                    </div>\
                                    </div>\
                                    </div>\
                                    </div>'
                            
                            $('#append_product_search').append(new_row);     
                   });
                                       
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
        
               
            if(!$('#customer_id').val())
            {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Please select a customer!!!',
                    showConfirmButton: false,
                    timer: 1500
                })
            }

            if(!$('#staff_id').val())
            {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Please select staff!!!',
                    showConfirmButton: false,
                    timer: 1500
                })
            }

            var checked_shipping = $('#pos_shipping_cost').is(':checked');
            var shiped_value = $('#shipping_cost_input').val();
            var shipped_validation;
            if(checked_shipping){
                if(shiped_value == "" || null)
                {
                    Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Please Enter Shipping Cost!!!',
                    showConfirmButton: false,
                    timer: 1500
                })
                 shipped_validation = false;
                }else{
                    shipped_validation = true;
                }
            }else{
                shipped_validation = true;
            }
      
            if(($('#customer_id').val()) && ($('#staff_id').val()) && $('.data-row-class').length !== 1 && shipped_validation){
                $.ajax({
                    type: "POST",
                    url: "{{route('pos.create_order')}}",
                    data: $('#order_create-form').serialize(),
                    success: function (response) {
                
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
                            $('#data-row-id_customer').remove();
                            $('#data-row-id_staff').remove();
                            $('#total').val("");
                            $('#tax_input').val("");
                            $('#shipping_cost_input').val("");
                            $('#dicount_input').val("");
                            $('#grand_total').val("");
                            $('#Total_payment').html("");
                            $('#thead_product').after(
                                '<tbody id="append_div" class="newclass text-center" >\
                                    <tr id="blank_page" style="">\
                                        <td class="blankPage pt-5 pb-5 "  colspan="7">\
                                            <div class="icon-box text-center">\
                                                <i class="fa fa-smile align-self-center "></i>\
                                            </div>\
                                            <div class="text-center">\
                                                <p>Nothing Found</p>\
                                            </div>\
                                        </td>\
                                    </tr>\
                                </tbody>'
                            );       
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

$(document).ready(function () {
     $('#shipping_cost_input').prop("readonly", true);
    
});


function check_shiping_cost()
{
    var checked_shipping = $('#pos_shipping_cost').is(':checked');
    if(checked_shipping)
    {
        $('#shipping_cost_input').prop("readonly", false);
    }else{
        $('#shipping_cost_input').prop("readonly", true);
    }
    
}


function shipping_cost_calculation() { 
    var product_row_length = $('.common_class').length;
    if(product_row_length >= 1){
        var shipping_cost_value = $("#shipping_cost_input").val();
        
        if(shipping_cost_value == "" || null){
            shipping_cost_value = 0;
        }
        var discount_value = $("#dicount_input").val();
        if(discount_value == "" || null)
        {
            discount_value = 0;
        }
        var total_tax_value = $('.tax').val();
        if(total_tax_value == "" || null)
        {
            total_tax_value = 0;
        }
        var grand_sub_total = $('#total').val();
        if(grand_sub_total == "" || null)
        {
            grand_sub_total = 0;
        }
        var grand_final_payment = parseFloat(total_tax_value) + parseFloat(grand_sub_total) + parseFloat(shipping_cost_value);
        $('#Total_payment').html(parseFloat(grand_final_payment - parseFloat(discount_value)).toFixed(2));
        $('#grand_total').val(parseFloat(grand_final_payment).toFixed(2));

        
    }

};


</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
