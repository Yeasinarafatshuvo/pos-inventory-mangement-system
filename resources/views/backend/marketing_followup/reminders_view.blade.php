@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<style>
    .modal.fade .modal-dialog {
        transition: opacity 0.3s ease-out;
        opacity: 0;
    }
    .modal.fade.show .modal-dialog {
    opacity: 1;
    }
    .shadow_class{
        box-shadow: 0 0 50px #ccc;
    }
</style>
@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
			<h1 class="h3">{{translate('All Reminders of Customers')}}</h1>
	</div>
</div>
<script>


</script>



<div class="card">
    <div class="card-header hide_class">
        <h5 class="mb-0 h6">{{translate('Customers')}}</h5>
    
        <button data-toggle="modal" data-target="#add_reminder_modal" class="btn btn-info"><i class="fa-solid fa-bell"></i> Add Reminder</button>

    </div>
    <div class="card-body" style=" overflow-x: scroll;">
        <table id="dtBasicExample" class="table table-striped table-bordered table-hover text-center" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Date')}}</th>
                    <th>{{translate('Client Name')}}</th>
                    <th>{{translate('Reminder')}}</th>
                    <th data-breakpoints="lg">{{translate('Added By')}}</th>
                    <th data-breakpoints="lg">{{translate('Action')}}</th>
                </tr>
            </thead>
            @foreach($CRM_Reminders as $key => $CRM_Reminder)
                <tr>  
                    <td>{{ ($key+1)}}</td>
                    <td>{{date("F j, Y", strtotime($CRM_Reminder->created_at))}}</td>
                    <td>{{getUserName($CRM_Reminder->customer_id)}}</td>
                    <td>{{strip_tags($CRM_Reminder->note)}}</td>
                    <td>{{getUserName($CRM_Reminder->assign_by)}}</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="reminder_edit('{{$CRM_Reminder->id}}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="btn btn-sm btn-primary text-white" onclick="reminder_view('{{$CRM_Reminder->id}}')"><i class="fa-sharp fa-solid fa-eye"></i></button>
                        <button class="btn btn-sm btn-danger" onclick="reminder_delete('{{$CRM_Reminder->id}}')"><i class="fa-sharp fa-solid fa-trash"></i></button>
                        
                    </td>
                </tr>
            @endforeach
            
            <tbody>

            </tbody>
            <div class="aiz-pagination">
            
            </div>
      </table>

    </div>
</div>

    {{-- start  modal for Edit Modal--}}
    <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title h6">Edit Information</h4>
                        <button type="button" class="close close_button_edit" data-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                
                                <label for="#reminder">Reminder <span class="text-danger">*</span></label>
                                <textarea id="reminder_note" class="add_comment_vanish form-control mb-3" name="description"></textarea>

                                <label for="#product_name_search_id">Interested Product</label>
                                <input type="text" class="form-control mb-3 product_name_search" value="" placeholder="product search here" id="product_name_search_id">

                                <input type="text" hidden id="reminder_id" value="">

                                <input type="text" hidden class="form-control mb-3" id="product_search_result" value="" >
                                
                                <table class="table" id="product_show_table">
                                    <tbody class="product_search_result_reminder shadow_class">
                                    </tbody>
                                </table>

                                <table class="table" id="product_show_table">
                                    <tbody class="search_product_add_reminder">
                                    </tbody>
                                </table>

                                <table class="assign_row"  style="width:100%">
                                </table>

                                <table class="table" id="user_show_add_comment_table">
                                    <tbody class="user_search_result_add_commment shadow_class">
                                    </tbody>
                                </table>
                                
                                <select class="form-select form-control mb-3 reminder_status" aria-label="Default select example">
                                    <option value="0">Select Status</option>
                                </select>
                                <input type="hidden" id="user_id" value="{{ Auth::user()->id }}">
                                <input type="hidden" id="customer_id" value="">

                                <input type="button" class="btn btn-primary" onclick="update_reminder_function()" value="Update Reminder">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary mt-2 close_button_edit" data-dismiss="modal">Cancel</button>
                    </div>
            </div>
        </div>
    </div>
    {{-- end  modal for Edit Modal --}}

    {{-- start  modal for View Modal --}}
    <div class="modal fade" id="view_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title h6">View Information</h4>
                        <button type="button" class="close close_button_view" data-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">

                                
                                <label for="#reminder">Reminder <span class="text-danger">*</span></label>
                                <textarea id="view_reminder_note" readonly class="add_comment_vanish form-control mb-3" name="description"></textarea>

                                <label for="#product_name_search_id">Interested Product</label>
                                <input type="text" hidden id="reminder_id" value="">

                                <input type="text" hidden class="form-control mb-3" id="product_search_result" value="" >
                                

                                <table class="table" id="product_show_table">
                                    <tbody class="search_product_add_reminder">
                                    </tbody>
                                </table>

                                <table class="assign_row"  style="width:100%">
                                </table>

                                <table class="table" id="user_show_add_comment_table">
                                    <tbody class="user_search_result_add_commment shadow_class">
                                    </tbody>
                                </table>
                                
                                <select class="form-select form-control mb-3 reminder_status" aria-label="Default select example">
                                    <option value="0">Select Status</option>
                                </select>
   

            
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary mt-2 close_button_view" data-dismiss="modal">Cancel</button>
                    </div>
            </div>
        </div>
    </div>
    {{-- end  modal for View Modal --}}



       {{-- end  modal for Delete Modal --}}
       <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title h6">Delete Confirmation</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <div class="modal-body text-center">
                            <i class="la la-4x la-warning text-warning mb-4"></i>
                            <p class="fs-18 fw-600 mb-1">Are you sure to delete this?</p>
                            <div>All data related to this will be deleted.</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link mt-2" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-danger mt-2 text-white" id="delete_row">Yes, Delete</a>
                        </div>
                </div>
            </div>
        </div>


@endsection

@section('modal')
    @include('backend.inc.delete_modal')
@endsection

@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PrintArea/2.4.1/jquery.PrintArea.min.js" integrity="sha512-mPA/BA22QPGx1iuaMpZdSsXVsHUTr9OisxHDtdsYj73eDGWG2bTSTLTUOb4TG40JvUyjoTcLF+2srfRchwbodg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
      crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">


// Start for Product Ajax Search in Edit Comment Modal
var onKeyup = function(evt) {
    $(".product_search_result_reminder").html("");
    product_show(evt.target.value);

};
var input = document.getElementById('product_name_search_id');
input.addEventListener('input', onKeyup, false);


var product_list_array = [];

function product_show(product_name){
    var product_name = product_name;

    setTimeout(function() {

        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $.ajax({
        type: "GET",
        url: "{{route('customer_crm.product_search')}}",
        data: {
            product_name:product_name
        },
        success: function (response) {
            
            var serial = 1;
            if(response == null){
                $(".product_search_result_reminder").html("");
            }else{
                var row = "";
                $.each(response.data, function (index, value) { 

                    
                 row += `<tr class="tabel_row mb-3" id=row_>
                        <td class="pt-4"><a href="javascript:void(0)" onclick="product_add_function_reminder(${value.id}, '${value.name}')">${value.name}</a></td>
                    </tr>`;
                    serial = serial+1;
                

                });
                $(".product_search_result_reminder").html(row);
            }


        }
    });


    }, 50);
}
// Edit for Product Ajax Search in Edit Comment Modal

    $(document).ready(function() {
        $('#dtBasicExample').DataTable({
            pageLength: 10,
            filter: true,
            deferRender: true,
            "searching": true,
        });

})

// Edit Reminder Function


function reminder_edit(id){

    $.ajax({
        type: "GET",
        url: "{{ route('marketing_followup.getting_reminder_data') }}",
        data: {
            id: id
        },
        success: function (response) {
            
            var interested_product = response.products;
            var arrayLength = Object.keys(interested_product).length;

            if(arrayLength == null){
                $(".search_product_add_reminder").html("");
            }else{
                var product_row = "";
                $.each(interested_product, function(index, value) {
                    product_row += `<div class="alert alert-success alert-dismissible remove_row">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <input class="product_class check_product_id" type="hidden" name="added_product_id" id="${index}" value="${value}">
                        <strong>${value}</strong>
                    </div>`;
                });
                $(".search_product_add_reminder").html(product_row);
            }


            $('.search_product_add_reminder input.product_class').each(function() {
                var id = $(this).attr('id');
                product_list_array.push(id);
            });
 

            var status_row = "";
            var status = response.CRM_Reminder[0].status;

            // Populate the dropdown menu
            $('.reminder_status').append($('<option>', {
                value: 1,
                text: 'Pending',
                selected: (status == 1 ? true : false)
            }));
            $('.reminder_status').append($('<option>', {
                value: 2,
                text: 'Confirm',
                selected: (status == 2 ? true : false)
            }));
            $('.reminder_status').append($('<option>', {
                value: 3,
                text: 'Rejected',
                selected: (status == 3 ? true : false)
            }));
            
           var assign_row = "";
           
           if(response.assign_to !== null){
            assign_row = `<label for="">Assign To</label>
                 
                <tr>
                    <td>
                        <div class="alert alert-success alert-dismissible">
                            <a href="#" class="close close_button_assign" data-dismiss="alert" aria-label="close">×</a>
                            <input class="assign_id" type="hidden" name="" id="${response.assign_to}" value="${response.assign_to}">
                            <strong>${response.assign_name}</strong>
                        </div>
                    </td>
                </tr>`;
           }
           else{
            assign_row = `<label for="">Assign To</label>
                 <tr>
                    <td>
                        <input type="text" class="form-control mb-3 user_search_reminder" placeholder="Search Assigned to Person" >
                    </td>
                </tr>`;
           }

           $(".assign_row").append(assign_row);

           $(document).on('click', '.close_button_assign', function() {
                assign_row = `<label for="">Assign To</label>
                 <tr>
                    <td>
                        <input type="text" class="form-control mb-3 user_search_reminder" placeholder="Search Assigned to Person" >
                    </td>
                </tr>`;
                 $(".assign_row").html("");
                 $(".assign_row").append(assign_row);
                 $(".user_search_reminder").focus();
            });
        

            $("#reminder_id").val(response.CRM_Reminder[0].id)
            $("#reminder_note").val(response.CRM_Reminder[0].note);
            $("#customer_id").val(response.CRM_Reminder[0].customer_id);


            $("#edit_modal").modal('show');

            $(".close_button_edit").click(function(){

                $('.reminder_status').html("");
                $(".assign_row").html("");
            });
            
        },
        error: function(error) {
            console.log(error);
        }
    });
}


function marketing_followup_comment_view(id){
    
    $.ajax({
        type: "GET",
        url: "{{ route('marketing_followup.getting_data_view_modal') }}",
        data: {
            id:id
        },
        success: function (response) {

            var date = response.data[0].created_at;
            var date = moment.utc(date).format('MMMM DD, YYYY');
            var comments = response.data[0].comments;

            $("#date_view").text(date);
            $("#client_name_view").text(response.customer_name);
            $("#comment_view").val(comments);
            $("#commented_by_view").text(response.added_by_user_name);
            $("#view_modal").modal('show');

            
        }
    });
    
}


// Update Reminder Function

function update_reminder_function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var reminder_id = $("#reminder_id").val();
    var reminder_note = $("#reminder_note").val();
    var customer_id = $("#customer_id").val();
    var user_id = $("#user_id").val();
    var status = $('.reminder_status option:selected').val();
    var assign_id = $(".assign_id").attr("id");

    var product_added_array = [];
    $(".search_product_add_reminder").find("input").each(function(){ product_added_array.push(this.id); });

    if(assign_id == null){
        Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please Fillup Assign To!',
            })
    }
    else{
        $.ajax({
        type: "POST",
        url: "{{route('marketing_followup.update_reminder')}}",
        data: {
            reminder_id        :reminder_id,
            reminder_note      :reminder_note,
            customer_id        :customer_id,
            user_id            :user_id,
            product_added_array:product_added_array,
            assign_id          :assign_id,
            status             :status
        },
        success: function (response) {

            if(response.status == "success"){
                Swal.fire(
                'Good job!',
                'You clicked the button!',
                'success'
                );

                $(".assign_row").html("");
                $("#edit_modal").modal("hide");
                
            }
            else{
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!'
                })
            }

        }
    });

    }

}

var product_list_array = [];

function product_add_function_reminder(product_id, product_name){


    product_row = `<div class="alert alert-success alert-dismissible remove_row">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <input class="check_product_id" type="hidden" name="added_product_id" id="${product_id}" value="${product_name}">
        <strong>${product_name}</strong>
    </div>`;

    

    
    var check = 0;
    var present_id = product_id;




    for(var key in product_list_array) {
        if(product_list_array.hasOwnProperty(key)) {
            if(product_list_array[key] == present_id){
                check++;
            }
        }
    }






$('.check_product_id').each(function(e){
    if($(this).val() == present_id){
        check++;
    }
    
})
if(check > 0){
    Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Duplicate Product!',
            })
}
if(check == 0 ){

    $(".search_product_add_reminder").append(product_row);
}

}


$(document).on("input", ".user_search_reminder", function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    var user_name = $(this).val();
    var name_length = user_name.length;


    $.ajax({
    type: "GET",
    url: "{{route('customer_crm.user_search')}}",
    data: {
        user_name:user_name,
        name_length:name_length
    },
    success: function (response) {
  
        var serial = 1;
        var row = "";
        if(response.name_length == 0){
            $("#user_show_add_comment_table").html("");
        }

        $.each(response.data, function (index, value) { 

            
        row += `<tr class="tabel_row mb-3" id=row_>
                <td class="pt-4"><a href="javascript:void(0)" onclick="user_add_function_reminder(${value.id}, '${value.name}')">${value.name}</a>
                    <input type="hidden" class="assign_id" value="${value.id}" id="${value.id}" name="">
                </td>
            </tr>`;
            serial = serial+1;

        });

        $(".user_search_result_add_commment").html(row);

        

    }
});
    
});


// User Add Section in Comment
function user_add_function_reminder(user_id, user_name){


    user_row = `<tr class="remove_row">
        <td>
            <div class="alert alert-success alert-dismissible">
                <a href="#" class="close close_button" data-dismiss="alert" aria-label="close">&times;</a>
                <input class="check_user_id assign_id" type="hidden" name="added_product_id" id="${user_id}" value="${user_name}">
                <strong>${user_name}</strong>
            </div>
        </td>
     </tr>`;


    
var check = 0;
var present_id = user_id;
$('.check_user_id').each(function(e){
    if($(this).val() == present_id){
        check++;
    }
})
if(check == 0 ){

    $(".assign_row").append(user_row);
    $(".user_search_reminder").addClass("d-none");
    $("#user_show_add_comment_table").addClass("d-none");

    $(".close_button").click(function (e) { 
        $(".user_search_reminder").removeClass("d-none");
        $("#user_show_add_comment_table").removeClass("d-none");
        $(".user_search_reminder").focus();

    });

}

}

function reminder_delete(reminder_id){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $.ajax({
        type: "POST",
        url: "{{route('marketing_followup.delete_reminder')}}",
        data: {
            reminder_id:reminder_id
        },
        success: function(response) {
            console.log(response);
        }
    });
}

function reminder_view(reminder_id){



    $.ajax({
    type: "GET",
    url: "{{route('marketing_followup.view_reminder')}}",
    data: {
        reminder_id:reminder_id,
    },
    success: function (response) {

        var interested_product = response.products;
            var arrayLength = Object.keys(interested_product).length;


            if(arrayLength == null){
                $(".search_product_add_reminder").html("");
            }else{
                var product_row = "";
                $.each(interested_product, function(index, value) {
                    product_row += `<div class="alert alert-success alert-dismissible remove_row">
                        <a href="#" data-dismiss="alert"></a>
                        <input class="product_class check_product_id" type="hidden" name="added_product_id" id="${index}" value="${value}">
                        <strong>${value}</strong>
                    </div>`;
                });
                $(".search_product_add_reminder").html(product_row);
            }
 
            var status_row = "";
            var status = response.reminder_data[0].status;

            // Populate the dropdown menu
            $('.reminder_status').append($('<option>', {
                value: 1,
                text: 'Pending',
                selected: (status == 1 ? true : false),
                disabled: (status == 1 ? false : true),
                
            }));
            $('.reminder_status').append($('<option>', {
                value: 2,
                text: 'Confirm',
                selected: (status == 2 ? true : false),
                disabled: (status == 2 ? false : true),
            }));
            $('.reminder_status').append($('<option>', {
                value: 3,
                text: 'Rejected',
                selected: (status == 3 ? true : false),
                disabled: (status == 3 ? false : true),
            }));

            
           var assign_row = "";

           if(response.assign_to !== null){
            assign_row = `<label for="">Assign To</label>
                 
                <tr>
                    <td>
                        <div class="alert alert-success alert-dismissible">
                            <a href="#" data-dismiss="alert"></a>
                            <input class="assign_id" type="hidden" name="" id="${response.assign_to}" value="${response.assign_to}">
                            <strong>${response.assign_name}</strong>
                        </div>
                    </td>
                </tr>`;
           }
           else{
            assign_row = `<label for="">Assign To</label>
                 <tr>
                    <td>
                        <input type="text" class="form-control mb-3 user_search_reminder" placeholder="Search Assigned to Person" >
                    </td>
                </tr>`;
           }

           $(".assign_row").append(assign_row);
           $("#view_reminder_note").val(response.reminder_data[0].note);
            $("#view_modal").modal('show');

            $(".close_button_view").click(function(){
                $('.reminder_status').html("");
                $(".assign_row").html("");
            });
        }
    });

}





    </script>

@endsection
