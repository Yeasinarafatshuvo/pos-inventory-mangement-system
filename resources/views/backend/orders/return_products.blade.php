@extends('backend.layouts.app')
@section('content')
<style>
    .jumbotron{
        padding-top: 5px;
        padding-bottom: 5px;
    }

</style>
<div class="jumbotron pl-0 pr-0">
    <h4 class="bg-primary text-center text-white">Update Return Product</h4>
    <form  id="return_product_create_form">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Order Invoice') }}</h5>
        </div>
        <div class="card-body" style="padding-bottom: 10px;">
            <select class="form-control aiz-selectpicker" name="invoice_number" data-live-search="true"
                title="{{ translate('Select Invoice') }}">
                @foreach ($invoice_numbers as $invoice_item)
                    <option value="{{ $invoice_item->code }}">{{ $invoice_item->code }}</option>
                @endforeach
            </select>
        </div>
        <div class="row" id="customre_details" style="z-index: 2">
           
        </div>
    </div>
    <div class="order-details">
        
            <div class="row">
                <div class="col-md-12">
                <table class="table table-bordered text-center">
                    <thead>
                      <tr>
                        <th scope="col">SL</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Order Qty</th>
                        <th scope="col">Serial No</th>
                        <th scope="col">Return QTY</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                        <tbody id="append_div">
                                     
                        </tbody>
                      </table>
                    </div>
                    <button class="btn store_return_product btn-primary text-center m-auto store_data" id="valueJusitfy" value="submit">Submit</button>
                </div>
          </form>
    </div>
</div>
@endsection
   



@section('script')
<script type="text/javascript">
$('#valueJusitfy').hide();
$('select').on('change', function() {
  var specefic_order_invoice_numbers
  specefic_order_invoice_numbers  = this.value;
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
  var inc = 1;
  var serial_val = 1;
  $.ajax({
      type: "GET",
      url: "{{route('orders.return_products_specefic_data')}}",
      data: {'invoice_number':specefic_order_invoice_numbers},
      success: function (response) {
          if(response.status == 200){
            $product_qty = response.order_details.length;
            //customer details append
            var user_name = response.user_details['user']['name'];
            var user_phone = response.user_details['user']['phone'];
            var order_id = response.user_details['id'];
         
            var customer_div = '<div class="col-md-4"  style="margin-left: 40px;padding-left: 0px;padding-bottom: 10px; font-size:15px;color:#6c50e1">Customer Name: '+user_name+'</div>\
                                <div class="col-md-4" style=" font-size:15px;color:#6c50e1">Customer Phone: '+user_phone+'</div>\
                                <input type="hidden" id="order_id" name="order_id" value="'+order_id+'" />'
               
            var order_id_values = $('#order_id').val();
            if(!order_id_values){
                $('#customre_details').append(customer_div);
            }
            //products details append
            for (let index = 0; index < $product_qty; index++) {
                var product_id = response.order_details[index]['product_id'];
                var product_qty = response.order_details[index]['quantity'];
                var product_name = response.order_details[index]['product']['name'];
                var product_id = response.order_details[index]['product_id'];
               
                var row = '<tr class="product_row_'+inc+'" >\
                            <td >'+serial_val+++'</td>\
                            <td >'+product_name+'</td>\
                            <td >'+product_qty+'</td>\
                            <input type="hidden" id="product_id" name="product_id[]" value="'+product_id+'" />\
                            <td class="text-justify"><textarea class="form-control" id="serial_id'+product_id+'" name="prod_serial_num[]" onKeyup="CheckDuplicate('+product_id+');"></textarea></td>\
                            <input type="hidden" id="return_product_qty'+product_id+'" name="return_product_qty[]" value="" />\
                            <td id="return_qty_field'+product_id+'"></td>\
                            <td  class="text-center" ><a onclick="deleteRow('+inc+')" class=" btn btn-outline-danger btn-sm mt-3">DEL</a></td>\
                        </tr>';
                if(!order_id_values){
                    $('#append_div').append(row);
                }        
               
                inc++;
            }
          }
         
      }
  });
  $('#valueJusitfy').show();
});

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
    let result = Object.keys(obj);
    if(nameArr.length > result.length){
        $('#serial_id'+prod_id).val('');
        $('#serial_id'+prod_id).val(result.toString());
    }

    $('#return_product_qty'+prod_id).val(result.length);
     $('#return_qty_field'+prod_id).html(result.length);     
           
}

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
          var product_table_row = $('#append_div tr').length;
          if(product_table_row == 0)
          {
            $('#customre_details').children().remove();
          }
    });

}

$('.store_return_product').on('click', function (e) {
  e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "{{route('orders.store_return_order')}}",
        data: $('#return_product_create_form').serialize(),
        success: function (response) {
           
            if(response.status == 200){
                Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                });

                $('#customre_details').remove();
                $('#append_div').remove();

            }
        }
    });
});

</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
