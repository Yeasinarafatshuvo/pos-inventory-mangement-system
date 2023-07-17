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
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email Address</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Registered By</th>
                            <th scope="col">Number of Orders</th>
                        </tr>
                        </thead>
                        <tbody>
                            
                            @foreach ($customer_search_instance as  $key => $customer_search_result)
                                        {{-- {{ dd($customer_search_result->user_id) }} --}}
                                <tr class="alldata">
                                    <td>{{$key+1}}</td>
                                    <td class="text-center pt-4">{{date('d-m-Y', strtotime($customer_search_result->created_at)) }}</td>
                                    <td class="text-center pt-4">{{$customer_search_result->name}}</td>
                                    <td class="text-center pt-4">{{$customer_search_result->email}}</td>
                                    <td class="text-center pt-4">{{$customer_search_result->phone}}</td>
                                    <td class="text-center pt-4">{{ $customer_search_result->registered_by == 1 ? 'Website' : ($customer_search_result->registered_by == 2 ? 'Over Phone Call' : ($customer_search_result->registered_by == 3 ? 'Facebook' : 'WhatsApp')) }}</td>
                                    <td class="text-center pt-4">{{ getUserTotalOrders($customer_search_result->user_id) }}</td>
                                    
                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
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






</script>
@endsection
