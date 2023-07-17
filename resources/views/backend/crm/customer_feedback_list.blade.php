@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
@section('content')
<style>

</style>
       {{-- Start Customer Profile View Modal  --}}

  <!-- Modal -->
  <div class="modal fade" id="profileViewMOdal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Customer Information</h4>
          <button type="button" class="close close_button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Customer Name:</h5>
                </div>
                <div class="col-md-6">
                    <div id="customer">
                        <ul id="customer_name" class="list-group" style="list-style: none;">
    
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h5>Product Name List:</h5>
                    <div id="product_name">
                        <ul id="product_ul_list" class="list-group" style="list-style: none;">

                        </ul>
                    </div>
                </div>
          </div>
          <div id="row">
            <h5 class="py-1">Feedback Comments</h5>
            <ul id="feedback_description" class="list-group" style="list-style: none;">

            </ul>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary close_button" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

       {{-- End Customer Profile View MOdal --}}

    {{-- Start Customer Profile Edit Modal  --}}

  <div class="modal fade" id="profileEditMOdal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Customer Information</h4>
          <button type="button" class="close close_button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Customer Name:</h5>
                </div>
                <div class="col-md-6">
                    <div id="customer">
                        <ul id="customer_name" class="list-group" style="list-style: none;">
    
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" id="feedback_description">
                    <h5>Product Name List:</h5>
                    <div id="product_name">
                        <ul id="product_ul_list" class="list-group" style="list-style: none;">

                        </ul>
                    </div>

                </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary close_button" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

       {{-- End Customer Profile Edit MOdal --}}

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
            <h2 class="bg-primary  text-center" style="color:white;">Customer Feedback List</h2>
            </div>
        </div>
    </div>
    <div class="row" style="text-align: center" id="all_search_btn">
        <div class="col-md-12"><button class="btn btn-primary" id="date">Search By Date</button></div>
    </div>

    <div class="card" style="display: none" id="search_date">
        <form  method="GET" action="{{ route('customer_feedback_crm.search_by_date') }}">
            @csrf
            <div class="row pb-2" style="background-color: #F2F2F2;margin-left:0; margin-right:0">
                <div class="col-md-2 pt-3 font-weight-bold" style="font-size: 20px">Feedback By Date</div>
                <div class="col-md-1 pt-3 font-weight-bold" style="padding-right: 0px;">Start Date:</div>
                <div class="col-md-2 pt-2" style="padding-left:0"><input type="text" id="datepicker" name="start_date" required class="form-control"></div>
                <div class="col-md-1 pt-3 font-weight-bold" style="padding-right: 0px;">End Date:</div>
                <div class="col-md-2 pt-2" style="padding-left:0"><input type="text" id="datepickertwo" name="end_date" required class="form-control"></div>
                <div class="col-md-2 pt-2"><button class="btn btn-primary btn-md">Search By Date</button></div>
                <div class="col-md-2 pt-2"><button type="button" onclick="delete_date_search()" class="btn btn-success btn-md"><i class="material-icons">&#xe8ba;</i></button></div>
            </div>
        </form>

    </div>

    <div class="card">
        <div class="card-body">
        <div class="row">
        <div class="col-md-12 pr-3" id="print_feedback_area">
            <table id="dtBasicExample" class="table table-striped table-bordered table-sm p-2 text-center" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th scope="col">SL</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Customer Email</th>
                    <th scope="col">Customer Type</th>
                    <th scope="col">Phone Number</th>
                    <th scope="col">Feedback Status</th>
                    <th scope="col">Feedback Details</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody id="data_default_from_database">
                    @foreach ($customer_feedback_instance as  $key => $customer_feedback)
                        <?php 
                            $customer_feedback_details_length = strlen($customer_feedback->feedback_details);
                            
                            if($customer_feedback_details_length >50){
                                $short_description_customer_feedback_details = substr($customer_feedback->feedback_details , 0, 50);
                                $customer_feedback_details_description = $short_description_customer_feedback_details." ...";
                            }
                            else{
                                $customer_feedback_details_description = $customer_feedback->feedback_details;
                            }
                            
                            
                        ?>
                        <tr class="alldata">
                            <td>{{$key+1}}</td>
                            <td class="text-center pt-4">{{$customer_feedback->customer->name ?? ""}}</td>
                            <td class="text-center pt-4">{{$customer_feedback->customer->email ?? ""}}</td>
                            <td class="text-center pt-4">{{$customer_feedback->customer->customer_type == 1  ? "Customer" : ""}}  {{$customer_feedback->customer->customer_type == 2  ? "Dealler" : ""}}  {{$customer_feedback->customer->customer_type == 3  ? "Corporate" : ""}}</td>
                            <td class="text-center pt-4">{{$customer_feedback->customer->phone ?? ""}}</td>
                            <td class="text-center pt-4 {{$customer_feedback->feedback_status== "Positive" ? "text-success" :"" }} {{$customer_feedback->feedback_status== "Negative" ? "text-danger": ""}} {{$customer_feedback->feedback_status== "Ordered" ? "text-info": ""}}">{{$customer_feedback->feedback_status ?? ""}}</td>
                            <td class="text-center pt-4">{{$customer_feedback_details_description ?? ""}}</td>
                            <td class="text-center pt-4">{{date('d-m-Y', strtotime($customer_feedback->created_at)) ?? ""}}</td>
                            <td class="text-center pt-4">
                                <a  data-toggle="modalz" data-target="#profileViewMOdal" value="{{$customer_feedback->customer->id ?? ""}}" onclick='profile_modal({{$customer_feedback->customer->id ?? ""}})'  class="btn btn-info btn-sm">View</a>
                                <a  data-toggle="modalz" data-target="#profileEditMOdal" value="{{$customer_feedback->customer->id ?? ""}}" onclick='profile_modal_edit({{$customer_feedback->customer->id ?? ""}})'  class="btn btn-primary btn-sm">Edit</a>
                            </td>


                        </tr>
                    @endforeach

                </tbody>
              </table>
        </div>
    </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6 offset-md-3 d-flex justify-content-center">
            <button class="btn btn-info" onclick="print_feedback_search_data('print_feedback_area')">Print</button>
        </div>
    </div>
@endsection




@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PrintArea/2.4.1/jquery.PrintArea.min.js" integrity="sha512-mPA/BA22QPGx1iuaMpZdSsXVsHUTr9OisxHDtdsYj73eDGWG2bTSTLTUOb4TG40JvUyjoTcLF+2srfRchwbodg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
      crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">

function profile_modal(id){
   
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var customer_feedback_id = id;
    var name = "";
    var description = "";
    var row = "";
    $.ajax({
        type: "GET",
        url: "{{ route('customer_feedback_crm.view_each') }}",
        data: {customer_feedback_id:customer_feedback_id},
        success: function (response) {
           
            var feedback_details = response.customer_details_feedback[0].feedback_details;
            name = response.customer_details[0].name;
            $.each(response.all_products_name, function(index, value) {
                var list = `<li class="list-group-item list-group-item-success">${value}</li>`;
                $("#product_ul_list").append(list);
             
            });

            row = `<li class="list-group-item list-group-item-success"><textarea rows="4" cols="50" class="form-control">${feedback_details}</textarea></li>`;
            name = `<h5>${name}</h5>`;
            $("#customer_name").append(name);
            $("#feedback_description").append(row);
      
            $("#profileViewMOdal").modal('show');
            $(".close_button").click(function(){

                $("#customer_name").empty();
                $("#product_ul_list").empty();
                $("#feedback_description").empty();
            });
        }
    });
    
}

function profile_modal_edit(id){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var customer_feedback_id = id;
    var name = "";
    var description = "";
    var row = "";
    $.ajax({
        type: "GET",
        url: "{{ route('customer_feedback_crm.view_each') }}",
        data: {customer_feedback_id:customer_feedback_id},
        success: function (response) {
           
            var feedback_details = response.customer_details_feedback[0].feedback_details;
            name = response.customer_details[0].name;
            $.each(response.all_products_name, function(index, value) {
                var list = `<div class="alert alert-success alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>${value}!</strong>
                </div>`;
                //var list = `<li class="list-group-item list-group-item-success">${value}</li>`;
                $("#product_ul_list").append(list);
             
            });

            row = `<li class="list-group-item list-group-item-success"><textarea rows="4" cols="50" class="form-control">${feedback_details}</textarea></li>`;
            name = `<h5>${name}</h5>`;
            $("#customer_name").append(name);
            $("#feedback_description").append(row);
      
            $("#profileViewMOdal").modal('show');
            $(".close_button").click(function(){

                $("#customer_name").empty();
                $("#product_ul_list").empty();
                $("#feedback_description").empty();
            });
        }
    });


}


$(document).ready(function () {
    $('#dtBasicExample').DataTable();
    $('.dataTables_length').addClass('bs-select');


});

$( function() {
    $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

});

$( function() {
    $( "#datepickertwo" ).datepicker({ dateFormat: 'yy-mm-dd' });
})

$('#date').click(function (e) {
    e.preventDefault();
    $('#search_date').show();
    $('#all_search_btn').hide();

});
function delete_date_search()
{
    $('#search_date').hide();
    $('#all_search_btn').show();
    $("#datepicker").val("");
    $("#datepickertwo").val("");
}


function print_feedback_search_data(print_feedback_area){

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
var printContents = document.getElementById(print_feedback_area).innerHTML;
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







</script>
@endsection
