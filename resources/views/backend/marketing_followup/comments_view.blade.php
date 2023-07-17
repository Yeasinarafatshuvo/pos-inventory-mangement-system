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
			<h1 class="h3">{{translate('All Comments of Customers')}}</h1>
	</div>
</div>
<script>


</script>



<div class="card">
    <div class="card-header hide_class">
        <h5 class="mb-0 h6">{{translate('Customers')}}</h5>
        {{-- <div class="row" style="text-align: center" id="all_search_btn">
            <div class="col-md-12"><button class="btn btn-primary" id="date">Search By Date</button></div>
        </div> --}}
    
        <button data-toggle="modal" data-target="#add_comment_modal" class="btn btn-info"><i class="fa-solid fa-comments"></i> Add Comment</button>

    </div>
    <div class="card-body" style=" overflow-x: scroll;">
        <table id="dtBasicExample" class="table table-striped table-bordered table-hover text-center" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Date')}}</th>
                    <th>{{translate('Client Name')}}</th>
                    <th>{{translate('Comment')}}</th>
                    <th data-breakpoints="lg">{{translate('Commented By')}}</th>
                    <th data-breakpoints="lg">{{translate('Action')}}</th>
                </tr>
            </thead>
            @foreach($CRM_Comments as $key => $CRM_Comment)
                <tr>
                    
                    <td>{{ ($key+1)}}</td>
                    <td>{{date("F j, Y", strtotime($CRM_Comment->created_at))}}</td>
                    <td>{{getUserName($CRM_Comment->crm_id)}}</td>
                    <td>{{strip_tags($CRM_Comment->comments)}}</td>
                    <td>{{getUserName($CRM_Comment->added_by)}}</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="marketing_followup_comment_edit('{{$CRM_Comment->id}}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        {{-- <button class="btn btn-sm btn-primary" onclick="view_each_customer('{{$CRM_Comment->id}}')"><i class="fa-sharp fa-solid fa-eye"></i></button> --}}
                        <a class="btn btn-sm btn-primary" onclick="view_each_customer('{{$CRM_Comment->crm_id}}')"><i class="fa-sharp fa-solid fa-eye"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="marketing_followup_comment_delete('{{$CRM_Comment->id}}')"><i class="fa-sharp fa-solid fa-trash"></i></button>
                        
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


{{-- Start  modal for Add Comment Modal--}}
    <div class="modal fade" id="add_comment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title h6">Add Comment</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="tab-pane fade show active" id="v-pills-add-comments" role="tabpanel" aria-labelledby="v-pills-add-comments-tab">
                            <label for="#comment">Comment <span class="text-danger">*</span></label>
                            <textarea id="add_comment_id" class="aiz-text-editor add_comment_vanish form-control mb-3" name="description"></textarea>

                            <input type="text" hidden class="comment_class" value="" >

                            <table>
                                <tbody class="search_result_product_add_comment">

                                </tbody>
                            </table>
                            
                            <label for="">Interested Product</label>
                            <input type="text" class="form-control mb-3 product_name_search_add_comment" placeholder="product search here" id="product_name_search_id_add_comment">

                            <table class="table" id="product_show_reminder_table">
                                <tbody class="product_search_result_add_comment shadow_class">
                                </tbody>
                            </table>

                            <label for="">Customer Name</label>
                            <input type="text" class="form-control mb-3 user_search_add_comment" oninput="customer_search()" placeholder="Search Customer Name">
                            <table>
                                <tbody class="search_user_add_comment">

                                </tbody>
                            </table>

                            <table class="table" id="user_show_add_comment_table">
                                <tbody class="user_search_result_add_commment shadow_class">
                                </tbody>
                            </table>
                            <input type="hidden" id="user_id" value="{{ Auth::user()->id }}">
                            <input type="button" class="btn btn-primary" onclick="add_comment_function()" value="Add Comment">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary mt-2" data-dismiss="modal">Cancel</button>
                    </div>
            </div>
        </div>
    </div>
    {{-- end  modal for Edit Modal --}}

    {{-- start  modal for Edit Modal--}}
    <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title h6">Edit Information</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="tab-pane fade show active" id="v-pills-add-comments" role="tabpanel" aria-labelledby="v-pills-add-comments-tab">
                            <label for="#comment">Comment <span class="text-danger">*</span></label>
                            <textarea id="add_comment" class="add_comment_vanish form-control mb-3" name="description"></textarea>
                            <input type="text" hidden class="comment_class" value="" >

                            <label for="#product_name_search">Interested Product</label>
                            <input type="text" class="form-control mb-3 product_name_search" value="" placeholder="product search here" id="product_name_search_id">
                            <input type="text" hidden class="form-control mb-3" id="product_search_result" value="" >
                            <table class="table" id="product_show_table">
                                <tbody class="product_search_result shadow_class">
                                </tbody>
                            </table>

                            <table class="table" id="product_show_table">
                                <tbody class="search_product_add_comment">
                                </tbody>
                            </table>
                            <input type="button" class="btn btn-primary" onclick="update_comment_function()" value="Update Comment">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary mt-2" data-dismiss="modal">Cancel</button>
                    </div>
            </div>
        </div>
    </div>
    {{-- end  modal for Edit Modal --}}


    {{-- start  modal for View Modal--}}
    <div class="modal fade" id="view_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title h6">View Modal</h4>
                        <button type="button" class="close close_view" data-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body text-center">
                        <table id="dtBasicExample1" class="table table-striped table-bordered text-center" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Client Name</th>
                                    <th scope="col">Comment</th>
                                    <th scope="col">Commented By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center pt-4" id="date_view"></td>
                                    <td class="text-center pt-4" id="client_name_view"></td>
                                    <td class="text-center pt-4" id="comment_view"></td>
                                    <td class="text-center pt-4" id="commented_by_view"></td>
                                </tr>

                            </tbody>
                      </table>
                        {{-- <i class="la la-4x la-warning text-warning mb-4"></i>
                        <p class="fs-18 fw-600 mb-1">Are you sure to delete this?</p>
                        <div>All data related to this will be deleted.</div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary mt-2 close_view" data-dismiss="modal">Close</button>
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

// Start For Product Ajax Search in Add Comment Modal
var onKeyup = function(evt) {
    $(".product_search_result").html("");
    product_show_add_comment();
};
var input = document.getElementById('product_name_search_id_add_comment');
input.addEventListener('input', onKeyup, false);

function product_show_add_comment(){
    setTimeout(function() {
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var product_name = $('.product_name_search_add_comment').val();

        $.ajax({
        type: "GET",
        url: "{{route('customer_crm.product_search')}}",
        data: {
            product_name:product_name
        },
        success: function (response) {
            
            var serial = 1;
            if(response == null){
                $("#product_search_result").html("");
            }else{
                var row = "";
                $.each(response.data, function (index, value) { 

                 row += `<tr class="tabel_row mb-3" id=row_>
                        <td class="pt-4"><a href="javascript:void(0)" onclick="product_add_function_comment(${value.id}, '${value.name.replace(/["']/g, "")}')">${value.name}</a></td>
                    </tr>`;
                    serial = serial+1;
                
                });
                $(".product_search_result_add_comment").html(row);
            }


        }
    });


    }, 50);
}
// End For Product Ajax Search in Add Comment Modal



// Start for Product Ajax Search in Edit Comment Modal
var onKeyup = function(evt) {
    $(".product_search_result").html("");
    product_show();
  console.info(evt.target.value);
};
var input = document.getElementById('product_name_search_id');
input.addEventListener('input', onKeyup, false);

function product_show(){
    setTimeout(function() {
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var product_name = $('.product_name_search').val();

        $.ajax({
        type: "GET",
        url: "{{route('customer_crm.product_search')}}",
        data: {
            product_name:product_name
        },
        success: function (response) {
            
            var serial = 1;
            if(response == null){
                $("#product_search_result").html("");
            }else{
                var row = "";
                $.each(response.data, function (index, value) { 

                    
                 row += `<tr class="tabel_row mb-3" id=row_>
                        <td class="pt-4"><a href="javascript:void(0)" onclick="product_add_function(${value.id}, '${value.name.replace(/["']/g, "")}')">${value.name}</a></td>
                    </tr>`;
                    serial = serial+1;
                

                });
                $(".product_search_result").html(row);
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

function marketing_followup_comment_edit(id){

    console.log(id);

    $.ajax({
        type: "GET",
        url: "{{ route('marketing_followup.getting_comments_view') }}",
        data: {
            id: id
        },
        success: function (response) {


            var product_name_array = response.product_name_array;
            var comment = response.data[0].comments;
            var row = "";
            $.each(product_name_array, function (index, value) {
                
                $.each(value, function(innerIndex, innerValue) {
                    
                    row += `<div class="alert alert-success alert-dismissible remove_row">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <input class="check_product_id" type="hidden" name="added_product_id" id="${innerIndex}" value="${innerIndex}">
                        <strong>${innerValue}</strong>
                    </div>`;

                });

            });

            $(".search_product_add_comment").html(row);
            $("#add_comment").val(comment);
            $(".comment_class").val(response.data[0].id);
            $("#edit_modal").modal('show');
            
        }
    });
}

function marketing_followup_comment_view(id){

    console.log(id);
    
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

function marketing_followup_comment_delete(id){

    console.log(id);

    $("#delete_modal").modal('show');

        $("#delete_row").click(function (e) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
            type: "POST",
            url: "{{ route('marketing_followup.delete_comments') }}",
            data: {
                id:id
            },
            success: function (response) {
                if(response.status = "success"){
                    window.location.href = '{{ route("marketing_followup.comments") }}';
                }
                else{
                    alert("There is something error");
                }
                
            }
        });
    });


}



function update_comment_function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var comment_id = $(".comment_class").val();
    var comment = $("#add_comment").val();

    var product_added_array = [];
    $(".search_product_add_comment").find("input").each(function(){ product_added_array.push(this.id); });


    
    $.ajax({
        type: "POST",
        url: "{{route('marketing_followup.update_comments')}}",
        data: {
            comment_id:comment_id,
            comment:comment,
            product_added_array:product_added_array
        },
        success: function (response) {

            $('#edit_modal').modal('hide');
            window.location.href = '{{ route("marketing_followup.comments") }}';
            Swal.fire(
                'Good job!',
                'You clicked the button!',
                'success'
            )

        }
    });
}

function product_add_function(product_id, product_name){

console.log(product_id);

    product_row = `<div class="alert alert-success alert-dismissible remove_row">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <input class="check_product_id" type="hidden" name="added_product_id" id="${product_id}" value="${product_id}">
        <strong>${product_name}</strong>
    </div>`;



    
var check = 0;
var present_id = product_id;
$('.check_product_id').each(function(e){
    if($(this).val() == present_id){
        check++;
    }
    console.log($(this).val());
})
if(check > 0){
    Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Duplicate Product!',
            })
}
if(check == 0 ){

    $(".search_product_add_comment").append(product_row);
}

}

function product_add_function_comment(product_id, product_name){


    product_row = `<div class="alert alert-success alert-dismissible remove_row">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <input class="check_product_id" type="hidden" name="added_product_id" id="${product_id}" value="${product_id}">
        <strong>${product_name}</strong>
    </div>`;



    
    var check = 0;
    var present_id = product_id;
    $('.check_product_id').each(function(e){
        if($(this).val() == present_id){
            check++;
        }
        console.log($(this).val());
    })
    if(check > 0){
        Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Duplicate Product!',
                })
    }
    if(check == 0 ){

        $(".search_result_product_add_comment").append(product_row);
    }

}

function customer_search(){

$(".search_user_add_comment").html("");

setTimeout(function() {
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    var user_name = $('.user_search_add_comment').val();
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
                        <input type="hidden" class="customer_name" value="${value.id}" name="">
                    </td>
                </tr>`;
                serial = serial+1;
            

            });
            $(".user_search_result_add_commment").html(row);
        


    }
});



}, 50);
}

function user_add_function_reminder(user_id, user_name){

//console.log(user_id);

    user_row = `<tr class="remove_row">
        <td>
            <div class="alert alert-success alert-dismissible">
                <a href="#" class="close close_button" data-dismiss="alert" aria-label="close">&times;</a>
                <input class="check_user_id" type="hidden" name="added_product_id" id="${user_id}" value="${user_name}">
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
        console.log($(this).val());
    })
    if(check == 0 ){

        $(".search_user_add_comment").append(user_row);
        $(".user_search_add_comment").addClass("d-none");
        $("#user_show_add_comment_table").addClass("d-none");

        $(".close_button").click(function (e) { 
            e.preventDefault();
            $(".user_search_add_comment").removeClass("d-none");
            $("#user_show_add_comment_table").removeClass("d-none");
            $(".user_search_add_comment").focus();

        });

    }

}


// Add Comment Submit
function add_comment_function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    var comment_text = $('#add_comment_id').val();
    var comment_text = $(comment_text).text();

    var user_id = $("#user_id").val();
    var customer_id = $(".customer_name").val();
    var product_added_array = [];
    $(".search_result_product_add_comment").find("input").each(function(){ product_added_array.push(this.id); });

    $.ajax({
        type: "POST",
        url: "{{route('marketing_followup.add_comment')}}",
        data: {
            comment_text:comment_text,
            user_id:user_id,
            customer_id:customer_id,
            product_added_array:product_added_array

        },
        success: function (response) {

            if(response.status=="success"){
                Swal.fire(
                'Good job!',
                'You clicked the button!',
                'success'
                )
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                })
            }

            var url = "http://127.0.0.1:8000/admin/marketing_followup/comments_view";
            location.href = url;

        }
    });
}

function view_each_customer(customer_id){

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var customer_id = customer_id;
$.ajax({
    type: "GET",
    url: "{{route('customer_crm.check_customer')}}",
    data: {
        customer_id:customer_id
    },
    success: function (response) {
        window.location.replace('http://127.0.0.1:8000/admin/customers/'+ customer_id+'#dashboard_h', , "_blank");
    }
});
}



// $("#add_comment_function").click(function (e) {
//     e.preventDefault();


// });




    </script>

@endsection
