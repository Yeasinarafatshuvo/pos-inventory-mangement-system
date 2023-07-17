@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<style>
    .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
    background-color: #a09cbe;
    }
</style>
@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
			<h1 class="h3">{{translate('All Commented Client List')}}</h1>
	</div>
</div>
<script>


</script>



<div class="card">
    <div class="card-body" style=" overflow-x: scroll;">
        <table id="dtBasicExample" class="table table-striped table-bordered table-hover text-center" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Date')}}</th>
                    <th>{{translate('Clinet Name')}}</th>
                    <th data-breakpoints="lg">{{translate('Commented By')}}</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach($CRM_Comments as $key => $CRM_Comment)
                <tr>
                    <td>{{ ($key+1)}}</td>
                    <td>{{date("F j, Y", strtotime($CRM_Comment->created_at))}}</td>
                    <td>{{getUserName($CRM_Comment->crm_id)}}</td>
                    <td>{{getUserName($CRM_Comment->added_by)}}</td>
                </tr>
            @endforeach

            </tbody>
        <div class="aiz-pagination">
          
        </div>
      </table>

    </div>
</div>





       {{-- end  modal for adding customer --}}
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
        $('#dtBasicExample').DataTable({
            pageLength: 10,
            filter: true,
            deferRender: true,
            "searching": true,
        });

})


    </script>

@endsection
