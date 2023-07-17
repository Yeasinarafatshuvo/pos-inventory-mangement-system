@extends('backend.layouts.app')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    .main_div{
        background-color: #F0F1F7;
    }
    .inner_div{
        background-color: #ffffff;
    }
    #suggesstion-box li{
      list-style-type: none;
      cursor: pointer;
      padding: 5px;
      padding-left: 30px;
    }
    #suggesstion-box li:hover{
      color: green;

    }
    .product_list{
      display: flex;
      flex-direction: row;
       
     
    }
    .product_list .form-check{
      margin-left: 20px;
      
    }
    .product_list .form-check .form-check-label{
      cursor: pointer;
    }
    table th{
        color: #6c50e1;
    }
    #suggesstion-box, #suggesstion-box_suppliers li:hover{
            color: #3498db;

    }
    #suggesstion-box, #suggesstion-box_suppliers li{
      list-style-type: none;
      padding: 3px;
      padding-bottom: 0px;
      padding-top: 0px;
    }
    #append_div {
      display: block;
      max-height: 250px;
      overflow-y: scroll;
    }
    table thead, table tbody tr {
      display: table;
      width: 100%;
      table-layout: fixed;
    }
    
    .search-box_popup {
        position: absolute;
        z-index: 21000;
        top: 100;
        right: 0;
        left: 0;
        width: 50%;
        border-radius: 3px;
        background: #fff;
        margin-left: 35px;
        box-shadow: 0 10px 15px rgb(0 0 0 / 20%), 0 1px 0 rgb(0 0 0 / 5%) inset, 0 -5px 0 0 #fff;
    }
    
  
</style>
<div class="container-fluid main_div">
    
     <div class="row">
       {{-- product-serch section start  --}}
          <div class="search_box col-md-12 mt-1">
                  <div class="form-group">
                      <input id="select_product" type="text" class="form-control"  placeholder="Search By Product Name">
                      <div id="suggesstion-box" class="search-box_popup"></div>
                  </div>
          </div>
           {{-- product-serch section end  --}}
      </div>
      {{-- start dealer panel  --}}
      <div class="row">
        <div class="col-md-12">
          <div class="container-fluid d-flex pl-0 pr-0">
            <div class="category_search flex-fill p-1 mb-3">
               <input type="text" class="form-control" id="supplier_serch_id" placeholder="Search By Suppliers Phone">
               <div id="suggesstion-box_suppliers" class="search-box_popup"></div>
            </div>

            <div class="icon-box1 text-center">
                <i class="fa fa-user-plus  ml-1 "  data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" aria-hidden="true" style="color:green; margin-top:14px;cursor: pointer"></i>
            </div>
          </div>
            {{-- start  modal for adding suppliers --}}
              <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title text-success" id="exampleModalLabel">Add Suppliers</h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                      <span id="close_supplier_modal" aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form>
                      <div class="form-group">
                        <label for="supplier-name" class="col-form-label">Supplier Name:</label>
                        <input type="text" class="form-control supplier_name" id="supplier-name" >
                      </div>
                      <div class="form-group">
                          <label for="supplier-phone" class="col-form-label">Supplier Phone:</label>
                          <input type="text" class="form-control supplier_phone" id="supplier-phone" >
                      </div>
                      <div class="form-group">
                        <label for="supplier-email" class="col-form-label">Supplier Email:</label>
                        <input type="text" class="form-control supplier_email" id="supplier-email" >
                      </div>
                      <div class="form-group">
                        <label for="supplier-address" class="col-form-label">Supplier Address:</label>
                        <input type="text" class="form-control supplier_address" id="supplier-address" >
                      </div>
                      <button type="button" id="add_supplier" class="btn btn-primary">Submit</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            {{-- end  modal for adding suppliers --}}
        </div>
      </div>
      {{-- end dealer panel  --}}
      <form  id="inventory_create-form">
        <div class="row">
          <div class="col-md-12">
            <div class="supplier_select_data">
              <table class="table table-bordered text-center">
                <tbody id="append_supplier_data" class="newclass text-center">
                  
                </tbody>
              </table>
          </div>
          </div>
        </div>
     
      {{-- product show and form start --}}
       
          <div class="row">
              <div class="col-md-12">
              <table class="table table-bordered text-center">
                  <thead>
                    <tr>
                      <th style="width:78px;" scope="col">SL</th>
                      <th style="width:127px;" scope="col">Product Name</th>
                      <th style="width:130px;"scope="col">Product Image</th>
                      <th style="width:190px;" scope="col">Serial No</th>
                      <th style="width:100px;" scope="col">Product QTY</th>
                      <th style="width:120px;" scope="col">Purchase Price</th>
                      <th style="width:120px;" scope="col">Total Price</th>
                      <th style="width:80px;" scope="col">Action</th>
                    </tr>
                  </thead>
                      <tbody id="append_div">
                                   
                      </tbody>
                    </table>
                    <table class="table table-bordered text-center">
                      <thead class="product_navbar">
                      <tr >
                          <tr class="text-primary">
                          <th scope="col">Total Payable</th>
                          <th scope="col">Payment Type</th>
                          <th scope="col">Transaction Id</th>
                          <th scope="col">Total Paid</th>
                          <th scope="col">Total Due</th>
                      </tr>
                      </thead>
                      <tbody id="append_div2" >
                          
                          <tr id="data-row-id" class="data-row-class text-center product_row">
                              <td><input type="text" id="total_payable"  name="total_payable" class="form-control" style="border:none;"  value="" /></td>
                              <td>
                                <select class="form-control" name="payment_type" id="exampleSelect">
                                  <option value="cash" selected>Cash</option>
                                  <option value="bkash">Bkash</option>
                                  <option value="nagad">Nagad</option>
                                  <option value="bank">Bank</option>
                                </select>
                              </td>
                              <td><input type="text" min="1" id="transaction_id" name="transaction_id" placeholder="Transaction id" class="form-control" value=""></td>
                              <td><input type="text" min="1" id="total_paid" name="total_paid" class="form-control"  value=""></td>
                              <td><input  min="1" id="total_due" name="total_due" class="form-control "  value=""></td>
                          </tr>
                      </tbody>
                  </table>
                  </div>
                  @can('can_save')
                  <button class="btn btn-primary text-center m-auto store_data" id="valueJusitfy" value="submit">Receive</button>
                  @endcan
              </div>
            </form>
        
    {{-- product show and form end    --}}
</div>
<script type="text/javascript">
//hide the submit button before adding product
$('#valueJusitfy').hide();

// start show product search result
$('#select_product').on('keyup', function(){

  $("#suggesstion-box").html("");
  var value = $(this).val();

  $.ajax({
    type:"GET",
    url:"{{route('pos.search')}}",
    data:{'search':value,'id':""},
    success:function(response){
      var data ="";
      var p_length = response == ""?0:response.match_products.data.length;   
      for(i=0;i<p_length;i++){
        data += '<li onClick="selectProduct('+ response.match_products.data[i]["id"]+ ')">'+ response.match_products.data[i]["name"]+'</li>';
      }

        $("#suggesstion-box").show();
        if(value !== ""){
          $("#suggesstion-box").html(data);
        }
        else{
          $("#suggesstion-box").html("");
        }

      }

  });

});
// end show product search result

var inc=1;
function selectProduct(id) {
    $.ajax({
    type:"GET",
    url:"{{route('pos.search')}}",
    data:{'search':"",'id':id},
    success:function(response){
      var base_url = window.location.origin;
      var file_name ="";
      if(response.match_products.image_url === null){

      }else{
          file_name = response.match_products.image_url.file_name;          
      }
      var row = '<tr class="product_row_'+inc+'" id="'+response.match_products.id+'">\
          <td style="width:80px;">'+inc+'</td>\
          <td style="width:133px;" class="text-justify">'+response.match_products.name+' <input type="hidden" id="product_id" name="product_id[]" class="row_len check_duplicate" value="'+response.match_products.id+'"></td>\
          <td style="width:138px;"><img style="height: 84px; width: 84px;" src="'+ base_url+"/"+file_name+'" class="card-img-top" alt="..."></td>\
          <td style="width:199px;" class="text-justify"><textarea class="form-control serial_id"rows="3" id="serial_id'+response.match_products.id+'" name="prod_serial_num[]" onKeyup="CheckDuplicate('+response.match_products.id+'); calculation('+inc+'); checInventoryData('+response.match_products.id+')"></textarea></td>\
          <input  type="hidden" id="stock_qty" name="product_qty[]" class="qty_'+inc+' qty_unique_class_'+response.match_products.id+'" value="">\
          <td style="width:103px;" id="qty_field'+response.match_products.id+'" ></td>\
          <td style="width:124px;"><input type="text" class="form-control price_'+inc+'" name="product_purchase_price[]" value="'+response.match_products.purchase_price+'"></td>\
          <input type="hidden" class="total_price_append_'+inc+' row_class_count" value="0">\
          <td style="width:122px;" class="total_price_'+inc+'"></td>\
          <td style="width:68px;" class="text-center" style="width:120px" ><a  onclick="deleteRow('+inc+')" class="dell btn btn-outline-danger btn-sm mt-3">DEL</a></td>\
        </tr>';
       
        //checking duplicate product
        var newValue = 0;
        $('.check_duplicate').each(function(){
            if(this.value == response.match_products.id){
                newValue++;
            }
        });
        if(!(newValue == 1))
        {  
          $('#append_div').append(row);
          $('#select_product').val('');
          $(".serial_id").focus();
        }
      
      inc++;
      
      } 

  });
$("#suggesstion-box").hide();
//show submit button if data is added
$('#valueJusitfy').show();

}

//remove product list function
function deleteRow(value){

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
            $('.product_row_'+value).remove();
            calculation(value);
            //delete submit button if no data is available
            if ( $('#append_div').children().length < 1 ) {
            $('#valueJusitfy').hide();
            }
              Swal.fire(
              'Deleted!',
              'Product has been deleted.',
              'success'
              )               
          }
    });

}

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
           $('#qty_field'+prod_id).html(unique_serial.length);     
           
}

//check serial already exist or not?
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
            url: "{{route('pos.inventory.check_duplicate_product')}}",
            data: data,
            success: function (response) { 
                if(response.status == 1){
                  Swal.fire({
                        title: 'This Serial Number Already Exist!',
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var serial_data =  $('#serial_id'+prod_id).val();
                            var array_serial = serial_data.split(',');

                            var result = array_serial.filter(function (currentValue) {  
                                return  currentValue !== "" && currentValue !== response.product_serial;
                            });
                            
                            if(result == ""){
                                $('#serial_id'+prod_id).val('');
                                $('#serial_id'+prod_id).val(result);
                                $('#qty_field'+prod_id).html(result.length);
                                $('.qty_unique_class_'+prod_id).val(result.length);
                            }else{
                                $('#serial_id'+prod_id).val('');
                                $('#serial_id'+prod_id).val(result+',');
                                $('#qty_field'+prod_id).html(result.length);
                                $('.qty_unique_class_'+prod_id).val(result.length);
                            }
                               
                        }
                    })   
                }else{
                    
                               
            }                      
        }
    }); 
}

//end check serial already exist or not?

//store proeduct barcode serial number
$('.store_data').on('click', function (e) {
  e.preventDefault();
      
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //validate supplier 
    if(!$('#supplier_id').val())
      {
        Swal.fire({
            position: 'top',
            icon: 'error',
            title: 'Please select a Supplier!!!',
            showConfirmButton: false,
            timer: 1500
        })
      }
      if(!$('#total_paid').val())
      {
        Swal.fire({
            position: 'top',
            icon: 'error',
            title: 'Please Add Paid Amount!!!',
            showConfirmButton: false,
            timer: 1500
        })
      }
    if($('#supplier_id').val() && $('#total_paid').val())
    {
    $.ajax({
      type: "POST",
      url: "{{route('pos.inventory.store')}}",
      data: $('#inventory_create-form').serialize(),
      success: function (response) {
        if(response.status == 200){
        Swal.fire({
                position: 'top',
                icon: 'success',
                title: response.message,
                showConfirmButton: false,
                timer: 1500
        });
        $('#append_div').remove();
        $('#append_supplier_data').remove();
        $('#total_payable').val('');
        $('#total_paid').val('');
        $('#total_due').val('');
        $('#transaction_id').val('');
        $('#valueJusitfy').hide();

      }
      }
    });
  }
  
});


$("#supplier_serch_id").on('keyup', function () {
    $("#suggesstion-box_suppliers").html("");
    var input_suppliers_value = $(this).val();

    $.ajax({
        type: "GET",
        url: "{{route('pos.inventory.supplier_search')}}",
        data: {'search':input_suppliers_value, 'id':""},
        success: function (response) {
            var data = "";
            var search_result_data_length = response.data.length;
            for (let i = 0; i <search_result_data_length; i++) {
              var customer_name = response.data[i]["name"];
              var customer_phone = response.data[i]["phone"];
              var phone_name = customer_name.concat(" (", customer_phone, ")")
              data += '<li style="cursor:pointer" onClick="selectSupplierInfo('+response.data[i]["id"] +')">'+phone_name+'</li>'

            }

            $("#suggesstion-box_suppliers").show();
            if(input_suppliers_value !== "")
            {
            $("#suggesstion-box_suppliers").html(data);
            }
            else
            {
            $("#suggesstion-box_suppliers").html("");
            }

        }
    });

});

//start function for append data after select specefic supplier
var supplier_increments = 1;
function selectSupplierInfo(id)
{
    $.ajax({
        type: "GET",
        url: "{{route('pos.inventory.supplier_search')}}",
        data:{'search':"",'id':id},
        success: function (response) {

            var row = 
                '<tr id="data-row-id_customer" class="customer_row_'+supplier_increments+' supplier_common">\
                    <td class="text-bold"><input type="hidden" name="supplier_id" id="supplier_id" class="form-control" value="'+response.id+'">'+response.name+'</td>\
                    <td class="text-bold">'+response.phone+'</td>\
                    <td class="text-center"><i class="fa fa-trash" onclick="deleteSupplier('+supplier_increments+')" style="color:red; cursor:pointer" aria-hidden="true"></i></td>\
                </tr>';

            if($('.supplier_common').length <1){
                $('#append_supplier_data').append(row);
                $('#supplier_serch_id').val('');
            }

            $("#suggesstion-box_suppliers").hide();


            supplier_increments++;
        }
    });

}
//end function for append data after select specefic supplier
//delete supplier 
//delete customer function
function deleteSupplier(customer_row_value)
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
//end supplier

//save customer
$('#add_supplier').on('click', function (e) {
  e.preventDefault();
  var data = {
    'name': $('.supplier_name').val(),
    'phone': $('.supplier_phone').val(),
    'email': $('.supplier_email').val(),
    'address': $('.supplier_address').val(),
  }
  if(data.name != ""  && data.phone != ""  && data.email != ""  && data.address != "")
  {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
      type: "POST",
      url: "{{route('pos.inventory.supplier_store')}}",
      data: data,
      success: function (response) {
        if(response.status == 200)
          {
              
              $('#close_supplier_modal').trigger('click');
              Swal.fire({
                  position: 'top',
                  icon: 'success',
                  title: response.message,
                  showConfirmButton: false,
                  timer: 1500
                  })
              
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

//calculation total purchase price as per qty
function calculation(inc)
{
  var row_quantity = parseInt($('.qty_'+inc).val());
  var row_price = $('.price_'+inc).val();
  $('.total_price_'+inc).html(row_price * row_quantity);
  $('.total_price_append_'+inc).val(row_price * row_quantity);

  var total_price_sum = 0;
  $(".row_class_count").each(function(){
    total_price_sum += parseFloat(this.value);
  });

  $('#total_payable').val(total_price_sum.toFixed(2));  

}

$('#total_paid').keyup(function (e) { 
  var total_paid_amount = $('#total_paid').val();
  if(!total_paid_amount){
    total_paid_amount = 0;
  }
  var total_payable_amount =  $('#total_payable').val();
  var total_due_amount = parseFloat(total_payable_amount) - parseFloat(total_paid_amount);
  $('#total_due').val(total_due_amount.toFixed(2));

});

$("#total_payable").prop("readonly", true);
$("#total_due").prop("readonly", true);
</script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>



@endsection