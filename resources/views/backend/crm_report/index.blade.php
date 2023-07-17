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
			<h1 class="h3">{{translate('All Information about CRM Report')}}</h1>
            <input type="hidden" id="check_user_id" data-id="{{ Auth::user()->id }}" value="{{ Auth::user()->user_type }}">
	</div>
</div>


<div class="card">
    <div class="card-header hide_class">
        <button data-toggle="modal" data-target="#add_crm_modal" class="btn btn-info"><i class="fa-solid fa-bell"></i> Add CRM Report</button>

    </div>
    <div class="card-body" style=" overflow-x: scroll;">
        <table id="dtBasicExample" class="table table-striped table-bordered table-hover text-center" style="width:100%">
            <thead>
                <tr>
                    <th></th>
                    <th>#</th>
                    <th>{{translate('Date')}}</th>
                    <th>{{translate('User Name')}}</th>
                    <th>{{translate('Whatsapp Sent')}}</th>
                    <th>{{translate('Whatsapp Response')}}</th>
                    <th>{{translate('Whatsapp Comment')}}</th>
                    <th>{{translate('Email Sent')}}</th>
                    <th>{{translate('Email Response')}}</th>
                    <th>{{translate('Email Comment')}}</th>
                    <th>{{translate('Phone Call')}}</th>
                    <th>{{translate('Phone Call Response')}}</th>
                    <th>{{translate('Phone Call Comment')}}</th>
                    <th>{{translate('Action')}}</th>

                </tr>
            </thead>

            <tbody>

            </tbody>
            <div class="aiz-pagination">
            
            </div>
      </table>

      <div class="text-center">
        <button class="btn btn-danger text-white print_class" onclick="print_report()"><i class="fa-solid fa-print"></i></button>
        <h3 class="d-none data_avaiable">There is no data avaiable</h3>
      </div>


    </div>
</div>

    {{-- start  modal for Edit Modal--}}
    <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title h6">Edit Information</h4>
                        <button type="button" class="close close_button_view" data-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#update_whatsapp_sent">No of Whatsapp Message Sent</span></label>
                                            <input type="number" class="form-control" id="update_whatsapp_sent" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#update_whatsapp_response">No of Whatsapp Message Response</span></label>
                                            <input type="number" class="form-control" id="update_whatsapp_response" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#update_whatsapp_comment">Whatsapp Comment</label>
                                            <textarea class="form-control" id="update_whatsapp_comment"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#update_email_sent">No of Email Sent</span></label>
                                            <input type="number" class="form-control" id="update_email_sent" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#update_email_response">No of Email Response</span></label>
                                            <input type="number" class="form-control" id="update_email_response" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#update_email_comment">Email Comment</label>
                                            <textarea class="form-control" id="update_email_comment"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#update_phone_call_sent">No of Phone Call</span></label>
                                            <input type="number" class="form-control" id="update_phone_call_sent" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#update_phone_call_response">No of Phone Call Response</span></label>
                                            <input type="number" class="form-control" id="update_phone_call_response" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#update_phone_comment">Phone Call Comment</label>
                                            <textarea class="form-control" id="update_phone_comment"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <input type="text" hidden id="update_user_id" value="{{ Auth::user()->id }}">
                                <input type="text" hidden id="crm_report_id" value="">
                                <button type="submit" class="btn btn-primary" onclick="update_report_function()">Update</button>
            
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary mt-2 close_button_view" data-dismiss="modal">Cancel</button>
                    </div>
            </div>
        </div>
    </div>
    {{-- end  modal for Edit Modal --}}

    {{-- start  modal for View Modal --}}
    <div class="modal fade" id="add_crm_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title h6">View Information</h4>
                        <button type="button" class="close close_button_view" data-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#number_of_whatsapp_message_sent">No of Whatsapp Message Sent</span></label>
                                            <input type="number" class="form-control" id="number_of_whatsapp_message_sent" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#number_of_whatsapp_message_response">No of Whatsapp Message Response</span></label>
                                            <input type="number" class="form-control" id="number_of_whatsapp_message_response" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#whatsapp_comment">Whatsapp Comment</label>
                                            <textarea class="form-control" id="whatsapp_comment"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#number_of_email_sent">No of Email Sent</span></label>
                                            <input type="number" class="form-control" id="number_of_email_sent" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#number_of_email_response">No of Email Response</span></label>
                                            <input type="number" class="form-control" id="number_of_email_response" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#email_comment">Email Comment</label>
                                            <textarea class="form-control" id="email_comment"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#number_of_phone_call_sent">No of Phone Call</span></label>
                                            <input type="number" class="form-control" id="number_of_phone_call_sent" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#number_of_phone_call_response">No of Phone Call Response</span></label>
                                            <input type="number" class="form-control" id="number_of_phone_call_response" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#phone_comment">Phone Call Comment</label>
                                            <textarea class="form-control" id="phone_comment"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <input type="text" hidden id="user_id" value="{{ Auth::user()->id }}">
                                <button type="submit" class="btn btn-primary" onclick="add_report_function()">Submit</button>
            
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




$(document).ready(function() {
$.ajax({
type: 'GET',
url: '{{ route("crm_report.view_all") }}',
success: function(response) {


    var tbody = $('#dtBasicExample tbody');
    var serial = 1;


    for (var i = 0; i < response.data.length; i++) {

    var item = response.data[i];
    var id = item.user_id;
    var user = null;
    var userID = $('#check_user_id').data('id');
    var userType = $('#check_user_id').val();

    if(userType == "admin" || (userType == "staff" && userID == id)){

        if(userType == "admin"){
            $.ajax({
                url: '{{ route("crm_report.user_name_view") }}',
                method: 'GET',
                async: false,
                data: {id:id},
                success: function(data) {
                    user = data;
                }
            });
        }

        if(userType == "staff"){
            $.ajax({
                url: '{{ route("crm_report.user_name_view") }}',
                method: 'GET',
                async: false,
                data: {id:id},
                success: function(data) {
                    user = data;
                }
            });
        }

        var tr = $('<tr>');
        tr.append($('<td>').html('<input onclick="checkbox('+item.id+')" type="checkbox" value="">'));
        tr.append($('<td>').text(serial));
        tr.append($('<td>').css('white-space', 'nowrap').text(moment(item.created_at).format("DD-MM-YYYY")));
        tr.append($('<td>').text((user ? user.name : 'N/A')))
        tr.append($('<td>').text(item.number_of_whatsapp_sent));
        tr.append($('<td>').text(item.number_of_whatsapp_response));
        tr.append($('<td>').text(item.whatsapp_comment));
        tr.append($('<td>').text(item.number_of_email_sent));
        tr.append($('<td>').text(item.number_of_email_response));
        tr.append($('<td>').text(item.email_comment));
        tr.append($('<td>').text(item.number_of_phone_call));
        tr.append($('<td>').text(item.number_of_phone_call_response));
        tr.append($('<td>').text(item.phone_call_comment));
        tr.append($('<td>').html('<button class="btn btn-sm btn-info editClass" id="' + item.id + '"><i class="fa-solid fa-pen-to-square"></i></button> <button class="btn btn-sm btn-danger text-white deleteClass" id="' + item.id + '" ><i class="fa-solid fa-trash"></i></button>'));
        tbody.append(tr);
        serial++;
    }
    }


    if (response.data.length > 0) {
        $('#dtBasicExample').DataTable({
        "paging": true,
        "searching": true,
        "info": true,
        "responsive": true,
        });
    } else {
        $('.print_class').addClass('d-none');
        $('.data_avaiable').addClass('d-block');
        $('.data_avaiable').removeClass('d-none');
        tbody.empty();
    }
}
});

});







function add_report_function(){

    var user_id = $("#user_id").val();
    var number_of_whatsapp_message_sent = $("#number_of_whatsapp_message_sent").val();
    var number_of_whatsapp_message_response = $("#number_of_whatsapp_message_response").val();
    var whatsapp_comment = $("#whatsapp_comment").val();

    var number_of_email_sent = $("#number_of_email_sent").val();
    var number_of_email_response = $("#number_of_email_response").val();
    var email_comment = $("#email_comment").val();

    var number_of_phone_call_sent = $("#number_of_phone_call_sent").val();
    var number_of_phone_call_response = $("#number_of_phone_call_response").val();
    var phone_comment = $("#phone_comment").val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "{{route('crm_report.add_report')}}",
        data: {
            user_id                             :   user_id,
            number_of_whatsapp_message_sent     :   number_of_whatsapp_message_sent,
            number_of_whatsapp_message_response :   number_of_whatsapp_message_response,
            whatsapp_comment                    :   whatsapp_comment,
            number_of_email_sent                :   number_of_email_sent,
            number_of_email_response            :   number_of_email_response,
            email_comment                       :   email_comment,
            number_of_phone_call_sent           :   number_of_phone_call_sent,
            number_of_phone_call_response       :   number_of_phone_call_response,
            phone_comment                       :   phone_comment,
        },
        success: function (response) {

            $("#number_of_whatsapp_message_sent").val("");
            $("#number_of_whatsapp_message_response").val("");
            $("#whatsapp_comment").val("");
            $("#number_of_email_sent").val("");
            $("#number_of_email_response").val("");
            $("#email_comment").val("");
            $("#number_of_phone_call_sent").val("");
            $("#number_of_phone_call_response").val("");
            $("#phone_comment").val("");
            $("#add_crm_modal").modal('hide');

            window.location.href = "{{route('crm_report.view')}}";


        }
    });
}

$('#dtBasicExample tbody').on('click', '.editClass', function() {
    var id = $(this).attr('id');

    
    
    $.ajax({
        type: "GET",
        url: "{{route('crm_report.edit')}}",
        data: {
            id  :  id
        },
        success: function (response) {
            $("#update_whatsapp_sent").val(response.crm_report.number_of_whatsapp_sent);
            $("#update_whatsapp_response").val(response.crm_report.number_of_whatsapp_response);
            $("#update_whatsapp_comment").val(response.crm_report.whatsapp_comment);
            $("#update_email_sent").val(response.crm_report.number_of_email_sent);
            $("#update_email_response").val(response.crm_report.number_of_email_response);
            $("#update_email_comment").val(response.crm_report.email_comment);
            $("#update_phone_call_sent").val(response.crm_report.number_of_phone_call);
            $("#update_phone_call_response").val(response.crm_report.number_of_phone_call_response);
            $("#update_phone_comment").val(response.crm_report.phone_call_comment);
            $("#crm_report_id").val(response.crm_report.id)
            $("#edit_modal").modal('show');

        }
    });

});

$('#dtBasicExample tbody').on('click', '.deleteClass', function() {
    var id = $(this).attr('id');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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
            $.ajax({
                type: "POST",
                url: "{{route('crm_report.delete')}}",
                data: {
                    id  :  id
                },
                success: function (response) {
                    window.location.href = "{{route('crm_report.view')}}";
                    Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                    )
                }
            });
        }
    })
    


});

function update_report_function(){
    var user_id = $("#update_user_id").val();
    var crm_report_id = $("#crm_report_id").val();
    var number_of_whatsapp_message_sent =$("#update_whatsapp_sent").val();
    var number_of_whatsapp_message_response =$("#update_whatsapp_response").val();
    var whatsapp_comment =$("#update_whatsapp_comment").val();
    var number_of_email_sent =$("#update_email_sent").val();
    var number_of_email_response =$("#update_email_response").val();
    var email_comment =$("#update_email_comment").val();
    var number_of_phone_call_sent =$("#update_phone_call_sent").val();
    var number_of_phone_call_response =$("#update_phone_call_response").val();
    var phone_comment =$("#update_phone_comment").val();

    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "{{route('crm_report.update_report')}}",
        data: {
            crm_report_id                       :   crm_report_id,
            user_id                             :   user_id,
            number_of_whatsapp_message_sent     :   number_of_whatsapp_message_sent,
            number_of_whatsapp_message_response :   number_of_whatsapp_message_response,
            whatsapp_comment                    :   whatsapp_comment,
            number_of_email_sent                :   number_of_email_sent,
            number_of_email_response            :   number_of_email_response,
            email_comment                       :   email_comment,
            number_of_phone_call_sent           :   number_of_phone_call_sent,
            number_of_phone_call_response       :   number_of_phone_call_response,
            phone_comment                       :   phone_comment,
        },
        success: function (response) {

            $("#number_of_whatsapp_message_sent").val("");
            $("#number_of_whatsapp_message_response").val("");
            $("#whatsapp_comment").val("");
            $("#number_of_email_sent").val("");
            $("#number_of_email_response").val("");
            $("#email_comment").val("");
            $("#number_of_phone_call_sent").val("");
            $("#number_of_phone_call_response").val("");
            $("#phone_comment").val("");
            $("#add_crm_modal").modal('hide');

            window.location.href = "{{route('crm_report.view')}}";


        }
    });


}


var id_list_array = [];
function checkbox(id){
    id_list_array.push(id);
}

function print_report(){

    window.open("{{ route('crm_report.print_view') }}", "_blank");


    $.ajax({
        type: "GET",
        url: "{{route('crm_report.print_report')}}",
        data: {
            id_list_array  :  id_list_array
        },
        success: function (response) {
            console.log(response);
        }
    });
}






    </script>

@endsection
