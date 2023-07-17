@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
@section('content')
<style>

</style>

    <div class="card" >
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 pr-3" id="print_area">
                    <h2 id="search_list" class="text-center" style="display:none">Search List from</h2>
                    <table id="dtBasicExample" class="table table-striped table-bordered table-sm p-2 text-center prepend" cellspacing="0" width="100%">
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
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($customer_search_feedback_instance as  $key => $customer_search_feedback)

                                <tr class="alldata">
                                    <td>{{$key+1}}</td>
                                    <td class="text-center pt-4">{{$customer_search_feedback->customer->name ?? ""}}</td>
                                    <td class="text-center pt-4">{{$customer_search_feedback->customer->email ?? ""}}</td>
                                    <td class="text-center pt-4">{{$customer_search_feedback->customer->customer_type == 1  ? "Customer" : ""}}  {{$customer_search_feedback->customer->customer_type == 2  ? "Dealler" : ""}}  {{$customer_search_feedback->customer->customer_type == 3  ? "Corporate" : ""}}</td>
                                    <td class="text-center pt-4">{{$customer_search_feedback->customer->phone ?? ""}}</td>
                                    <td class="text-center pt-4">{{$customer_search_feedback->feedback_status ?? ""}}</td>
                                    <td class="text-center pt-4">{{$customer_search_feedback->feedback_details ?? ""}}</td>
                                    <td class="text-center pt-4">{{date('d-m-Y', strtotime($customer_search_feedback->created_at)) ?? ""}}</td>
                                    
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
            <button class="btn btn-info" onclick="print_search_data('print_area')">Print</button>
        </div>
    </div>
@endsection




@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
      crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PrintArea/2.4.1/jquery.PrintArea.min.js" integrity="sha512-mPA/BA22QPGx1iuaMpZdSsXVsHUTr9OisxHDtdsYj73eDGWG2bTSTLTUOb4TG40JvUyjoTcLF+2srfRchwbodg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('#dtBasicExample').DataTable();
    $('.dataTables_length').addClass('bs-select');

});



function print_search_data(print_area){
    $('#search_list').addClass('d-block');
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
    var printContents = document.getElementById(print_area).innerHTML;
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
$("cancel-button").click(function(){
  alert("The paragraph was clicked.");
});








</script>
@endsection
